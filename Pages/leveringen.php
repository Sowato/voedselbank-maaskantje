<?php
require_once __DIR__ . '/../Components/funcs.php';
requireLogin();
require_once __DIR__ . '/../Components/db_conn.php';

$db = (new Database())->getConnection();

// Als de sessie user_id 0 is (tijdelijk admin), gebruik dan de eerste echte user uit de DB
$session_user_id = $_SESSION['user_id'];
if ($session_user_id === 0) {
    $row = $db->query('SELECT id FROM user LIMIT 1')->fetch(PDO::FETCH_ASSOC);
    $session_user_id = $row ? (int)$row['id'] : 1;
}

$error   = '';
$success = '';

// --- DELETE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $id = (int)$_POST['id'];
    $db->prepare('DELETE FROM levering WHERE id = ?')->execute([$id]);
    $success = 'Levering verwijderd.';
}

// --- UPDATE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
    $id            = (int)$_POST['id'];
    $product_id    = (int)$_POST['product_id'];
    $leverancier_id = (int)$_POST['leverancier_id'];
    $datumtijd     = $_POST['datumtijd'];
    $aantal        = (float)$_POST['aantal'];
    $houdbaar_tot  = $_POST['houdbaar_tot'];

    $stmt = $db->prepare('UPDATE levering SET product_id=?, leverancier_id=?, datumtijd=?, aantal=?, houdbaar_tot=? WHERE id=?');
    $stmt->execute([$product_id, $leverancier_id, $datumtijd, $aantal, $houdbaar_tot, $id]);
    $success = 'Levering bijgewerkt.';
}

// --- CREATE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
    $product_id     = (int)$_POST['product_id'];
    $leverancier_id = (int)$_POST['leverancier_id'];
    $datumtijd      = $_POST['datumtijd'];
    $aantal         = (float)$_POST['aantal'];
    $houdbaar_tot   = $_POST['houdbaar_tot'];

    $stmt = $db->prepare('INSERT INTO levering (user_id, product_id, leverancier_id, datumtijd, aantal, houdbaar_tot) VALUES (?,?,?,?,?,?)');
    $stmt->execute([$session_user_id, $product_id, $leverancier_id, $datumtijd, $aantal, $houdbaar_tot]);
    $success = 'Levering toegevoegd.';
}

// Edit-modus
$edit_row = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $stmt = $db->prepare('SELECT * FROM levering WHERE id = ?');
    $stmt->execute([(int)$_GET['id']]);
    $edit_row = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Haal alle leveringen op
