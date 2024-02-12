<?php
require 'db.php'; // Ensure this points to your database connection script

if (!empty($_FILES['file'])) {
    // Handling file uploads
    foreach ($_FILES['file']['name'] as $key => $name) {
        $tempName = $_FILES['file']['tmp_name'][$key];
        $newName = "../imgs/" . time() . $name;
        if (move_uploaded_file($tempName, $newName)) {
            // Assuming you're sending block_id and metadata as part of formData in your AJAX call
            $block_id = $_POST['block_id'][$key] ?? null;
            $metadata = json_encode([
                'alt' => 'Image Description', // You might want to dynamically set this
                'title' => 'Image Title' // Same here for dynamic setting
            ]);

            $sql = "INSERT INTO block_content (block_id, content, metadata) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$block_id, $newName, $metadata]);

            echo json_encode(['success' => true, 'message' => 'File uploaded successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload file.']);
        }
    }
} elseif (isset($_POST['blockId']) && isset($_POST['content'])) {
    // Handling text content updates
    $blockId = $_POST['blockId'];
    $content = $_POST['content'];
    $metadata = json_encode([]); // Add any metadata if necessary

    $sql = "UPDATE block_content SET content = ?, metadata = ? WHERE block_id = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$content, $metadata, $blockId])) {
        echo json_encode(['success' => true, 'message' => 'Content updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update content.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No data received.']);
}
