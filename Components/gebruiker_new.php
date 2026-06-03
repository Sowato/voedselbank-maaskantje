<?php
/*
Naam: Kieran Teunissen
Versie: 1.0
Datum: 03/06/2026
Beschrijving: component voor het verwerken van het aanmaken van een nieuwe gebruiker
*/
// Eenvoudige verwerking van het registratiesformulier
if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

require_once __DIR__ . '/db_conn.php';

$email = $_POST['email'] ?? '';
$roles = isset($_POST['roles'])
    ? implode(', ', $_POST['roles'])
    : '';
$password = $_POST['password'] ?? '';


if (empty($email) || empty($roles) || empty($password)) {
    $error = 'Vul alle velden in';
    return;
}

//check of email al bestaat
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare("SELECT * FROM user WHERE email = :email");
$stmt->execute([':email' => $email]);
if ($stmt->fetch()) {
    echo "<script>alert('Email bestaat al'); window.location.href='../Pages/admin_gebruiker_new.php';</script>";
    exit;
}

// Hash the password before storing it in the database
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert the new user into the database
$stmt = $conn->prepare("INSERT INTO user (email, roles, password) VALUES (:email, :roles, :password)");
$success = $stmt->execute([
    ':email' => $email,
    ':roles' => $roles,
    ':password' => $hashedPassword,
]);

if ($success) {
    echo "<script>alert('Gebruiker succesvol toegevoegd'); window.location.href='../Pages/admin_gebruiker_overzicht.php';</script>";
    exit;
}

$error = 'Er is iets misgegaan bij het aanmaken van het account';
// Close the database connection
$conn = null;
