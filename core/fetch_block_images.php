<?php
// Include database connection
require_once 'db.php'; // Adjust the path as necessary

// Ensure there's an ID to work with
if (!isset($_GET['block_id'])) {
    echo json_encode(['success' => false, 'message' => 'No block ID provided']);
    exit;
}

$blockId = $_GET['block_id'];

// Prepare the statement to fetch image paths
$stmt = $conn->prepare("
    SELECT bc.content
    FROM block_contents bc
    JOIN blocks bl ON bc.block_id = bl.id
    WHERE bl.id = ? AND bl.block_type = 'image'
");

$stmt->execute([$blockId]);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($images) {
    // Convert content paths to a more usable array structure
    $imagePaths = array_map(function($img) {
        return ['url' => htmlspecialchars($img['content'])];
    }, $images);

    echo json_encode(['success' => true, 'images' => $imagePaths]);
} else {
    // No images found for the block
    echo json_encode(['success' => false, 'message' => 'No images found for this block.']);
}
?>
