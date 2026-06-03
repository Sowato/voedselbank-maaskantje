<?php ?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Maaskantje</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<nav class="navbar">

    <div class="logo">
        Maaskantje
    </div>

    <ul class="menu">

        <li><a href="#">Home</a></li>
        <li><a href="#">Leveringen</a></li>
        <li><a href="#">Uitgifte</a></li>

        <li>
            <a href="#">Beheer ▼</a>

            <ul class="dropdown">

                <li><a href="#">Leveranciers</a></li>

                <li class="has-submenu">
                    <a href="#">Voorraad</a>

                    <ul class="submenu">
                        <li><a href="#">Magazijn voorraad</a></li>
                        <li><a href="#">Product voorraad overzicht</a></li>
                    </ul>
                </li>

                <li><a href="#">Voedselpakketten</a></li>

                <li class="has-submenu">
                    <a href="#">Klanten</a>

                    <ul class="submenu">
                        <li><a href="#">Beheer klanten</a></li>
                        <li><a href="#">Pakketten overzicht</a></li>
                    </ul>
                </li>

            </ul>
        </li>

        <li><a href="#">Admin</a></li>

    </ul>

</nav>

</body>
</html>