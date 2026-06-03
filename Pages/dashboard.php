<?php
require_once __DIR__ . '/../Components/funcs.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – Voedselbank Maaskantje</title>
    <link rel="stylesheet" href="../dashboard.css">
</head>
<body>

<nav class="navbar">

    <div class="logo">
        Maaskantje
    </div>

    <ul class="menu">

        <li>
            <a href="#">Home</a>
        </li>

        <li>
            <a href="#">Leveringen</a>
        </li>

        <li>
            <a href="#">Uitgifte</a>
        </li>

        <li>
            <a href="#">Beheer ▼</a>

            <ul class="dropdown">

                <li>
                    <a href="#">Leveranciers</a>
                </li>

                <li class="has-submenu">
                    <a href="#">Voorraad</a>

                    <ul class="submenu">
                        <li>
                            <a href="#">Magazijn voorraad</a>
                        </li>

                        <li>
                            <a href="#">Product voorraad overzicht</a>
                        </li>
                    </ul>

                </li>

                <li>
                    <a href="#">Voedselpakketten</a>
                </li>

                <li class="has-submenu">
                    <a href="#">Klanten</a>

                    <ul class="submenu">
                        <li>
                            <a href="#">Beheer klanten</a>
                        </li>

                        <li>
                            <a href="#">Pakketten overzicht</a>
                        </li>
                    </ul>

                </li>

            </ul>

        </li>

        <li>
            <a href="#">Admin</a>
        </li>

    </ul>

    <div class="navbar-user">
        <a href="../Components/logout.php" class="logout-btn">Uitloggen</a>
    </div>

</nav>

<div class="content">
    <div class="grid">
        <div class="card">
            <h3>Leverancier</h3>
            <p>Beheer van leveranciers: invoeren, verwerken, verwijderen en wijzigen.</p>
            <a href="#" class="btn">Ga naar leveranciers</a>
        </div>

        <div class="card">
            <h3>Voorraad beheer</h3>
            <p>Beheer van de magazijnvoorraad en product voorraad overzicht.</p>
            <a href="#" class="btn">Ga naar voorraad beheer</a>
        </div>

        <div class="card">
            <h3>Voedselpakketten</h3>
            <p>Samenstellen van een pakket voor een klant met aanwezige producten in het magazijn.</p>
            <a href="#" class="btn">Ga naar voedselpakketten</a>
        </div>

        <div class="card">
            <h3>Klanten</h3>
            <p>Beheer van klanten met hun specifieke wensen en gezinssamenstelling en overzicht afgenomen voedselpakketten.</p>
            <a href="#" class="btn">Ga somewhere</a>
        </div>
    </div>
</div>

</body>
</html>
