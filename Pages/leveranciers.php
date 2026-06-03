<?php
require_once __DIR__ . '/../Components/db_conn.php';

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
<!doctype html>
<html lang="nl">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Leveranciers overzicht</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
	

	<?php if (!empty($error)): ?>
		<p style="color: red"><?= htmlspecialchars($error) ?></p>
	<?php endif; ?>

	<?php if (empty($leveranciers)): ?>
		<p>Er zijn geen leveranciers gevonden.</p>
	<?php else: ?>
        
           
		<table>
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
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>

</body>
</html>