$leveringen = $db->query('
    SELECT l.id, p.omschrijving AS product, lv.company AS leverancier,
           l.datumtijd, l.aantal, l.houdbaar_tot
    FROM levering l
    JOIN product p ON p.id = l.product_id
    JOIN leverancier lv ON lv.id = l.leverancier_id
    ORDER BY l.datumtijd DESC
')->fetchAll(PDO::FETCH_ASSOC);

// Dropdowndata
$producten    = $db->query('SELECT id, omschrijving FROM product ORDER BY omschrijving')->fetchAll(PDO::FETCH_ASSOC);
$leveranciers = $db->query('SELECT id, company FROM leverancier ORDER BY company')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leveringen – Maaskantje</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/leveringen.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">Maaskantje</div>
    <ul class="menu">
        <li><a href="dashboard.php">Home</a></li>
        <li><a href="leveringen.php">Leveringen</a></li>
        <li><a href="uitgifte.php">Uitgifte</a></li>
        <li>
            <a href="#">Beheer ▼</a>
            <ul class="dropdown">
                <li><a href="leveranciers.php">Leveranciers</a></li>
                <li class="has-submenu">
                    <a href="#">Voorraad</a>
                    <ul class="submenu">
                        <li><a href="magazijnvoorraad.php">Magazijn voorraad</a></li>
                        <li><a href="#">Product voorraad overzicht</a></li>
                    </ul>
                </li>
                <li><a href="voedselpakketen.php">Voedselpakketten</a></li>
                <li class="has-submenu">
                    <a href="#">Klanten</a>
                    <ul class="submenu">
                        <li><a href="klant.php">Beheer klanten</a></li>
                        <li><a href="klanten_overzicht.php">Pakketten overzicht</a></li>
                    </ul>
                </li>
            </ul>
        </li>
        <li><a href="#">Admin</a></li>
    </ul>
    <div class="navbar-user">
        <a href="../Components/logout.php" class="logout-btn">Uitloggen</a>
    </div>
</nav>

<div class="content">

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Formulier: toevoegen of bewerken -->
    <?php if ($edit_row): ?>
        <!-- EDIT FORMULIER -->
        <div class="form-card">
            <h3>Levering bewerken</h3>
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?= (int)$edit_row['id'] ?>">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Product</label>
                        <select name="product_id" required>
                            <?php foreach ($producten as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= $p['id'] == $edit_row['product_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['omschrijving']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Leverancier</label>
                        <select name="leverancier_id" required>
                            <?php foreach ($leveranciers as $lv): ?>
                                <option value="<?= $lv['id'] ?>" <?= $lv['id'] == $edit_row['leverancier_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($lv['company']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Datum &amp; tijd</label>
                        <input type="datetime-local" name="datumtijd"
                               value="<?= date('Y-m-d\TH:i', strtotime($edit_row['datumtijd'])) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Aantal</label>
                        <input type="number" step="0.01" min="0" name="aantal"
                               value="<?= htmlspecialchars($edit_row['aantal']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Houdbaar tot</label>
                        <input type="datetime-local" name="houdbaar_tot"
                               value="<?= date('Y-m-d\TH:i', strtotime($edit_row['houdbaar_tot'])) ?>" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-save">Opslaan</button>
                    <a href="leveringen.php" class="btn-cancel">Annuleren</a>
                </div>
            </form>
        </div>

    <?php else: ?>
        <!-- TOEVOEGEN FORMULIER -->
        <div class="form-card">
            <h3>Nieuwe levering toevoegen</h3>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Product</label>
                        <select name="product_id" required>
                            <option value="">— Kies product —</option>
                            <?php foreach ($producten as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['omschrijving']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Leverancier</label>
                        <select name="leverancier_id" required>
                            <option value="">— Kies leverancier —</option>
                            <?php foreach ($leveranciers as $lv): ?>
                                <option value="<?= $lv['id'] ?>"><?= htmlspecialchars($lv['company']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Datum &amp; tijd</label>
                        <input type="datetime-local" name="datumtijd" required>
                    </div>
                    <div class="form-group">
                        <label>Aantal</label>
                        <input type="number" step="0.01" min="0" name="aantal" placeholder="0" required>
                    </div>
                    <div class="form-group">
                        <label>Houdbaar tot</label>
                        <input type="datetime-local" name="houdbaar_tot" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-add">Toevoegen</button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- TABEL -->
    <div class="page-header">
        <h2>Leveringen overzicht</h2>
    </div>

    <?php if (empty($leveringen)): ?>
        <p style="color:#888; font-size:14px;">Nog geen leveringen gevonden.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Leverancier</th>
                    <th>Datum &amp; tijd</th>
                    <th>Aantal</th>
                    <th>Houdbaar tot</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leveringen as $r): ?>
                    <tr>
                        <td><?= (int)$r['id'] ?></td>
                        <td><?= htmlspecialchars($r['product']) ?></td>
                        <td><?= htmlspecialchars($r['leverancier']) ?></td>
                        <td><?= htmlspecialchars($r['datumtijd']) ?></td>
                        <td><?= htmlspecialchars($r['aantal']) ?></td>
                        <td><?= htmlspecialchars($r['houdbaar_tot']) ?></td>
                        <td style="display:flex; gap:6px;">
                            <a href="?action=edit&id=<?= (int)$r['id'] ?>" class="btn-edit">Bewerken</a>
                            <form method="POST" onsubmit="return confirm('Weet je zeker dat je deze levering wilt verwijderen?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                <button type="submit" class="btn-delete">Verwijderen</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>

</body>
</html>
