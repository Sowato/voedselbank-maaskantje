<?php
// Gebruik: include vanuit Pages/
$_role = $_SESSION['user_role'] ?? '';
?>
<nav class="navbar">
    <div class="logo">Maaskantje</div>

    <ul class="menu">
        <li><a href="dashboard.php">Home</a></li>

        <?php if ($_role === 'admin' || $_role === 'medewerker'): ?>
        <li><a href="leveringen.php">Leveringen</a></li>
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
                <?php if ($_role === 'admin'): ?>
                <li><a href="voedselpakketen.php">Voedselpakketten</a></li>
                <li class="has-submenu">
                    <a href="#">Klanten</a>
                    <ul class="submenu">
                        <li><a href="klant.php">Beheer klanten</a></li>
                        <li><a href="klanten_overzicht.php">Pakketten overzicht</a></li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
        </li>
        <?php endif; ?>

        <?php if ($_role === 'vrijwilliger' || $_role === 'admin'): ?>
        <li><a href="voedselpakketen.php">Voedselpakketten</a></li>
        <?php endif; ?>

        <?php if ($_role === 'admin'): ?>
        <li><a href="uitgifte.php">Uitgifte</a></li>
        <li><a href="#">Admin</a></li>
        <?php endif; ?>
    </ul>

    <div class="navbar-user">
        <span class="role-badge"><?= htmlspecialchars($_role) ?></span>
        <a href="../Components/logout.php" class="logout-btn">Uitloggen</a>
    </div>
</nav>
