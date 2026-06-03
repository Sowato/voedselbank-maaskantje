<?php
// Processes the register form POST from Pages/register.php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

require_once __DIR__ . '/db_conn.php';

$error   = '';
$success = '';
$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm  = $_POST['confirm'] ?? '';

if ($name === '' || $email === '' || $password === '' || $confirm === '') {
    $error = 'Vul alle velden in.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Voer een geldig e-mailadres in.';
} elseif (strlen($password) < 6) {
    $error = 'Wachtwoord moet minimaal 6 tekens zijn.';
} elseif ($password !== $confirm) {
    $error = 'Wachtwoorden komen niet overeen.';
} else {
    $db   = (new Database())->getConnection();
    $stmt = $db->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        $error = 'Er bestaat al een account met dit e-mailadres.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $db->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)')
           ->execute([$name, $email, $hash]);
        $success = true;
    }
}
