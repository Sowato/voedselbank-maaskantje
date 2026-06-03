<?php
require_once __DIR__ . '/../Components/funcs.php';
requireRole(['admin']);
require_once __DIR__ . '/../Components/db_conn.php';

$db = (new Database())->getConnection();

$error   = '';
$success = '';

// --- DELETE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $id = (int)$_POST['id'];
    // Zet category_id op NULL voor producten in deze categorie
    $db->prepare('UPDATE product SET category_id = NULL WHERE category_id = ?')->execute([$id]);
    $db->prepare('DELETE FROM category WHERE id = ?')->execute([$id]);
    $success = 'Categorie verwijderd.';
}

// --- UPDATE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
    $db->prepare('UPDATE category SET code=?, omschrijving=? WHERE id=?')
       ->execute([
           strtoupper(trim($_POST['code'])),
           trim($_POST['omschrijving']),
           (int)$_POST['id'],
       ]);
    $success = 'Categorie bijgewerkt.';
}

// --- CREATE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
    $db->prepare('INSERT INTO category (code, omschrijving) VALUES (?, ?)')
       ->execute([
           strtoupper(trim($_POST['code'])),
           trim($_POST['omschrijving']),
       ]);
    $success = 'Categorie aangemaakt.';
}

// Edit-modus
$edit_row = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $stmt = $db->prepare('SELECT * FROM category WHERE id = ?');
    $stmt->execute([(int)$_GET['id']]);
    $edit_row = $stmt->fetch(PDO::FETCH_ASSOC);
}

$categorieen = $db->query('
    SELECT c.*, COUNT(p.id) AS aantal_producten
    FROM category c
    LEFT JOIN product p ON p.category_id = c.id
    GROUP BY c.id, c.code, c.omschrijving
    ORDER BY c.omschrijving
')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorieën – Maaskantje</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/leveringen.css">
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
            <h3>Categorie bewerken</h3>
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id"     value="<?= (int)$edit_row['id'] ?>">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Code</label>
                        <input type="text" name="code" maxlength="25"
                               value="<?= htmlspecialchars($edit_row['code']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Omschrijving</label>
                        <input type="text" name="omschrijving"
                               value="<?= htmlspecialchars($edit_row['omschrijving']) ?>" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-save">Opslaan</button>
                    <a href="categorieen.php" class="btn-cancel">Annuleren</a>
                </div>
            </form>
        </div>
    <?php else: ?>
        <div class="form-card">
            <h3>Nieuwe categorie aanmaken</h3>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Code</label>
                        <input type="text" name="code" maxlength="25" placeholder="bijv. DIEPVRIES" required>
                    </div>
                    <div class="form-group">
                        <label>Omschrijving</label>
                        <input type="text" name="omschrijving" placeholder="bijv. Diepvriesproducten" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-add">Aanmaken</button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- Tabel -->
    <div class="page-header">
        <h2>Categorieën overzicht</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Omschrijving</th>
                <th>Aantal producten</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorieen as $c): ?>
                <tr>
                    <td><?= (int)$c['id'] ?></td>
                    <td><strong><?= htmlspecialchars($c['code']) ?></strong></td>
                    <td><?= htmlspecialchars($c['omschrijving']) ?></td>
                    <td><?= (int)$c['aantal_producten'] ?></td>
                    <td style="display:flex; gap:6px;">
                        <a href="?action=edit&id=<?= (int)$c['id'] ?>" class="btn-edit">Bewerken</a>
                        <form method="POST" onsubmit="return confirm('Categorie verwijderen? Producten in deze categorie verliezen hun categorie.')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id"     value="<?= (int)$c['id'] ?>">
                            <button type="submit" class="btn-delete">Verwijderen</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
</body>
</html>
