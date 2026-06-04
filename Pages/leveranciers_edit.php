<?php
require_once __DIR__ . '/../Components/db_conn.php';
require_once __DIR__ . '/../Components/navbar.php';
require_once __DIR__ . '/../Components/funcs.php';
requireLogin();
requireRole(['admin']);
$role = $_SESSION['user_role'] ?? '';
?>
<!doctype html>
<html lang="nl">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Leveranciers overzicht</title>
     <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/leveringen.css">
	<script src="../Components/funcs.js"></script>
</head>
<body class="leveranciers-page">
	<?php

$id = $_GET['id'] ?? '';

if (empty($id)) {
    echo "<script>alert('Ongeldig leverancier ID'); window.location.href='leveranciers.php';</script>";
    exit;
}

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    $stmt = $conn->prepare("SELECT * FROM leverancier WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $leverancier = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$leverancier) {
        echo "<script>alert('Leverancier niet gevonden'); window.location.href='leveranciers.php';</script>";
        exit;
    }
} catch (PDOException $e) {
    echo "<script>alert('Fout bij laden leverancier'); window.location.href='leveranciers.php';</script>";
    exit;
}
?>
<!doctype html>
<html lang="nl">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Leverancier bewerken</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body class="leveranciers-edit-page">
	<h1>Leverancier bewerken</h1>

	<form class="leverancier-form" action="../Components/leverancier_edit.php" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($leverancier['id']) ?>">
        
        <div class="form-group">
            <label>Bedrijfsnaam</label>
            <input type="text" name="company" placeholder="Voer de bedrijfsnaam in" value="<?= htmlspecialchars($leverancier['company']) ?>" required>
        </div>
        <div class="form-group">
            <label>Adres</label>
            <input type="text" name="adres" placeholder="Voer het adres in" value="<?= htmlspecialchars($leverancier['adres'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Plaats</label>
            <input type="text" name="plaats" placeholder="Voer de plaats in" value="<?= htmlspecialchars($leverancier['plaats'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Contactpersoon</label>
            <input type="text" name="contact_persoon" placeholder="Voer de contactpersoon in" value="<?= htmlspecialchars($leverancier['contact_persoon']) ?>" required>
        </div>
        <div class="form-group">
            <label>Telefoon</label>
            <input type="text" name="telefoon" placeholder="Voer het telefoonnummer in" value="<?= htmlspecialchars($leverancier['telefoon']) ?>" required>
        </div>
        <div class="form-group">
            <label>E-mailadres</label>
            <input type="email" name="email" placeholder="Voer het e-mailadres in" value="<?= htmlspecialchars($leverancier['email']) ?>" required>  
        </div>
        <div class="form-group">
            <label>Volgende leveringsdatum</label>
            <input type="datetime-local" name="volgende_levering_datum" value="<?= isset($leverancier['volgende_levering_datum']) && !empty($leverancier['volgende_levering_datum']) ? date('Y-m-d\TH:i', strtotime($leverancier['volgende_levering_datum'])) : '' ?>">
		</div>

		<button type="submit" class="btn">Opslaan</button>
		<a href="leveranciers.php" class="btn btn-secondary">Annuleren</a>
	</form>

</body>
</html>
