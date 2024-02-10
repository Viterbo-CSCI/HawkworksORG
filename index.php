<?php
// index.php
session_start();
require_once 'core/db.php';
require_once 'core/render.php';

$editMode = isset($_SESSION['loggedin']) && $_SESSION['loggedin'];

$bannerBlockId = 2; // The block ID for the banner area
$newsBlockId = 3; // The block ID for the news feed area
$infoBlockId = 4; // The block ID for the basic information area
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dynamic CMS Homepage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body class="<?php echo $editMode ? 'logged-in' : ''; ?> pg_1">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">Dynamic CMS</a>
        <?php if ($editMode): ?>
            <a href="logout.php" class="btn btn-danger ms-auto">Logout</a>
        <?php endif; ?>
    </div>
</nav>

<div class="container my-4">
    <!-- Full-width Banner Area -->
    <div id="block_<?php echo $bannerBlockId; ?>" class="editable-block">
        <div class="content"><?php echo render_block($bannerBlockId, $conn); ?></div>
        <?php if ($editMode): ?>
            <button class="edit-btn" onclick="editBlock(<?php echo $bannerBlockId; ?>, 'image')">Edit</button>
        <?php endif; ?>
    </div>

    <!-- Side-by-Side Content Area -->
    <div class="row">
        <!-- News Feed Area -->
        <div id="block_<?php echo $newsBlockId; ?>" class="col-lg-6 editable-block">
            <div class="content"><?php echo render_block($newsBlockId, $conn); ?></div>
            <?php if ($editMode): ?>
                <button class="edit-btn" onclick="editBlock(<?php echo $newsBlockId; ?>)">Edit</button>
            <?php endif; ?>
        </div>

        <!-- Basic Information Area -->
        <div id="block_<?php echo $infoBlockId; ?>" class="col-lg-6 editable-block">
            <div class="content"><?php echo render_block($infoBlockId, $conn); ?></div>
            <?php if ($editMode): ?>
                <button class="edit-btn" onclick="editBlock(<?php echo $infoBlockId; ?>)">Edit</button>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Additional sections can be added here -->
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
<script src="core/js/edit_blocks.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
