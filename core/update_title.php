<?php
// update_title.php
header('Content-Type: application/json');

session_start();
require_once 'db.php'; // Adjust this path to your database connection file

// Security check: Only allow logged-in users to perform the update
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

// Validate the presence of required data
if (!isset($_POST['blockId']) || !isset($_POST['title'])) {
    echo json_encode(['success' => false, 'message' => 'Missing data']);
    exit;
}

$blockId = $_POST['blockId'];
$title = trim($_POST['title']);

// Sanitize the input
$blockId = filter_var($blockId, FILTER_SANITIZE_NUMBER_INT);
$title = filter_var($title, FILTER_SANITIZE_STRING);

try {
    // Update the title in block_contents based on the block_id from blocks
    // Assuming block_id in blocks is a foreign key in block_contents
    $stmt = $conn->prepare("
        UPDATE block_contents bc
        JOIN blocks b ON bc.block_id = b.id
        SET bc.title = :title
        WHERE b.id = :blockId
    ");

    // Execute the statement with the sanitized inputs
    $stmt->execute([':title' => $title, ':blockId' => $blockId]);

    // Check if any rows were affected
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Title updated successfully']);
    } else {
        // No rows were updated - possibly because the block ID doesn't exist or the title was unchanged
        echo json_encode(['success' => false, 'message' => 'No update performed. Check if the block exists or the title was unchanged.']);
    }
} catch (PDOException $e) {
    // Catch any exceptions and report the error
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
