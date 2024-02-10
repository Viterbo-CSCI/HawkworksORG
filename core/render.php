<?php
// render.php
include 'db.php'; // This will provide the $conn PDO object

function render_block($blockId) {
    global $conn; // Use the global database connection variable
    
    // Fetch the block type and content based on block ID by joining the `blocks` and `block_contents` tables
    $stmt = $conn->prepare("
        SELECT bl.block_type, bc.content
        FROM blocks bl
        JOIN block_contents bc ON bl.id = bc.block_id
        WHERE bl.id = ?
    ");
    $stmt->execute([$blockId]);
    $block = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$block) {
        return ''; // Block does not exist or no content
    }

    // Start output buffering to capture HTML output
    ob_start();

    // Render the block based on its type
    $blockTypeClass = htmlspecialchars($block['block_type']);
    echo "<div class=\"block-wrapper " . $blockTypeClass . "\" id=\"block_" . $blockId . "\">";

    switch ($block['block_type']) {
        case 'image':
            // Render image block
            echo "<div class=\"block image-block\" style=\"background-image: url('" . htmlspecialchars($block['content']) . "'); height: 300px; background-size: cover; background-position: center;\"></div>";
            break;
        case 'html':
            // Render HTML block, ensure this content is safe to display
            echo "<div class=\"block html-block\">" . $block['content'] . "</div>";
            break;
        case 'text':
            // Render text block
            echo "<div class=\"block text-block\"><p>" . htmlspecialchars($block['content']) . "</p></div>";
            break;
    }

    echo "</div>";

    // End output buffering and return the buffer content
    return ob_get_clean();
}
