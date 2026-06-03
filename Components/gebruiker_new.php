<?php
// Processes the register form POST from Pages/register.php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

require_once __DIR__ . '/db_conn.php';

$email = $_POST['email'] ?? '';
$roles = $_POST['roles'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($roles) || empty($password)) {
    $error = 'Vul alle velden in';
    return;
}
//check of email al bestaat
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
$stmt->bindParam(':email', $email);
$stmt->execute();
if ($stmt->rowCount() > 0) {
    $error = 'Email bestaat al';
    return;
}


// Hash the password before storing it in the database
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert the new user into the database
$stmt = $conn->prepare("INSERT INTO users (email, roles, password) VALUES (:email, :roles, :password)");
$stmt->bindParam(':email', $email);
$stmt->bindParam(':roles', $roles);
$stmt->bindParam(':password', $hashedPassword);
if ($stmt->execute()) {
    $success = true;
} else {
    $error = 'Er is iets misgegaan bij het aanmaken van het account';
}
if ($success) {
    header('Location: ../Pages/admin_gebruiker_overzicht.php');
    popup('Gebruiker succesvol toegevoegd');
    exit;
}
// Close the database connection
$conn = null;