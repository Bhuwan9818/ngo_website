<?php
/**
 * config.php
 * -----------------------------------------------------------
 * Central configuration for the Membership Registration system.
 * Edit the values below to match your hosting / XAMPP / Gmail setup.
 * -----------------------------------------------------------
 */

// ---------- DATABASE (phpMyAdmin / MySQL) SETTINGS ----------
define('DB_HOST', 'localhost');
define('DB_NAME', 'sahara_foundation');
define('DB_USER', 'root');      // change for your server
define('DB_PASS', '');          // change for your server

// ---------- EMAIL (Gmail SMTP) SETTINGS ----------
// 1. Use a Gmail account.
// 2. Turn on 2-Step Verification on that Gmail account.
// 3. Create an "App Password" (Google Account > Security > App Passwords)
//    and paste the 16-character app password below (NOT your normal Gmail password).
define('SMTP_HOST',      'smtp.gmail.com');      // smtp.gmail.com OR smtp.hostinger.com
define('SMTP_PORT',      587);                    // 587 = STARTTLS  |  465 = SSL
define('SMTP_SECURE',    'tls');                  // 'tls' for port 587, 'ssl' for port 465
define('SMTP_USERNAME',  'bhuwansingh8860@gmail.com'); // ← your SMTP login email
define('SMTP_PASSWORD',  'metu ghcw azds jvip');  // <-- Gmail App Password
define('SMTP_FROM_NAME', 'Sahara Foundation');

// Email address where the NGO admin should receive a copy of every
// new membership registration (can be same as SMTP_USERNAME).
define('ADMIN_NOTIFY_EMAIL', 'bhuwansingh9818@gmail.com');

// ---------- DB CONNECTION (mysqli) ----------
function getDbConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]));
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
