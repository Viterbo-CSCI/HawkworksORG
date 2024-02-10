<?php
// edit_block.php

session_start();
require_once 'db.php';

// Check if the user is logged in and has permission to edit
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: login.php');
    exit;
}

$blockId = $_GET['block_id'] ?? 0;
$stmt = $conn->prepare("
    SELECT bl.block_type, bc.content
    FROM blocks bl
    INNER JOIN block_contents bc ON bl.id = bc.block_id
    WHERE bl.id = ?
");
$stmt->execute([$blockId]);
$block = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if a file is being uploaded
    if ($block['block_type'] == 'image' && isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
        // Handle the image upload
        $file = $_FILES['image'];
        $uploadDirectory = 'imgs/';
        $fileName = time() . basename($file['name']); // Prepend time to avoid filename conflicts
        $filePath = $uploadDirectory . $fileName;
        
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // File is uploaded successfully
            $newContent = $filePath;
        } else {
            // Handle error; file upload failed
            $newContent = $block['content']; // Keep the old content if upload fails
            // You may want to communicate the error to the user
        }
    } else {
        // Handle other content types, such as text or HTML
        $newContent = $_POST['content'] ?? ''; // Make sure to sanitize this properly!
    }

    // Update the content in the database
    $updateStmt = $conn->prepare("UPDATE block_contents SET content = ? WHERE block_id = ?");
    $updateStmt->execute([$newContent, $blockId]);

    // Redirect to prevent form resubmission
    header('Location: index.php');
    exit;
}

// Rest of your HTML below...
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... -->
</head>
<body>
    <form method="post" action="edit_block.php?block_id=<?php echo $blockId; ?>" enctype="multipart/form-data">
        <?php 
        switch ($block['block_type']) {
            case 'image':
                echo '<label for="image">Upload Image:</label>';
                echo '<input type="file" name="image">';
                echo '<img src="' . htmlspecialchars($block['content']) . '" alt="Current Image" height="100">';
                break;
            case 'html':
                echo '<label for="content">HTML Content:</label>';
                echo '<textarea name="content">' . htmlspecialchars($block['content']) . '</textarea>';
                break;
            case 'text':
                echo '<label for="content">Text Content:</label>';
                echo '<textarea name="content">' . htmlspecialchars($block['content']) . '</textarea>';
                break;
        }
        ?>
        <input type="submit" value="Save Changes">
    </form>
</body>
</html>
