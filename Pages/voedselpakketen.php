<?php
require_once __DIR__ . '/../Components/funcs.php';
requireRole(['admin', 'vrijwilliger']);
require_once __DIR__ . '/../Components/db_conn.php';

$db = (new Database())->getConnection();

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
    $db->prepare('DELETE FROM inhoud  WHERE pakket_id = ?')->execute([$id]);
    $db->prepare('DELETE FROM pakket WHERE id = ?')->execute([$id]);
    $success = 'Pakket verwijderd.';
}

// --- UPDATE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
    $id             = (int)$_POST['id'];
    $klant_id       = (int)$_POST['klant_id'];
    $datum          = $_POST['datum'];
    $uitgifte_datum = $_POST['uitgifte_datum'] ?: null;

    $db->prepare('UPDATE pakket SET klant_id=?, datum=?, uitgifte_datum=? WHERE id=?')
       ->execute([$klant_id, $datum, $uitgifte_datum, $id]);
    $success = 'Pakket bijgewerkt.';
}

// --- CREATE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
    $klant_id       = (int)$_POST['klant_id'];
    $datum          = $_POST['datum'];
    $uitgifte_datum = $_POST['uitgifte_datum'] ?: null;

    $db->prepare('INSERT INTO pakket (user_id, klant_id, datum, uitgifte_datum) VALUES (?,?,?,?)')
       ->execute([$session_user_id, $klant_id, $datum, $uitgifte_datum]);
    $success = 'Pakket aangemaakt.';
}

// Edit-modus
$edit_row = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $stmt = $db->prepare('SELECT * FROM pakket WHERE id = ?');
    $stmt->execute([(int)$_GET['id']]);
    $edit_row = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Haal alle pakketten op
$pakketten = $db->query('
    SELECT p.id, k.gezins_naam AS klant, p.datum, p.uitgifte_datum,
           COUNT(i.id) AS aantal_producten
    FROM pakket p
    JOIN klant k ON k.id = p.klant_id
    LEFT JOIN inhoud i ON i.pakket_id = p.id
    GROUP BY p.id, k.gezins_naam, p.datum, p.uitgifte_datum
    ORDER BY p.datum DESC
')->fetchAll(PDO::FETCH_ASSOC);

$klanten = $db->query('SELECT id, gezins_naam FROM klant ORDER BY gezins_naam')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voedselpakketten – Maaskantje</title>
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

    <?php if (empty($klanten)): ?>
        <div class="alert alert-error">Geen klanten gevonden. Voeg eerst klanten toe voordat je pakketten aanmaakt.</div>
    <?php else: ?>

    <!-- Formulier -->
    <?php if ($edit_row): ?>
        <div class="form-card">
            <h3>Pakket bewerken</h3>
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?= (int)$edit_row['id'] ?>">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Klant</label>
                        <select name="klant_id" required>
                            <?php foreach ($klanten as $k): ?>
                                <option value="<?= $k['id'] ?>" <?= $k['id'] == $edit_row['klant_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($k['gezins_naam']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Aanmaakdatum</label>
                        <input type="datetime-local" name="datum"
                               value="<?= date('Y-m-d\TH:i', strtotime($edit_row['datum'])) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Uitgiftedatum</label>
                        <input type="datetime-local" name="uitgifte_datum"
                               value="<?= $edit_row['uitgifte_datum'] ? date('Y-m-d\TH:i', strtotime($edit_row['uitgifte_datum'])) : '' ?>">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-save">Opslaan</button>
                    <a href="voedselpakketen.php" class="btn-cancel">Annuleren</a>
                </div>
            </form>
        </div>
    <?php else: ?>
        <div class="form-card">
            <h3>Nieuw voedselpakket aanmaken</h3>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Klant</label>
                        <select name="klant_id" required>
                            <option value="">— Kies klant —</option>
                            <?php foreach ($klanten as $k): ?>
                                <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['gezins_naam']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Aanmaakdatum</label>
                        <input type="datetime-local" name="datum" required>
                    </div>
                    <div class="form-group">
                        <label>Uitgiftedatum <small style="font-weight:normal">(optioneel)</small></label>
                        <input type="datetime-local" name="uitgifte_datum">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-add">Aanmaken</button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <?php endif; ?>

    <!-- Tabel -->
    <div class="page-header">
        <h2>Voedselpakketten overzicht</h2>
    </div>

    <?php if (empty($pakketten)): ?>
        <p style="color:#888; font-size:14px;">Nog geen pakketten gevonden.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Klant</th>
                    <th>Aanmaakdatum</th>
                    <th>Uitgiftedatum</th>
                    <th>Producten</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pakketten as $r): ?>
                    <tr>
                        <td><?= (int)$r['id'] ?></td>
                        <td><?= htmlspecialchars($r['klant']) ?></td>
                        <td><?= htmlspecialchars($r['datum']) ?></td>
                        <td><?= $r['uitgifte_datum'] ? htmlspecialchars($r['uitgifte_datum']) : '<span style="color:#aaa">—</span>' ?></td>
                        <td><?= (int)$r['aantal_producten'] ?></td>
                        <td style="display:flex; gap:6px;">
                            <a href="?action=edit&id=<?= (int)$r['id'] ?>" class="btn-edit">Bewerken</a>
                            <form method="POST" onsubmit="return confirm('Weet je zeker dat je dit pakket wilt verwijderen?')">
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
