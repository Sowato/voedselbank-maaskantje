<?php
require_once __DIR__ . '/../Components/db_conn.php';
require_once __DIR__ . '/../Components/funcs.php';
requireLogin();
requireRole(['admin']);
$role = $_SESSION['user_role'] ?? '';
require_once __DIR__ . '/../Components/navbar.php';
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

$db = new Database();
$conn = $db->getConnection();

try {
	$stmt = $conn->prepare(
		"SELECT id, company, adres, plaats, contact_persoon, email, telefoon, volgende_levering_datum FROM leverancier ORDER BY id"
	);
	$stmt->execute();
	$leveranciers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	$leveranciers = [];
	$error = 'Kon leveranciers niet laden.';
}
?>

	<?php if (!empty($error)): ?>
		<p style="color: red"><?= htmlspecialchars($error) ?></p>
	<?php endif; ?>

	
		<!-- Formulier voor het toevoegen van een nieuwe leverancier -->
		<form class="leverancier-form" action="../Components/leverancier_new.php" method="POST">
            <div class="form-group">
                <label>Bedrijfsnaam</label>
                <input type="company" name="company" placeholder="Voer de bedrijfsnaam in" Value="testbedrijf" >
            </div>
            <div class="form-group">
                <label>Adres</label>
                <input type="text" name="adres" placeholder="Voer het adres in" Value="teststraat 1" >
            </div>
            <div class="form-group">
                <label>Plaats</label>
                <input type="text" name="plaats" placeholder="Voer de plaats in" Value="testplaats" >
            </div>
            <div class="form-group">
                <label>Contactpersoon</label>
                <input type="text" name="contact_persoon" placeholder="Voer de contactpersoon in" Value="testcontact" >
            </div>
            <div class="form-group">
                <label>Telefoon</label>
                <input type="text" name="telefoon" placeholder="Voer het telefoonnummer in" Value="0000000000" >
            </div>
               <div class="form-group">
                <label>E-mailadres</label>
                <input type="email" name="email" placeholder="Voer het e-mailadres in" Value="testemail@example.com" >  
            </div>
            <div class="form-group">
                <label>Volgende leveringsdatum</label>
                <input type="datetime-local" name="volgende_levering_datum" placeholder="Voer de volgende leveringsdatum in" required>

			</div>

			<button type="submit" class="btn">Toevoegen</button>
		</form>

           <!-- Tabel voor het weergeven van leveranciers -->

		   <?php if (empty($leveranciers)): ?>
		<p>Er zijn geen leveranciers gevonden.</p>
	<?php else: ?>
		<table class="leveranciers-table">
            <thead><tr><th><h1>Leveranciers<h1></th></tr></thead>
			<thead>
				<tr>
					<th>Company</th>
					<th>Adres</th>
					<th>Plaats</th>
					<th>Contact persoon</th>
					<th>Email</th>
					<th>Telefoon</th>
					<th>Volgende levering datum</th>
					<th>Acties</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($leveranciers as $l): ?>
					<tr>
						<td><?= htmlspecialchars($l['company']) ?></td>
						<td><?= htmlspecialchars($l['adres'] ?? '') ?></td>
						<td><?= htmlspecialchars($l['plaats'] ?? '') ?></td>
						<td><?= htmlspecialchars($l['contact_persoon']) ?></td>
						<td><?= htmlspecialchars($l['email']) ?></td>
						<td><?= htmlspecialchars($l['telefoon']) ?></td>
						<td>
							<?php if (!empty($l['volgende_levering_datum'])): ?>
								<?= htmlspecialchars(date('Y-m-d H:i', strtotime($l['volgende_levering_datum']))) ?>
							<?php endif; ?>
						</td>
						<td>
							<a href="leveranciers_edit.php?id=<?= $l['id'] ?>" class="btn btn-edit">Edit</a>
							<button type="button" class="btn btn-delete" onclick="deleteLeverancier(<?= $l['id'] ?>)">[X]</button>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>


</body>
</html>