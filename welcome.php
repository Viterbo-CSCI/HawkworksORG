<?php
session_start();
require 'vendor/autoload.php';
require 'core/config.php';
ini_set('display_errors', 1);
// Redirect if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include your database connection file
require_once 'core/db.php';

// Assuming your session stores the user's ID
$userId = $_SESSION['user_id'];

// Prepare a select statement to fetch user details
$stmt = $conn->prepare("SELECT username, email, created_at, is_active FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Check if user exists
if (!$user) {
    //echo "User not found. Please log in again." . $user;
    //print_r($_SESSION); 
    exit;
}

// Now you can use $user['username'], $user['email'], etc., to access the user's details
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <style>
        body { font: 14px sans-serif; }
        .wrapper { padding: 20px; }
        .content-table { margin-top: 20px; }
        .table-sortable th { cursor: pointer; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">CMS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedCMS" aria-controls="navbarSupportedCMS" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedCMS">
                <!-- Dynamic Toolbar Placeholder -->
                <!-- Permissions based toolbar will be loaded here -->
            </div>
        </div>
    </nav>

    <div class="wrapper container">
        <div class="row">
            <!-- User Information Panel -->
            <div class="col-md-6">
                <h2>User Information</h2>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Account Created On:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
                <p><strong>Account Status:</strong> <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?></p>
                <a href="logout.php" class="btn btn-danger">Sign Out</a>
            </div>

            <!-- Content Listing Panel -->
            <div class="col-md-6">
                <h2>Content</h2>
                <table class="table table-bordered content-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Demo Content -->
                        <tr>
                            <td>Demo Article</td>
                            <td>
                                <button class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</button>
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Delete</button>
                            </td>
                        </tr>
                        <!-- More content rows will be loaded here dynamically -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Informational Panel -->
        <div class="row mt-4">
            <div class="col">
                <h2>Information</h2>
                <p>This is a demo content management system. More features and content will be added here as the system is developed.</p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Include any additional JS libraries for table sorting here -->
</body>
</html>
