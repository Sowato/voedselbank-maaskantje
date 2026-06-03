<?php
require_once __DIR__ . '/../Components/funcs.php';
requireLogin();
$role = $_SESSION['user_role'] ?? '';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maaskantje</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<?php include __DIR__ . '/../Components/navbar.php'; ?>

<div class="content">
    <div class="grid">

        <?php if ($role === 'admin' || $role === 'medewerker'): ?>
        <div class="card">
            <h3>Leverancier</h3>
            <p>Beheer van leveranciers: invoeren, verwerken, verwijderen en wijzigen.</p>
            <a href="leveranciers.php" class="btn">Ga naar leveranciers</a>
        </div>

        <div class="card">
            <h3>Voorraad beheer</h3>
            <p>Beheer van de magazijnvoorraad en product voorraad overzicht.</p>
            <a href="magazijnvoorraad.php" class="btn">Ga naar voorraad beheer</a>
        </div>
        <?php endif; ?>

        <?php if ($role === 'admin' || $role === 'vrijwilliger'): ?>
        <div class="card">
            <h3>Voedselpakketten</h3>
            <p>Samenstellen van een pakket voor een klant met aanwezige producten in het magazijn.</p>
            <a href="voedselpakketen.php" class="btn">Ga naar voedselpakketten</a>
        </div>
        <?php endif; ?>

        <?php if ($role === 'admin'): ?>
        <div class="card">
            <h3>Klanten</h3>
            <p>Beheer van klanten met hun specifieke wensen en gezinssamenstelling en overzicht afgenomen voedselpakketten.</p>
            <a href="klant.php" class="btn">Ga naar klanten</a>
        </div>

        <div class="card">
            <h3>Categorieën</h3>
            <p>Beheer de productcategorieën voor de voorraad. Aanmaken, bewerken en verwijderen van categorieën.</p>
            <a href="categorieen.php" class="btn">Categorieën beheren</a>
        </div>
        <?php endif; ?>

    </div>
</div>

</body>
</html>
