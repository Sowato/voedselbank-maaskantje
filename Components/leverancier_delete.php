<?php
/*
Naam: Kieran Teunissen
Versie: 1.0
Datum: 04/06/2026
Beschrijving: component voor het verwerken van het verwijderen van een leverancier
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

    $stmt = $conn->prepare("DELETE FROM leverancier WHERE id = :id");
    $success = $stmt->execute([':id' => $id]);

    if ($success && $stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Leverancier succesvol verwijderd']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Leverancier niet gevonden']);
    }
} catch (PDOException $e) {
    error_log('Leverancier delete error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database fout']);
}
exit;
