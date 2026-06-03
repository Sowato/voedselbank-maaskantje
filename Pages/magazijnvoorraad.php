<?php
require_once __DIR__ . '/../Components/funcs.php';
requireRole(['admin', 'medewerker']);
require_once __DIR__ . '/../Components/db_conn.php';

$db = (new Database())->getConnection();

// Verwijder oude gecombineerde categorieën
$oud = ['AGF', 'KV', 'ZPE', 'BB', 'FSKK', 'PRW', 'SSKO', 'SKCC', 'BVH'];
$placeholders = implode(',', array_fill(0, count($oud), '?'));
$db->prepare("UPDATE product SET category_id = NULL WHERE category_id IN (SELECT id FROM category WHERE code IN ($placeholders))")->execute($oud);
$db->prepare("DELETE FROM category WHERE code IN ($placeholders)")->execute($oud);

// Seed standaard categorieën — voeg toe als de code nog niet bestaat
$standaard = [
    ['AARD',  'Aardappelen'],
    ['GROEN', 'Groenten'],
    ['FRUIT', 'Fruit'],
    ['KAAS',  'Kaas'],
    ['VLEES', 'Vleeswaren'],
    ['ZUIV',  'Zuivel'],
    ['PLANT', 'Plantaardig'],
    ['EIER',  'Eieren'],
    ['BAK',   'Bakkerij'],
    ['BANK',  'Banket'],
    ['FRIS',  'Frisdrank'],
    ['SAP',   'Sappen'],
    ['KOF',   'Koffie en thee'],
    ['PASTA', 'Pasta'],
    ['RIJST', 'Rijst'],
    ['WORLD', 'Wereldkeuken'],
    ['SOEP',  'Soepen'],
    ['SAUS',  'Sauzen'],
    ['KRUID', 'Kruiden'],
    ['OLIE',  'Olie'],
    ['SNOEP', 'Snoep'],
    ['KOEK',  'Koek'],
    ['CHIPS', 'Chips'],
    ['CHOC',  'Chocolade'],
    ['BABY',  'Baby'],
    ['VERZ',  'Verzorging'],
    ['HYG',   'Hygiëne'],
];
$check = $db->prepare('SELECT COUNT(*) FROM category WHERE code = ?');
$ins   = $db->prepare('INSERT INTO category (code, omschrijving) VALUES (?, ?)');
foreach ($standaard as [$code, $omschrijving]) {
    $check->execute([$code]);
    if ((int)$check->fetchColumn() === 0) {
        $ins->execute([$code, $omschrijving]);
    }
}

$error   = '';
$success = '';

// --- DELETE product ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $db->prepare('DELETE FROM product WHERE id = ?')->execute([(int)$_POST['id']]);
    $success = 'Product verwijderd.';
}

// --- UPDATE product ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
    $db->prepare('UPDATE product SET category_id=?, streepjescode=?, omschrijving=?, aantal=? WHERE id=?')
       ->execute([
           (int)$_POST['category_id'],
           trim($_POST['streepjescode']),
           trim($_POST['omschrijving']),
           (float)$_POST['aantal'],
           (int)$_POST['id'],
       ]);
    $success = 'Product bijgewerkt.';
}

// --- CREATE product ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
    $db->prepare('INSERT INTO product (category_id, streepjescode, omschrijving, aantal) VALUES (?,?,?,?)')
       ->execute([
           (int)$_POST['category_id'],
           trim($_POST['streepjescode']),
           trim($_POST['omschrijving']),
           (float)$_POST['aantal'],
       ]);
    $success = 'Product toegevoegd.';
}

// Actieve categorie filter
$active_cat = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;

// Edit-modus
$edit_row = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $stmt = $db->prepare('SELECT * FROM product WHERE id = ?');
    $stmt->execute([(int)$_GET['id']]);
    $edit_row = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Categorieën
$categorieen = $db->query('SELECT * FROM category ORDER BY omschrijving')->fetchAll(PDO::FETCH_ASSOC);

