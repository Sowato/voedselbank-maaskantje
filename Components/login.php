<?php
/*
Naam: Krishna Sardarsing
Versie: 1.0
Datum: 03/06/2026
Beschrijving: login
*/
if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

require_once __DIR__ . '/db_conn.php';

$error    = '';
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    $error = 'Vul alle velden in.';
} elseif ($email === 'admin@admin.nl' && $password === 'Admin1234!') {
    // Tijdelijk admin account
    $_SESSION['user_id']   = 0;
    $_SESSION['user_name'] = 'Admin';
    $_SESSION['user_email'] = $email;
    header('Location: Pages/dashboard.php');
    exit;
} else {
    $db   = (new Database())->getConnection();
    $stmt = $db->prepare('SELECT id, password FROM user WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        header('Location: Pages/dashboard.php');
        exit;
    } else {
        $error = 'Ongeldig e-mailadres of wachtwoord.';
    }
}
