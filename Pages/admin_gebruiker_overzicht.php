<?php
/*
Naam: Krishna Sardarsing
Versie: 1.0
Datum: 03/06/2026
Beschrijving: Admin overzicht van gebruikers CRUD Systeem
*/
require_once __DIR__ . '/../Components/funcs.php';
requireRole(['admin']);
require_once __DIR__ . '/../Components/db_conn.php';

$db = (new Database())->getConnection();

$error   = '';
$success = '';

// --- DELETE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $id = (int)$_POST['id'];
    if ($id === (int)$_SESSION['user_id']) {
        $error = 'Je kan je eigen account niet verwijderen.';
    } else {
        $db->prepare('DELETE FROM user WHERE id = ?')->execute([$id]);
        $success = 'Gebruiker verwijderd.';
    }
}

// --- UPDATE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
    $id    = (int)$_POST['id'];
    $email = trim($_POST['email']);
    $role  = $_POST['role'];
    $nieuw_wachtwoord = $_POST['nieuw_wachtwoord'] ?? '';

    $allowed = ['vrijwilliger', 'medewerker', 'admin'];
    if (!in_array($role, $allowed)) {
        $error = 'Ongeldige rol.';
    } else {
        // Check of email al in gebruik is bij een andere gebruiker
        $stmt = $db->prepare('SELECT id FROM user WHERE email = ? AND id != ?');
        $stmt->execute([$email, $id]);
        if ($stmt->fetch()) {
            $error = 'Dit e-mailadres is al in gebruik.';
        } elseif ($nieuw_wachtwoord !== '') {
            if (strlen($nieuw_wachtwoord) < 6) {
                $error = 'Nieuw wachtwoord moet minimaal 6 tekens zijn.';
            } else {
                $hash = password_hash($nieuw_wachtwoord, PASSWORD_DEFAULT);
                $db->prepare('UPDATE user SET email=?, roles=?, password=? WHERE id=?')
                   ->execute([$email, $role, $hash, $id]);
                $success = 'Gebruiker bijgewerkt (inclusief wachtwoord).';
            }
        } else {
            $db->prepare('UPDATE user SET email=?, roles=? WHERE id=?')
               ->execute([$email, $role, $id]);
            $success = 'Gebruiker bijgewerkt.';
        }
    }
}

// --- CREATE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
    $email    = trim($_POST['email']);
    $role     = $_POST['role'];
    $password = $_POST['password'];
    $allowed  = ['vrijwilliger', 'medewerker', 'admin'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Voer een geldig e-mailadres in.';
    } elseif (!in_array($role, $allowed)) {
        $error = 'Kies een geldige rol.';
    } elseif (strlen($password) < 6) {
        $error = 'Wachtwoord moet minimaal 6 tekens zijn.';
    } else {
        $stmt = $db->prepare('SELECT id FROM user WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Dit e-mailadres is al in gebruik.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $db->prepare('INSERT INTO user (email, roles, password) VALUES (?,?,?)')
               ->execute([$email, $role, $hash]);
            $success = 'Gebruiker aangemaakt.';
        }
    }
}

// Edit-modus
$edit_row = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $stmt = $db->prepare('SELECT id, email, roles FROM user WHERE id = ?');
    $stmt->execute([(int)$_GET['id']]);
    $edit_row = $stmt->fetch(PDO::FETCH_ASSOC);
}

$gebruikers = $db->query('SELECT id, email, roles FROM user ORDER BY roles, email')->fetchAll(PDO::FETCH_ASSOC);

$rol_labels = [
    'admin'       => ['label' => 'Admin',       'kleur' => '#dc3545'],
    'medewerker'  => ['label' => 'Medewerker',  'kleur' => '#0d6efd'],
    'vrijwilliger'=> ['label' => 'Vrijwilliger', 'kleur' => '#198754'],
];
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebruikers – Maaskantje</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/leveringen.css">
    <style>
        .rol-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
        }
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
            <h3>Gebruiker bewerken</h3>
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id"     value="<?= (int)$edit_row['id'] ?>">
                <div class="form-grid">
                    <div class="form-group">
                        <label>E-mailadres</label>
                        <input type="email" name="email"
                               value="<?= htmlspecialchars($edit_row['email']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Rol</label>
                        <select name="role" required>
                            <option value="vrijwilliger" <?= $edit_row['roles'] === 'vrijwilliger' ? 'selected' : '' ?>>Vrijwilliger</option>
                            <option value="medewerker"   <?= $edit_row['roles'] === 'medewerker'   ? 'selected' : '' ?>>Medewerker</option>
                            <option value="admin"        <?= $edit_row['roles'] === 'admin'        ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nieuw wachtwoord <small style="font-weight:normal">(leeglaten = niet wijzigen)</small></label>
                        <input type="password" name="nieuw_wachtwoord" placeholder="Min. 6 tekens">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-save">Opslaan</button>
                    <a href="admin_gebruiker_overzicht.php" class="btn-cancel">Annuleren</a>
                </div>
            </form>
        </div>
    <?php else: ?>
        <div class="form-card">
            <h3>Nieuwe gebruiker aanmaken</h3>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="form-grid">
                    <div class="form-group">
                        <label>E-mailadres</label>
                        <input type="email" name="email" placeholder="gebruiker@voorbeeld.nl" required>
                    </div>
                    <div class="form-group">
                        <label>Rol</label>
                        <select name="role" required>
                            <option value="">— Kies rol —</option>
                            <option value="vrijwilliger">Vrijwilliger</option>
                            <option value="medewerker">Medewerker</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Wachtwoord</label>
                        <input type="password" name="password" placeholder="Min. 6 tekens" required>
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
        <h2>Gebruikers overzicht (<?= count($gebruikers) ?>)</h2>
    </div>

    <?php if (empty($gebruikers)): ?>
        <p style="color:#888; font-size:14px;">Nog geen gebruikers gevonden.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>E-mailadres</th>
                    <th>Rol</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($gebruikers as $g): ?>
                    <?php $rl = $rol_labels[$g['roles']] ?? ['label' => ucfirst($g['roles']), 'kleur' => '#6c757d']; ?>
                    <tr>
                        <td><?= (int)$g['id'] ?></td>
                        <td><?= htmlspecialchars($g['email']) ?></td>
                        <td>
                            <span class="rol-badge" style="background:<?= $rl['kleur'] ?>">
                                <?= $rl['label'] ?>
                            </span>
                        </td>
                        <td style="display:flex; gap:6px;">
                            <a href="?action=edit&id=<?= (int)$g['id'] ?>" class="btn-edit">Bewerken</a>
                            <?php if ((int)$g['id'] !== (int)$_SESSION['user_id']): ?>
                            <form method="POST" onsubmit="return confirm('Gebruiker verwijderen?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id"     value="<?= (int)$g['id'] ?>">
                                <button type="submit" class="btn-delete">Verwijderen</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>
</body>
</html>
