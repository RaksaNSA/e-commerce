<?php require_once __DIR__ . '/../includes/config.php'; ?>
	<?php $pageTitle = '';?>
<?php include 'header.php'?>
		<!-- Start Hero Section -->
        <div class="hero">
				<div class="container">
					<div class="row justify-content-between">
						<div class="col-lg-5">
							<div class="intro-excerpt">
								<?php $page = SITE_NAME?>
								<h1><?php echo $pageTitle?> <span clsas="d-block"></span></h1>
								<p class="mb-4">Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique.</p>
								<p><a href="<?php echo SITE_URL?>./pages/product.php" class="btn btn-secondary me-2">Shop Now</a><a href="#" class="btn btn-white-outline">Explore</a></p>
							</div>
						</div>
						<div class="col-lg-7">
							<div class="hero-img-wrap">
								<img src="<?php echo SITE_URL?>./assets/image/products/couch.png" class="img-fluid">
							</div>
						</div>
					</div>
				</div>
		</div>
		<!-- End Hero Section -->