<?php
// save_block_content.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();
require_once 'db.php';
//require_once 'HTMLPurifier.auto.php'; // Make sure to include the HTMLPurifier library

header('Content-Type: application/json');

//$config = HTMLPurifier_Config::createDefault();
//$purifier = new HTMLPurifier($config);

try {
    if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
        throw new Exception('Not logged in');
    }

    $blockId = $_POST['block_id'] ?? null;
    // Use HTMLPurifier to clean the content, instead of escaping it with htmlspecialchars.
    //$content = isset($_POST['content']) ? $purifier->purify($_POST['content']) : null;
    $content = $_POST['content'] ?? null;
    if (!$blockId) {
        throw new Exception('Block ID is missing');
    }

    if ($content !== null) {
        // Update the text content
        $stmt = $conn->prepare("UPDATE block_contents SET content = ? WHERE block_id = ?");
        $stmt->execute([$content, $blockId]);

        echo json_encode(['success' => true, 'content' => $content]);
    } else {
        throw new Exception('No content provided');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
