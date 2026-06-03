<?php
/*
Naam: Krishna Sardarsing
Versie: 1.0
Datum: 03/06/2026
Beschrijving: log out
*/
session_start();
session_destroy();
header('Location: ../index.php');
exit;
