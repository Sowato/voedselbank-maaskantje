<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

require_once __DIR__ . '/../Components/db_conn.php';

$error   = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if ($email === '' || $password === '' || $confirm === '') {
        $error = 'Vul alle velden in.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Voer een geldig e-mailadres in.';
    } elseif (strlen($password) < 6) {
        $error = 'Wachtwoord moet minimaal 6 tekens zijn.';
    } elseif ($password !== $confirm) {
        $error = 'Wachtwoorden komen niet overeen.';
    } else {
        $db   = (new Database())->getConnection();
        $stmt = $db->prepare('SELECT id FROM user WHERE email = ?');
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = 'Dit e-mailadres is al in gebruik.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $db->prepare('INSERT INTO user (email, roles, password) VALUES (?, ?, ?)')
               ->execute([$email, 'vrijwilliger', $hash]);
            $success = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren – Voedselbank Maaskantje</title>
    <link rel="stylesheet" href="../css/style.css">
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
            <p>Maak een nieuw account aan</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                Account aangemaakt! <a href="../index.php">Inloggen</a>.
            </div>
        <?php else: ?>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="email">E-mailadres</label>
                    <input type="email" id="email" name="email"
                           placeholder="jij@voorbeeld.nl"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Wachtwoord</label>
                    <input type="password" id="password" name="password"
                           placeholder="Min. 6 tekens" required>
                </div>
                <div class="form-group">
                    <label for="confirm">Wachtwoord bevestigen</label>
                    <input type="password" id="confirm" name="confirm"
                           placeholder="Herhaal wachtwoord" required>
                </div>
                <button type="submit" class="btn">Registreren</button>
            </form>

        <?php endif; ?>

        <div class="links">
            <a href="../index.php">&#8592; Terug naar inloggen</a>
        </div>
    </div>
</body>
</html>
