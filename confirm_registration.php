<?php
require 'core/db.php'; // Include your database connection

// Get the token from the query string
$token = isset($_GET['token']) ? $_GET['token'] : '';

if (empty($token)) {
    die('Token is required.');
}

try {
    // Prepare a select statement to check if the token exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        // Token is valid, activate the user account and remove the token
        $updateStmt = $conn->prepare("UPDATE users SET is_active = 1, token = NULL WHERE id = ?");
        $updateStmt->execute([$user['id']]);

        echo 'Your account has been activated successfully. You can now <a href="login.php">login</a>.';
    } else {
        // Token is invalid or does not exist
        echo 'This activation link is invalid or has already been used.';
    }
} catch (PDOException $e) {
    die("Error during confirmation: " . $e->getMessage());
}
?>
