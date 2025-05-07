<?php 
    require_once __DIR__ . '/../includes/config.php';
    require_once 'header.php';
?>
<!-- Start Header/Navigation -->
<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark" arial-label="Furni navigation bar">

<div class="container">
<a class="navbar-brand" href="<?php echo SITE_URL?>"><img src="<?php echo SITE_URL; ?>./assets/image/logos/logo.png" alt="logo"></a>

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbarsFurni">
    <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
        <li class="nav-item ">
            <a class="nav-link" href="<?php echo SITE_URL?>">Home</a>
        </li>
        <li><a class="nav-link" href="<?php echo SITE_URL; ?>/pages/product.php">Shop</a></li>
        <li><a class="nav-link" href="<?php echo SITE_URL; ?>/pages/about_us.php">About us</a></li>
        <li><a class="nav-link" href="<?php echo SITE_URL; ?>/pages/service.php">Services</a></li>
        <li><a class="nav-link" href="<?php echo SITE_URL; ?>/pages/blog.php">Blog</a></li>
        <li><a class="nav-link" href="<?php echo SITE_URL; ?>/pages/contact_us.php">Contact us</a></li>
    </ul>

    <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
        <li><a class="nav-link" href="#"><i class="fa-solid fa-user"></i></a></li>
        <li><a class="nav-link" href="<?php echo SITE_URL; ?>/pages/cart.php"><i class="fa-solid fa-cart-shopping"><span><?php ?> </span></i></a></li>
    </ul>
</div>
</div>

    </nav>
<!-- End Header/Navigation -->