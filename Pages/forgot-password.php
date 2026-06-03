<?php
session_start();

$error      = '';
$sent       = false;
$reset_link = '';
require_once __DIR__ . '/../Components/forgot_password.php';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wachtwoord vergeten – Voedselbank Maaskantje</title>
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
            <h1>Wachtwoord vergeten?</h1>
            <p>Voer je e-mailadres in om te resetten</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($sent): ?>
            <div class="alert alert-success">
                <?php if ($reset_link): ?>
                    Reset-link aangemaakt.<br>
                    <a href="<?= htmlspecialchars($reset_link) ?>" style="color:#1565c0;font-weight:600;">Klik hier om je wachtwoord te resetten</a><br>
                    <small style="color:#90caf9">(In productie wordt deze link per e-mail verstuurd.)</small>
                <?php else: ?>
                    Als dat e-mailadres bij ons bekend is, ontvang je een reset-link.
                <?php endif; ?>
            </div>
        <?php else: ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">E-mailadres</label>
                <input type="email" id="email" name="email" placeholder="jij@voorbeeld.nl"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            <button type="submit" class="btn">Reset-link versturen</button>
        </form>
        <?php endif; ?>

        <div class="links">
            <a href="../index.php">&#8592; Terug naar inloggen</a>
        </div>
    </div>
</body>
</html>
