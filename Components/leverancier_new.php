<?php
/*
Naam: Kieran Teunissen
Versie: 1.0
Datum: 03/06/2026
Beschrijving: component voor het verwerken van het aanmaken van een nieuwe leverancier
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return;
}

require_once __DIR__ . '/db_conn.php';

$company = trim($_POST['company'] ?? '');
$adres = trim($_POST['adres'] ?? '');
$plaats = trim($_POST['plaats'] ?? '');
$contact_persoon = trim($_POST['contact_persoon'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefoon = trim($_POST['telefoon'] ?? '');
$volgende_levering_datum = trim($_POST['volgende_levering_datum'] ?? '');

if (empty($company) || empty($adres) || empty($plaats) || empty($contact_persoon) || empty($email) || empty($telefoon)) {
    echo "<script>alert('Vul alle verplichte velden in.'); window.location.href='../Pages/leveranciers.php';</script>";
    exit;
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare(
        "INSERT INTO leverancier (company, adres, plaats, contact_persoon, email, telefoon, volgende_levering_datum)
         VALUES (:company, :adres, :plaats, :contact_persoon, :email, :telefoon, :volgende_levering_datum)"
    );

    $success = $stmt->execute([
        ':company' => $company,
        ':adres' => $adres,
        ':plaats' => $plaats,
        ':contact_persoon' => $contact_persoon,
        ':email' => $email,
        ':telefoon' => $telefoon,
        ':volgende_levering_datum' => $volgende_levering_datum ?: null,
    ]);

    if ($success) {
        echo "<script> window.location.href='../Pages/leveranciers.php';</script>";
        exit;
    }
} catch (PDOException $e) {
    error_log('Leverancier insert error: ' . $e->getMessage());
}

echo "<script>alert('Er is iets misgegaan bij het toevoegen van de leverancier.'); window.location.href='../Pages/leveranciers.php';</script>";
exit;