// Producten (gefilterd op categorie indien gekozen)
if ($active_cat > 0) {
    $stmt = $db->prepare('
        SELECT p.*, c.omschrijving AS categorie
        FROM product p
        LEFT JOIN category c ON c.id = p.category_id
        WHERE p.category_id = ?
        ORDER BY p.omschrijving
    ');
    $stmt->execute([$active_cat]);
} else {
    $stmt = $db->query('
        SELECT p.*, c.omschrijving AS categorie
        FROM product p
        LEFT JOIN category c ON c.id = p.category_id
        ORDER BY c.omschrijving, p.omschrijving
    ');
}
$producten = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Bouw redirect-URL terug inclusief cat-filter
$back_url = 'magazijnvoorraad.php' . ($active_cat ? '?cat=' . $active_cat : '');
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voorraad – Maaskantje</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/leveringen.css">
    <style>
        .cat-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 20px;
        }
        .cat-tab {
            padding: 6px 14px;
            border-radius: 20px;
            border: 1.5px solid #0d6efd;
            color: #0d6efd;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            background: #fff;
            transition: background .15s, color .15s;
        }
        .cat-tab:hover,
        .cat-tab.active {
            background: #0d6efd;
            color: #fff;
        }
        .stock-low  { color: #dc3545; font-weight: 700; }
        .stock-ok   { color: #198754; font-weight: 700; }
    </style>
</head>
<body>

<?php include __DIR__ . '/../Components/navbar.php'; ?>

<div class="content">

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Formulier -->
    <?php if ($edit_row): ?>
        <div class="form-card">
            <h3>Product bewerken</h3>
            <form method="POST" action="<?= htmlspecialchars($back_url) ?>">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id"     value="<?= (int)$edit_row['id'] ?>">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Categorie</label>
                        <select name="category_id" required>
                            <?php foreach ($categorieen as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= $c['id'] == $edit_row['category_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['omschrijving']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Omschrijving</label>
                        <input type="text" name="omschrijving" value="<?= htmlspecialchars($edit_row['omschrijving']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Streepjescode</label>
                        <input type="text" name="streepjescode" value="<?= htmlspecialchars($edit_row['streepjescode']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Aantal</label>
                        <input type="number" step="0.01" min="0" name="aantal" value="<?= htmlspecialchars($edit_row['aantal']) ?>" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-save">Opslaan</button>
                    <a href="<?= htmlspecialchars($back_url) ?>" class="btn-cancel">Annuleren</a>
                </div>
            </form>
        </div>
    <?php else: ?>
        <div class="form-card">
            <h3>Product toevoegen</h3>
            <form method="POST" action="<?= htmlspecialchars($back_url) ?>">
                <input type="hidden" name="action" value="create">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Categorie</label>
                        <select name="category_id" required>
                            <option value="">— Kies categorie —</option>
                            <?php foreach ($categorieen as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= $c['id'] == $active_cat ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['omschrijving']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Omschrijving</label>
                        <input type="text" name="omschrijving" placeholder="bijv. Appels" required>
                    </div>
                    <div class="form-group">
                        <label>Streepjescode</label>
                        <input type="text" name="streepjescode" placeholder="bijv. 8712345678901" required>
                    </div>
                    <div class="form-group">
                        <label>Aantal</label>
                        <input type="number" step="0.01" min="0" name="aantal" placeholder="0" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-add">Toevoegen</button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- Categorie tabs -->
    <div class="cat-tabs">
        <a href="magazijnvoorraad.php" class="cat-tab <?= $active_cat === 0 ? 'active' : '' ?>">Alle categorieën</a>
        <?php foreach ($categorieen as $c): ?>
            <a href="?cat=<?= $c['id'] ?>" class="cat-tab <?= $active_cat === (int)$c['id'] ? 'active' : '' ?>">
                <?= htmlspecialchars($c['omschrijving']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Tabel -->
    <div class="page-header">
        <h2>
            <?php
            if ($active_cat > 0) {
                foreach ($categorieen as $c) {
                    if ((int)$c['id'] === $active_cat) echo htmlspecialchars($c['omschrijving']);
                }
            } else {
                echo 'Alle producten';
            }
            ?>
        </h2>
    </div>

    <?php if (empty($producten)): ?>
        <p style="color:#888; font-size:14px;">Geen producten gevonden in deze categorie.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Categorie</th>
                    <th>Omschrijving</th>
                    <th>Streepjescode</th>
                    <th>Aantal</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($producten as $r): ?>
                    <tr>
                        <td><?= (int)$r['id'] ?></td>
                        <td><?= htmlspecialchars($r['categorie'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($r['omschrijving']) ?></td>
                        <td><?= htmlspecialchars($r['streepjescode']) ?></td>
                        <td class="<?= $r['aantal'] <= 5 ? 'stock-low' : 'stock-ok' ?>">
                            <?= htmlspecialchars($r['aantal']) ?>
                        </td>
                        <td style="display:flex; gap:6px;">
                            <a href="?action=edit&id=<?= (int)$r['id'] ?><?= $active_cat ? '&cat=' . $active_cat : '' ?>" class="btn-edit">Bewerken</a>
                            <form method="POST" action="<?= htmlspecialchars($back_url) ?>" onsubmit="return confirm('Product verwijderen?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id"     value="<?= (int)$r['id'] ?>">
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
