<?php
/*
Naam: Kieran Teunissen
Versie: 1.0
Datum: 03/06/2026
Beschrijving: Admin pagina voor het aanmaken van nieuwe gebruikers in het CRUD systeem
*/
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error   = '';
$success = false;
require_once __DIR__ . '/../Components/gebruiker_new.php';
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
            <h1>gebruiker account aanmaken</h1>
            <p>Vul gegevens in</p>
        </div>

    
        <form action="../Components/gebruiker_new.php" method="POST">
            <div class="form-group">
                <label>E-mailadres</label>
                <input type="email" name="email" placeholder="Voer je e-mailadres in" required>
            </div>
            <div class="form-group">
                <label>Rol</label>
                <input type="checkbox" name="roles[]" value="vrijwilliger"> vrijwilliger<br>
                <input type="checkbox" name="roles[]" value="magazijnmedewerker"> magazijnmedewerker<br>
                <input type="checkbox" name="roles[]" value="directie"> directie
            </div>
            <div class="form-group">
                <label>Wachtwoord</label>
                <input type="password" name="password" placeholder="Voer je wachtwoord in" required>
            </div>
            <button type="submit" class="btn">Toevoegen</button>
        </form>

    </div>
</body>
</html>
