<?php
// index.php
session_start();
require_once 'core/db.php';
require_once 'core/render.php';

$editMode = isset($_SESSION['loggedin']) && $_SESSION['loggedin'];

$bannerBlockId = 5; // The block ID for the banner area
$newsBlockId = 4; // The block ID for the news feed area
$infoBlockId = 6; // The block ID for the basic information area
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dynamic CMS Homepage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">

</head>
<body class="<?php echo $editMode ? 'logged-in' : ''; ?> pg_1">
<?php
require_once 'header.php';
?>


<div class="jumbotron bg-white text-center">
    <h1 class="display-4">Empowering Innovation with HAWK WORKS</h1>
    <p class="lead">At HAWK WORKS, we bring together multidisciplinary teams to drive innovation, with a strong focus on community and student engagement.</p>
    <a class="btn btn-primary btn-lg" href="#" role="button">Founder in Residence</a>
</div>

<!-- Image Grid Section -->
<div class="container-fluid my-4">
    <!-- Full-width Banner Area -->
    <div id="block_<?php echo $bannerBlockId; ?>" class="editable-block">
        <?php echo render_block($bannerBlockId); ?>
        <?php if ($editMode): ?>
            <button class="edit-btn" data-block-id="<?php echo $bannerBlockId; ?>" data-type="image">Edit</button>
        <?php endif; ?>
    </div>
</div>



<!-- Icons and Text Section -->
<div class="container my-5 ic_sec">
    <div class="row text-center">
        <!-- Validate Icon & Text -->
        <div class="col-md-4">
            <i class="animated-color fas fa-thumbs-up fa-3x"></i>
            <h3>VALIDATE</h3>
            <p>We validate ideas quickly to find the most promising paths.</p>
        </div>
        <!-- Build Icon & Text -->
        <div class="col-md-4">
            <i class="animated-color fas fa-hammer fa-3x"></i>
            <h3>BUILD</h3>
            <p>Our teams build products that meet market needs.</p>
        </div>
        <!-- Accelerate Icon & Text -->
        <div class="col-md-4">
            <i class="animated-color fas fa-rocket fa-3x"></i>
            <h3>ACCELERATE</h3>
            <p>We accelerate development to move faster than the competition.</p>
        </div>
    </div>
</div>
<div class="section-wrapper">
    <!-- Co-Found With Us Section -->
    <div class="co-found-section large_sec text-center text-white py-5" style="background-color: #0056b3;">
        <div class="container">
            <!-- Basic Information Area -->
            <div id="block_<?php echo $infoBlockId; ?>" class="editable-block position-relative">
                <div class="content"><?php echo htmlspecialchars_decode(render_block($infoBlockId, $conn)); ?></div>
                <?php if ($editMode): ?>
                    <button class="edit-btn fr position-upper-right"" data-block-id="<?php echo $infoBlockId; ?>" data-type="text">Edit</button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <svg class="overlay-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">  <path fill="#ffffff" fill-opacity="1" d="M0,60C100,0,300,120,600,60C900,0,1100,120,1200,60L1200,120L0,120Z" /></svg>
    <!-- Co-Found With Us Section -->
    <div class="co-found-section2 large_sec text-center py-5" style="background-color: #0056b3;">
        <div class="container">
            <!-- Basic Information Area -->
            <div id="block_<?php echo $newsBlockId; ?>" class="editable-block">
                <div class="content"><?php echo htmlspecialchars_decode(render_block($newsBlockId, $conn)); ?></div>
                <?php if ($editMode): ?>
                    <button class="edit-btn" data-block-id="<?php echo $newsBlockId; ?>" data-type="text">Edit</button>
                <?php endif; ?>
            </div>
        </div>
    </div>


</div>
<?php
require_once 'footer.php';
?>
