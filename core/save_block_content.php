<?php
// save_block_content.php

session_start();
require_once 'db.php';

header('Content-Type: application/json');

// Function to sanitize file names
function sanitizeFileName($filename) {
    // Remove any characters that aren't digits, letters, or underscores
    return preg_replace('/[^A-Za-z0-9_\-\.]+/', '_', $filename);
}

try {
    if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
        throw new Exception('Not logged in');
    }

    // Check if it's an image upload
    if (isset($_FILES['image'])) {
        $blockId = $_POST['block_id'] ?? null;
        if (!$blockId) {
            throw new Exception('Block ID is missing');
        }
        
        $file = $_FILES['image'];
        $uploadDirectory = '../imgs/';
        $sanitizedFileName = sanitizeFileName($file['name']);
        $filePath = $uploadDirectory . time() . $sanitizedFileName;

        // Check and create the upload directory if it doesn't exist
        if (!file_exists($uploadDirectory) && !mkdir($uploadDirectory, 0755, true)) {
            throw new Exception('Failed to create upload directory');
        }

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // Update the database with the new image path
            $stmt = $conn->prepare("UPDATE block_contents SET content = ? WHERE block_id = ?");
            $stmt->execute([$filePath, $blockId]);
            echo json_encode(['success' => true, 'newImagePath' => $filePath]);
        } else {
            throw new Exception('Failed to move uploaded file');
        }
    } else {
        // Handle text and HTML content updates
        $blockId = $_POST['block_id'] ?? null;
        $content = $_POST['content'] ?? '';

        if (!$blockId) {
            throw new Exception('Block ID is missing');
        }
        if ($content === '') {
            throw new Exception('Content is empty');
        }

        $stmt = $conn->prepare("UPDATE block_contents SET content = ? WHERE block_id = ?");
        $stmt->execute([$content, $blockId]);
        echo json_encode(['success' => true, 'content' => $content]);
    }
} catch (Exception $e) {
    // Return an error message
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}
