<?php
// story.php
session_start();
require_once 'core/db.php';
require_once 'core/render.php';

$editMode = isset($_SESSION['loggedin']) && $_SESSION['loggedin'];
$storyBlockId = 7; // The block ID for the story content area. Make sure this is unique.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Story</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="<?php echo $editMode ? 'logged-in' : ''; ?> pg_story">
<?php
require_once 'header.php'; // Include the header
?>

<!-- Story Header with full-width background -->
<header class="story-header">
    <div class="container text-center">
        <?php if ($editMode): ?>
            <!-- Editable title element -->
            <div id="title_<?php echo $storyBlockId; ?>" class="editable-block" contenteditable="true">
                <?php echo(render_title($storyBlockId)); ?>
            </div>
            <!-- Save button for the title -->
            <button class="save-btn" onclick="update_title(<?php echo $storyBlockId; ?>, $('#title_<?php echo $storyBlockId; ?>').text())">Save Title</button>

        <?php else: ?>
            <!-- Display title normally if not in edit mode -->
            <h2 class="story-title"><?php echo(render_title($storyBlockId)); ?></h2>
        <?php endif; ?>
    </div>
</header>

<!-- Story Content with full-width background -->
<section id="block_<?php echo $storyBlockId; ?>" class="story-content">
    <div class="container editable-block">
        <div id="block_<?php echo $storyBlockId; ?>" class="editable-block">
                <div class="content"><?php echo htmlspecialchars_decode(render_block($storyBlockId, $conn)); ?></div>
                <?php if ($editMode): ?>
                    <button class="edit-btn" data-block-id="<?php echo $storyBlockId; ?>" data-type="text">Edit</button>
                <?php endif; ?>
        </div>
    </div>
</section>
<!-- Story Footer with full-width background -->
<footer class="story-footer">
    <div class="container text-center">
        <p class="footer-text">We're ready to talk...</p>
        <p>Wherever you are on your startup journey, get in touch and let's unpack your thinking together and see where we can help turn your idea into a reality.</p>
        <a class="btn btn-primary" href="contact.php" role="button">Get in Touch</a>
    </div>
</footer>

<?php
require_once 'footer.php'; // Include the footer
?>
</body>
</html>
