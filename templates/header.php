<?php require_once __DIR__ . '/../includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' . SITE_NAME : SITE_NAME; ?></title>

        <!-- Site CSS -->
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
        <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/tiny-slider.css">

        <!-- Bootstrap CSS (from CDN or local if you move it into assets) -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    </head>
<body>
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
        <li><a class="nav-link" href="cart.html"><i class="fa-solid fa-cart-shopping"></i></a></li>
    </ul>
</div>
</div>

    </nav>
<!-- End Header/Navigation -->