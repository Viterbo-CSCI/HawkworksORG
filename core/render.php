<?php
// render.php
include 'db.php'; // This will provide the $conn PDO object

function render_block($blockId) {
    global $conn; // Use the global database connection variable
    
    // Adjusted to fetch the id of each block_content
    $stmt = $conn->prepare("
        SELECT bl.block_type, bc.id AS content_id, bc.content
        FROM blocks bl
        JOIN block_contents bc ON bl.id = bc.block_id
        WHERE bl.id = ?
    ");
    $stmt->execute([$blockId]);
    $contents = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$contents) {
        return ''; // Block does not exist or no content
    }

    ob_start(); // Start output buffering

    $blockType = $contents[0]['block_type'];
    echo "<div class=\"block-wrapper\" id=\"block_" . htmlspecialchars($blockId) . "\" data-block-type=\"" . htmlspecialchars($blockType) . "\">";

    switch ($blockType) {
        case 'image':
            echo '<div class="row">';
            foreach ($contents as $content) {
                // Include content_id in the markup, typically as a data attribute
                echo '<div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-4" data-content-id="' . $content['content_id'] . '">';
                echo '<img src="' . htmlspecialchars($content['content']) . '" alt="Image" class="img-fluid">';
                echo '</div>';
            }
            echo '</div>';
            break;
        case 'html':
            foreach ($contents as $content) {
                // Echo the content directly for HTML blocks.
                echo '<div class="' . htmlspecialchars($blockType) . '-block" data-content-id="' . $content['content_id'] . '">';
                echo $content['content']; // Do not escape the HTML content.
                echo '</div>';
            }
            break;
        
        case 'text':
            foreach ($contents as $content) {
                // Use htmlspecialchars for text blocks to prevent XSS.
                echo '<div class="' . htmlspecialchars($blockType) . '-block" data-content-id="' . $content['content_id'] . '">';
                echo htmlspecialchars($content['content']);
                echo '</div>';
            }
            break;
}

    echo "</div>"; // Close block-wrapper

    return ob_get_clean(); // End output buffering and return the buffer content
}
