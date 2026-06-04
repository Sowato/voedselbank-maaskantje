<?php
/*
Naam: Krishna Sardarsing
Versie: 1.0
Datum: 03/06/2026
Beschrijving: Algemene functies voor authenticatie en autorisatie.
*/
function requireLogin() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../index.php');
        exit;
    }
}

function requireRole(array $roles) {
    requireLogin();
    if (!in_array($_SESSION['user_role'] ?? '', $roles)) {
        header('Location: dashboard.php');
        exit;
    }
}
