<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: Pages/dashboard.php');
    exit;
}

$error = '';
require_once __DIR__ . '/Components/login.php';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen – Voedselbank Maaskantje</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card">
        <div class="card-header">
            <div class="logo">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                </svg>
            </div>
            <h1>Welkom terug</h1>
            <p>Log in op je account</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">E-mailadres</label>
                <input type="email" id="email" name="email" placeholder="jij@voorbeeld.nl"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Wachtwoord</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn">Inloggen</button>
        </form>

        <div class="links">
            <a href="Pages/forgot-password.php">Wachtwoord vergeten?</a>
            <span class="separator">|</span>
            <a href="Pages/register.php">Account aanmaken</a>
        </div>
    </div>
</body>
</html>
