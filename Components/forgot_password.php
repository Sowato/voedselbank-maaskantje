<?php
/*
Naam: Krishna Sardarsing
Versie: 1.0
Datum: 03/06/2026
Beschrijving: forgot password
*/
if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

require_once __DIR__ . '/db_conn.php';

$error      = '';
$reset_link = '';
$email      = trim($_POST['email'] ?? '');

if ($email === '') {
    $error = 'Voer je e-mailadres in.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Voer een geldig e-mailadres in.';
} else {
    $db   = (new Database())->getConnection();
    $stmt = $db->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $token   = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $db->prepare('UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?')
           ->execute([$token, $expires, $email]);

        // In productie: stuur dit via e-mail. Nu tonen we de link op het scherm.
        $reset_link = 'http://localhost/voedselbank-maaskantje/Pages/reset-password.php?token=' . $token;
    }
    // Always show success message to prevent email enumeration
    $sent = true;
}
