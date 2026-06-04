<?php
/*
Naam: Kieran Teunissen
Versie: 1.0
Datum: 04/06/2026
Beschrijving: component voor het verwerken van het verwijderen van een klant
*/

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Alleen POST toegestaan']);
    exit;
}

require_once __DIR__ . '/db_conn.php';

$id = trim($_POST['id'] ?? '');

if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'Ongeldig ID']);
    exit;
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Start transaction
    $conn->beginTransaction();

    // Eerst alle wensen van de klant verwijderen uit klant_wens
    $stmtWensen = $conn->prepare("DELETE FROM klant_wens WHERE klant_id = :id");
    $stmtWensen->execute([':id' => $id]);

    // Dan de klant zelf verwijderen
    $stmt = $conn->prepare("DELETE FROM klant WHERE id = :id");
    $success = $stmt->execute([':id' => $id]);

    if ($success && $stmt->rowCount() > 0) {
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Klant succesvol verwijderd']);
    } else {
        $conn->rollBack();
        echo json_encode(['success' => false, 'message' => 'Klant niet gevonden']);
    }
} catch (PDOException $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    error_log('Klant delete error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database fout']);
}
exit;
