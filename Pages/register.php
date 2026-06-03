<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error   = '';
$success = false;
require_once __DIR__ . '/../Components/register.php';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren – Voedselbank Maaskantje</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="card">
        <div class="card-header">
            <div class="logo">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 12c2.7 0 4.8-2.1 4.8-4.8S17.7 2.4 15 2.4s-4.8 2.1-4.8 4.8S12.3 12 15 12zm-9 2.4v1.2c0 .7.5 1.2 1.2 1.2h1.2V15c.9-.5 1.8-.8 2.6-1H6zm9 0c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8zM6 12V9.6H3.6V7.2H6V4.8h2.4v2.4h2.4v2.4H8.4V12H6z"/>
                </svg>
            </div>
            <h1>Account aanmaken</h1>
            <p>Vul je gegevens in</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                Account aangemaakt! Je kunt nu <a href="../index.php">inloggen</a>.
            </div>
        <?php else: ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Volledige naam</label>
                <input type="text" id="name" name="name" placeholder="Jan Jansen"
                    value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="email">E-mailadres</label>
                <input type="email" id="email" name="email" placeholder="jij@voorbeeld.nl"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Wachtwoord</label>
                <input type="password" id="password" name="password" placeholder="Min. 6 tekens" required>
            </div>
            <div class="form-group">
                <label for="confirm">Wachtwoord bevestigen</label>
                <input type="password" id="confirm" name="confirm" placeholder="Herhaal wachtwoord" required>
            </div>
            <button type="submit" class="btn">Account aanmaken</button>
        </form>
        <?php endif; ?>

        <div class="links">
            Al een account? <a href="../index.php">Inloggen</a>
        </div>
    </div>
</body>
</html>
