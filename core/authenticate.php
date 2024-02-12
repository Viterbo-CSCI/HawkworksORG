<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
require 'core/config.php';
ini_set('display_errors', 1);
require 'core/db.php'; // Adjust to use your actual database connection setup

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();
echo("SELECT id, password FROM users WHERE username =" . $username); 

echo('id'. $user['id']);
if ($user && password_verify($password, $user['password'])) {
    // Password is correct
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['loggedin'] = true;
    header("Location: welcome.php");
    exit;
} else {
    // Invalid credentials
    $_SESSION['loggedin'] = false;
    header("Location: login.php?error=invalid_credentials");
    exit;
}
?>
