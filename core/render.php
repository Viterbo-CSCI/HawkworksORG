<?php
// render.php
include 'db.php'; // This will provide the $conn PDO object

function render_block($blockId) {
    global $conn; // Use the global database connection variable
    
    // Fetch all contents for the block based on block ID
    $stmt = $conn->prepare("
        SELECT bl.block_type, bc.content
        FROM blocks bl
        JOIN block_contents bc ON bl.id = bc.block_id
        WHERE bl.id = ?
    ");
    $stmt->execute([$blockId]);
    $contents = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$contents) {
        return ''; // Block does not exist or no content
    }

    // Determine if there are multiple contents
    $isMultiple = count($contents) > 1;

    // Start output buffering to capture HTML output
    ob_start();

    $blockType = $contents[0]['block_type'];
    $blockTypeClass = htmlspecialchars($blockType);
    echo "<div class=\"block-wrapper " . $blockTypeClass . "\" id=\"block_" . $blockId . "\">";

    switch ($blockType) {
        case 'image':
            // Open the row div
            echo '<div class="row">';
            foreach ($contents as $content) {
                // Output each image in a grid column
                echo '<div class="col-lg-2 col-md-4 col-sm-6 col-12 mb-4">';
                echo '<img src="' . htmlspecialchars($content['content']) . '" alt="Image description" class="img-fluid">';
                echo '</div>';
            }
            // Close the row div
            echo '</div>';
            break;
        
        case 'html':
            // Concatenate or handle HTML content blocks
            foreach ($contents as $content) {
                echo "<div class=\"html-block\">" . $content['content'] . "</div>";
            }
            break;
        case 'text':
            // Concatenate or handle text content blocks
            foreach ($contents as $content) {
                echo "<div class=\"text-block\"><p>" . htmlspecialchars($content['content']) . "</p></div>";
            }
            break;
    }

    echo "</div>";

    // End output buffering and return the buffer content
    return ob_get_clean();
}
