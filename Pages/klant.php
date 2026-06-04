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
		"SELECT k.id, k.adres, k.plaats, k.email, k.telefoon, k.gezins_naam, k.volwassen, k.kind, k.baby,
                    GROUP_CONCAT(w.omschrijving SEPARATOR ', ') as wensen
             FROM klant k
             LEFT JOIN klant_wens kw ON k.id = kw.klant_id
             LEFT JOIN wens w ON kw.wens_id = w.id
             GROUP BY k.id
             ORDER BY k.id"
	);
	$stmt->execute();
	$klanten = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	$klanten = [];
	$error = 'Kon klanten niet laden.';
}
?>

	<?php if (!empty($error)): ?>
		<p style="color: red"><?= htmlspecialchars($error) ?></p>
	<?php endif; ?>

	
		<!-- Formulier voor het toevoegen van een nieuwe leverancier -->
		<form class="leverancier-form" action="../Components/klant_new.php" method="POST">
            <div class="form-group">
                <label>Gezinsnaam</label>
                <input type="text" name="gezins_naam" placeholder="Voer de gezinsnaam in" Value="testgezin" >
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
                <label>email</label>
                <input type="email" name="email" placeholder="Voer het e-mailadres in" Value="testcontact@example.com" >
            </div>
            <div class="form-group">
                <label>Telefoon</label>
                <input type="text" name="telefoon" placeholder="Voer het telefoonnummer in" Value="0000000000" >
            </div>
               <div class="form-group">
                <label>volwassen</label>
                <input type="number" name="volwassen" placeholder="Voer het aantal volwassenen in" Value="2" >  
            </div>
            <div class="form-group">
                <label>kind</label>
                <input type="number" name="kind" placeholder="Voer het aantal kinderen in" Value="1" >
            </div>
            <div class="form-group">
                <label>baby</label>
                <input type="number" name="baby" placeholder="Voer het aantal baby's in" Value="0" >
            </div>
            <div class="form-group">
                <label><h3>wensen</h3></label>
<label for="geen-varkensvlees">Geen varkensvlees</label><input type="checkbox" id="geen-varkensvlees" name="wensen[]" value="geen varkensvlees" >
                <label for="veganistisch">veganistisch</label><input type="checkbox" id="veganistisch" name="wensen[]" value="veganistisch" >
                <label for="vegetarisch">vegetarisch</label><input type="checkbox" id="vegetarisch" name="wensen[]" value="vegetarisch" >
                <label for="allergisch-voor">allergisch voor</label><input type="text" id="allergisch-voor" name="wensen[]" placeholder="allergisch voor" >
            </div>

                <label>we</label>
			<button type="submit" class="btn">Toevoegen</button>
		</form>

           <!-- Tabel voor het weergeven van leveranciers -->

		   <?php if (empty($klanten)): ?>
		<p>Er zijn geen klanten gevonden.</p>
	<?php else: ?>
		<table class="leveranciers-table">
            <thead><tr><th><h1>Klanten<h1></th></tr></thead>
			<thead>
				<tr>
					<th>Gezinsnaam</th>
					<th>Adres</th>
					<th>Plaats</th>
					<th>Email</th>
					<th>Telefoon</th>
					<th>Volwassen</th>
					<th>Kind</th>
					<th>Baby</th>
					<th>Wensen</th>
					<th>Acties</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($klanten as $l): ?>
					<tr>
						<td><?= htmlspecialchars($l['gezins_naam']) ?></td>
						<td><?= htmlspecialchars($l['adres'] ?? '') ?></td>
						<td><?= htmlspecialchars($l['plaats'] ?? '') ?></td>
						<td><?= htmlspecialchars($l['email']) ?></td>
						<td><?= htmlspecialchars($l['telefoon']) ?></td>
						<td><?= htmlspecialchars($l['volwassen']) ?></td>
						<td><?= htmlspecialchars($l['kind']) ?></td>
						<td><?= htmlspecialchars($l['baby']) ?></td>
						<td><?= htmlspecialchars($l['wensen'] ?? 'Geen wensen') ?></td>
						<td>
							<a href="klanten_edit.php?id=<?= $l['id'] ?>" class="btn btn-edit">Edit</a>
							<button type="button" class="btn btn-delete" onclick="deleteKlant(<?= $l['id'] ?>)">[X]</button>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>


</body>
</html>