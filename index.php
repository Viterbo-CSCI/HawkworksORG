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


<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="path_to_your_logo.png" alt="Logo" style="height: 40px;"> <!-- Replace with your logo path -->
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Launch Factory
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </li>
                <!-- Add more nav-item here as needed -->
            </ul>
            <?php if ($editMode): ?>
                <a href="logout.php" class="btn btn-outline-primary">Logout</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

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
    <div class="co-found-section2 large_sec text-center text-white py-5" style="background-color: #0056b3;">
        <div class="container">
            <!-- Basic Information Area -->
            <div id="block_<?php echo $infoBlockId; ?>" class="editable-block position-relative">
                <div class="content"><?php echo htmlspecialchars_decode(render_block($infoBlockId, $conn)); ?></div>
                <?php if ($editMode): ?>
                    <button class="edit-btn fr position-upper-right" data-block-id="<?php echo $infoBlockId; ?>" data-type="text">Edit</button>
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
<footer class="footer bg-dark text-white py-4">
    <div class="container">
        <div class="row">
            <!-- Logo and Privacy Policy -->
            <div class="col-md-4 footer-brand align-self-center">
                <img src="path_to_your_logo.png" alt="Launch Factory Logo" class="mb-2">
                <p>Privacy Policy</p>
                <p>Copyright ©2024 Launch Factory</p>
            </div>
            <!-- Menu -->
            <div class="col-md-3 footer-nav">
                <h5 class="text-uppercase mb-4">Menu</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white">Home</a></li>
                    <li><a href="#" class="text-white">About Us</a></li>
                    <li><a href="#" class="text-white">Join Our Newsletter</a></li>
                    <li><a href="#" class="text-white">Founder in Residence</a></li>
                </ul>
            </div>
            <!-- Social Media -->
            <div class="col-md-3 footer-social">
                <h5 class="text-uppercase mb-4">Social Media</h5>
                <a href="link_to_facebook" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                <a href="link_to_linkedin" class="text-white me-2"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <!-- Contact Button -->
            <div class="col-md-2 footer-contact align-self-center">
                <button class="btn btn-primary contact-button">Contact Us</button>
            </div>
        </div>
    </div>
</footer>


<!-- Modal -->
<div class="modal fade" id="imageUploadModal" tabindex="-1" aria-labelledby="imageUploadModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageUploadModalLabel">Upload Image</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="file" id="modalImageUpload" multiple>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="modalSaveImage">Upload Image</button>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
<script src="main.js"></script>
<script src="core/js/edit_blocks.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
