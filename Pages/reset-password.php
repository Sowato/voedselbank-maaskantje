<?php
session_start();
require_once __DIR__ . '/../Components/db_conn.php';

$error   = '';
$success = false;
$token   = trim($_GET['token'] ?? '');

if ($token === '') {
    header('Location: forgot-password.php');
    exit;
}

$db   = (new Database())->getConnection();
$stmt = $db->prepare('SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()');
$stmt->execute([$token]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $error = 'Deze reset-link is ongeldig of verlopen. <a href="forgot-password.php">Vraag een nieuwe aan</a>.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if ($password === '' || $confirm === '') {
        $error = 'Vul alle velden in.';
    } elseif (strlen($password) < 6) {
        $error = 'Wachtwoord moet minimaal 6 tekens zijn.';
    } elseif ($password !== $confirm) {
        $error = 'Wachtwoorden komen niet overeen.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $db->prepare('UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?')
           ->execute([$hash, $user['id']]);
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuw wachtwoord – Voedselbank Maaskantje</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="card">
        <div class="card-header">
            <div class="logo">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 8h-1V6c0-2.8-2.2-5-5-5S7 3.2 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.7 1.4-3.1 3.1-3.1 1.7 0 3.1 1.4 3.1 3.1v2z"/>
                </svg>
            </div>
            <h1>Nieuw wachtwoord</h1>
            <p>Kies een nieuw wachtwoord</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                Wachtwoord gewijzigd! <a href="../index.php">Inloggen</a>.
            </div>
        <?php elseif ($user): ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="password">Nieuw wachtwoord</label>
                <input type="password" id="password" name="password" placeholder="Min. 6 tekens" required>
            </div>
            <div class="form-group">
                <label for="confirm">Wachtwoord bevestigen</label>
                <input type="password" id="confirm" name="confirm" placeholder="Herhaal wachtwoord" required>
            </div>
            <button type="submit" class="btn">Wachtwoord opslaan</button>
        </form>
        <?php endif; ?>

        <div class="links">
            <a href="../index.php">&#8592; Terug naar inloggen</a>
        </div>
    </div>
</body>
</html>
