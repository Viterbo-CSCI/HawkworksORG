<?php
// fetch_block_content.php

session_start();
header('Content-Type: application/json');

require_once 'db.php'; // Adjust this path as needed

$blockId = isset($_GET['block_id']) ? intval($_GET['block_id']) : 0;

if ($blockId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid block ID.']);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT content FROM block_contents WHERE block_id = ?");
    $stmt->execute([$blockId]);
    $content = $stmt->fetchColumn();

    if ($content !== false) {
        echo json_encode(['success' => true, 'content' => $content]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Content not found.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' + $e->getMessage()]);
}
