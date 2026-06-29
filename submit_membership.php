<?php
/**
 * submit_membership.php
 * -----------------------------------------------------------
 * Handles the AJAX POST from membership.php:
 *  1. Validates input
 *  2. Generates a unique Registration ID
 *  3. Saves the record into MySQL (phpMyAdmin) via config.php
 *  4. Emails a confirmation to the member + a notification to admin
 *     using PHPMailer + Gmail SMTP
 *  5. Returns a JSON response consumed by the JS on membership.php
 * -----------------------------------------------------------
 */

header('Content-Type: application/json');

require_once 'config.php';
require_once 'villages_data.php';
require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function respond($status, $message, $extra = []) {
    echo json_encode(array_merge(['status' => $status, 'message' => $message], $extra));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond('error', 'Invalid request method.');
}

// ---------- Collect & sanitize input ----------
$full_name = trim($_POST['full_name'] ?? '');
$email     = trim($_POST['email'] ?? '');
$phone     = trim($_POST['phone'] ?? '');
$state     = 'Delhi'; // fixed default as per requirement
$location  = trim($_POST['location'] ?? '');
$district  = trim($_POST['district'] ?? '');
$village   = trim($_POST['village'] ?? '');

// ---------- Validation ----------
if ($full_name === '' || $email === '' || $phone === '' || $location === '' || $district === '' || $village === '') {
    respond('error', 'Please fill in all required fields.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond('error', 'Please enter a valid email address.');
}

if (!preg_match('/^[0-9]{10}$/', $phone)) {
    respond('error', 'Phone number must be exactly 10 digits.');
}

if (!in_array($location, $delhi_locations, true)) {
    respond('error', 'Invalid location selected.');
}

if (!isset($villages_data[$district])) {
    respond('error', 'Invalid district selected.');
}

if (!in_array($village, $villages_data[$district], true)) {
    respond('error', 'Invalid village selected for the chosen district.');
}

// ---------- DB connection ----------
$conn = getDbConnection();

// ---------- Generate unique Registration ID ----------
// Format: SF-<YEAR>-<5 digit zero padded incremental number>
function generateRegistrationId($conn) {
    $year = date('Y');
    $prefix = "SF-{$year}-";

    $result = $conn->query("SELECT registration_id FROM members WHERE registration_id LIKE '{$prefix}%' ORDER BY id DESC LIMIT 1");
    $nextNum = 1;
    if ($result && $row = $result->fetch_assoc()) {
        $lastId = $row['registration_id'];
        $lastNum = (int) substr($lastId, strlen($prefix));
        $nextNum = $lastNum + 1;
    }
    return $prefix . str_pad($nextNum, 5, '0', STR_PAD_LEFT);
}

$registration_id = generateRegistrationId($conn);

// ---------- Insert into database ----------
$stmt = $conn->prepare("INSERT INTO members (registration_id, full_name, email, phone, state, location, district, village) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    respond('error', 'Database error: ' . $conn->error);
}
$stmt->bind_param('ssssssss', $registration_id, $full_name, $email, $phone, $state, $location, $district, $village);

if (!$stmt->execute()) {
    // Handle rare duplicate registration_id race condition by retrying once
    if ($conn->errno === 1062) {
        $registration_id = generateRegistrationId($conn);
        $stmt->bind_param('ssssssss', $registration_id, $full_name, $email, $phone, $state, $location, $district, $village);
        $stmt->execute();
    } else {
        respond('error', 'Failed to save registration: ' . $stmt->error);
    }
}
$stmt->close();

// ---------- Send confirmation email (member) + notification (admin) ----------
$emailSent = true;
$emailError = '';

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USERNAME;
    $mail->Password   = SMTP_PASSWORD;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = SMTP_PORT;

    $mail->setFrom(SMTP_USERNAME, SMTP_FROM_NAME);
    $mail->addAddress($email, $full_name);
    $mail->addBCC(ADMIN_NOTIFY_EMAIL);

    $mail->isHTML(true);
    $mail->Subject = "Welcome to Sahara Foundation - Registration ID: {$registration_id}";
    $mail->Body = "
        <div style='font-family:Arial,sans-serif;max-width:600px;margin:auto;'>
            <h2 style='color:#1f9a3a;'>Membership Confirmed!</h2>
            <p>Dear " . htmlspecialchars($full_name) . ",</p>
            <p>Thank you for registering as a member of <strong>Sahara Foundation</strong>. Your details have been recorded successfully.</p>
            <table style='width:100%;border-collapse:collapse;margin:16px 0;'>
                <tr><td style='padding:8px;border:1px solid #ddd;'><strong>Registration ID</strong></td><td style='padding:8px;border:1px solid #ddd;'>{$registration_id}</td></tr>
                <tr><td style='padding:8px;border:1px solid #ddd;'><strong>Name</strong></td><td style='padding:8px;border:1px solid #ddd;'>" . htmlspecialchars($full_name) . "</td></tr>
                <tr><td style='padding:8px;border:1px solid #ddd;'><strong>Phone</strong></td><td style='padding:8px;border:1px solid #ddd;'>" . htmlspecialchars($phone) . "</td></tr>
                <tr><td style='padding:8px;border:1px solid #ddd;'><strong>State</strong></td><td style='padding:8px;border:1px solid #ddd;'>" . htmlspecialchars($state) . "</td></tr>
                <tr><td style='padding:8px;border:1px solid #ddd;'><strong>Location</strong></td><td style='padding:8px;border:1px solid #ddd;'>" . htmlspecialchars($location) . "</td></tr>
                <tr><td style='padding:8px;border:1px solid #ddd;'><strong>District</strong></td><td style='padding:8px;border:1px solid #ddd;'>" . htmlspecialchars($district) . "</td></tr>
                <tr><td style='padding:8px;border:1px solid #ddd;'><strong>Village</strong></td><td style='padding:8px;border:1px solid #ddd;'>" . htmlspecialchars($village) . "</td></tr>
            </table>
            <p>Please keep this Registration ID safe for future reference.</p>
            <p style='margin-top:24px;'>Warm regards,<br><strong>Sahara Foundation Team</strong></p>
        </div>
    ";
    $mail->AltBody = "Dear {$full_name},\n\nThank you for registering with Sahara Foundation.\nYour Registration ID: {$registration_id}\nLocation: {$location}\nDistrict: {$district}\nVillage: {$village}\n\nRegards,\nSahara Foundation Team";

    $mail->send();
} catch (Exception $e) {
    $emailSent = false;
    $emailError = $mail->ErrorInfo;
}

// ---------- Final response ----------
// Registration is still considered successful even if the email fails,
// since the record was already saved to the database.
if ($emailSent) {
    respond('success', 'Registration completed and confirmation email sent.', ['registration_id' => $registration_id]);
} else {
    respond('success', 'Registration saved, but the confirmation email could not be sent (' . $emailError . ').', ['registration_id' => $registration_id]);
}
