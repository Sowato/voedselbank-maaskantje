<?php
/*
Naam: Kieran Teunissen
Versie: 1.0
Datum: 03/06/2026
Beschrijving: component voor het verwerken van het aanmaken van een nieuwe klant
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return;
}

require_once __DIR__ . '/db_conn.php';

$gezins_naam = trim($_POST['gezins_naam'] ?? '');
$adres = trim($_POST['adres'] ?? '');
$plaats = trim($_POST['plaats'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefoon = trim($_POST['telefoon'] ?? '');
$volwassen = trim($_POST['volwassen'] ?? '');
$kind = trim($_POST['kind'] ?? '');
$baby = trim($_POST['baby'] ?? '');
//split wensen op in een array en trim elke wens
$wensen = array_map('trim', explode(',', $_POST['wensen'] ?? ''));


if (empty($gezins_naam) || empty($adres) || empty($plaats) || empty($email) || empty($telefoon)) {
    echo "<script>alert('Vul alle verplichte velden in.'); window.location.href='../Pages/klant.php';</script>";
    exit;
}

try {
    $db = new Database();
    $conn = $db->getConnection();


    $stmt = $conn->prepare(
        "INSERT INTO klant (gezins_naam, adres, plaats, email, telefoon, volwassen, kind, baby)
         VALUES (:gezins_naam, :adres, :plaats, :email, :telefoon, :volwassen, :kind, :baby)"
    );

    $success = $stmt->execute([
        ':gezins_naam' => $gezins_naam,
        ':adres' => $adres,
        ':plaats' => $plaats,
        ':email' => $email,
        ':telefoon' => $telefoon,
        ':volwassen' => $volwassen,
        ':kind' => $kind,
        ':baby' => $baby,
    ]);

    if (!$success) {
        throw new Exception('Klant kon niet worden toegevoegd');
    }

    //Haal klant_id op
    $klant_id = $conn->lastInsertId();

    //Voor elke wens INSERT in wens tabel en koppel aan klant
    foreach ($wensen as $wens_omschrijving) {
        if (!empty($wens_omschrijving)) {

            // INSERT wens in wens tabel
            $stmtWens = $conn->prepare(
                "INSERT INTO wens (omschrijving) VALUES (:omschrijving)"
            );
            $stmtWens->execute([':omschrijving' => $wens_omschrijving]);

            $wens_id = $conn->lastInsertId();

            // INSERT in klant_wens tabel
            $stmtKoppel = $conn->prepare(
                "INSERT INTO klant_wens (klant_id, wens_id) VALUES (:klant_id, :wens_id)"
            );
            $stmtKoppel->execute([
                ':klant_id' => $klant_id,
                ':wens_id' => $wens_id,
            ]);
        }
    }

    echo "<script> window.location.href='../Pages/klant.php';</script>";
    exit;
} catch (Exception $e) {
    error_log('Klant insert error: ' . $e->getMessage());
}

echo "<script>alert('Er is iets misgegaan bij het toevoegen van de klant.'); window.location.href='../Pages/klant.php';</script>";exit;
