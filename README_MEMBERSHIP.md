# devendra kula vellalar sangam delhi – Membership Registration Module

This adds a fully working **Membership Registration Form** to your NGO website,
storing data in MySQL (phpMyAdmin) and emailing a confirmation via Gmail SMTP.

## Files added
- `membership.php` – the registration form page (matches your site's design)
- `membership.css` – styles for the form page
- `villages_data.php` – district-wise village list (Namakkal & Salem) used for the dropdown
- `submit_membership.php` – backend: validates data, saves to DB, sends emails, returns JSON
- `config.php` – **edit this file** with your DB and Gmail settings
- `database.sql` – SQL to create the database & table
- `PHPMailer/` – email-sending library (already included, no installation needed)

## Setup Steps (XAMPP / Local Server)

1. **Copy all files** into your `htdocs` (or web root) folder, e.g. `htdocs/ngo_website/`.

2. **Create the database:**
   - Open phpMyAdmin (`http://localhost/phpmyadmin`)
   - Click **Import** → choose `database.sql` → click **Go**
   - This creates the `sahara_foundation` database and `members` table.

3. **Edit `config.php`:**
   - Set `DB_USER` / `DB_PASS` to match your MySQL login (XAMPP default is `root` / empty password).
   - Set `SMTP_USERNAME` to your Gmail address.
   - Set `SMTP_PASSWORD` to a **Gmail App Password** (NOT your normal Gmail password):
     1. Go to your Google Account → Security → turn on **2-Step Verification**.
     2. Go to **App Passwords**, generate one for "Mail", and paste the 16-character code into `SMTP_PASSWORD`.
   - Set `ADMIN_NOTIFY_EMAIL` to the email where you (the NGO admin) want a copy of every registration.

4. **Visit the form:**
   - `http://localhost/ngo_website/membership.php`
   - Fill in Name, Email, Phone, District (Namakkal/Salem) → Village dropdown auto-updates → Submit.

5. **What happens on submit:**
   - A unique **Registration ID** is generated (format: `SF-2026-00001`).
   - The record is inserted into the `members` table in MySQL (visible in phpMyAdmin).
   - A confirmation email (with the Registration ID) is sent to the member's email, and a BCC copy goes to `ADMIN_NOTIFY_EMAIL`.
   - The form shows a success message with the Registration ID on screen.

## Notes
- State is fixed to **Delhi** by default, as requested.
- To add more districts/villages later, just edit the `$villages_data` array in `villages_data.php` — no other code changes needed.
- If deploying to live hosting (cPanel etc.), update `DB_HOST`, `DB_USER`, `DB_PASS` in `config.php` to your hosting provider's MySQL credentials, and re-import `database.sql` via your host's phpMyAdmin.
- If you don't want to use Gmail SMTP (e.g. your host blocks it), you can switch `submit_membership.php` to use PHP's built-in `mail()` function instead — let me know if you need that variant.
