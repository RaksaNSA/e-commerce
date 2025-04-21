<?php require_once __DIR__ . '/../includes/config.php'; ?>
<?php
require_once __DIR__ . '/../includes/db.php';
?>

<?php 
	$stmt = $pdo->query("SELECT * FROM products");
	$products = $stmt->fetchAll();
?>

<?php $pageTitle = 'Home'; ?>
<?php include_once '../templates/header.php'; ?>
<div class="untree_co-section product-section before-footer-section">
		    <div class="container">
				
				
				<div class="row">
				<?php foreach ($products as $product): ?>
					<div class="col-md-4 mb-4">
						<a class="product-item" href="index.php?page=product&id=<?php echo $product['id']; ?>">
						<img src="<?php echo SITE_URL; ?>./assets/image/products/<?php echo $product['image']; ?>" class="img-fluid">
							<h3 class="product-title"><?php echo $product['name']; ?></h3>
							<strong class="product-price">$<?php echo $product['price']; ?></strong>
						</a>
					</div>
				<?php endforeach; ?>
				</div>

		      	<div class="row">

		      		<!-- Start Column 1 -->
					<div class="col-12 col-md-4 col-lg-3 mb-5">
						<a class="product-item" href="<?php echo SITE_URL?>./pages/product-detail.php">
							<img src="<?php echo SITE_URL?>./assets/image/products/product-1.png" class="img-fluid product-thumbnail">
							<h3 class="product-title">Nordic Chair</h3>
							<strong class="product-price">$50.00</strong>

							<span class="icon-cross">
								<img src="<?php echo SITE_URL?>./assets/image/products/product-1.png" class="img-fluid">
							</span>
						</a>
					</div> 
					<!-- End Column 1 -->
						
					<!-- Start Column 2 -->
					<div class="col-12 col-md-4 col-lg-3 mb-5">
						<a class="product-item" href="<?php echo SITE_URL?>./pages/product-detail.php">
							<img src="<?php echo SITE_URL?>./assets/image/products/product-2.png" class="img-fluid product-thumbnail">
							<h3 class="product-title">Nordic Chair</h3>
							<strong class="product-price">$50.00</strong>

							<span class="icon-cross">
								<img src="<?php echo SITE_URL?>./assets/image/products/product-2.png" class="img-fluid">
							</span>
						</a>
					</div> 
					<!-- End Column 2 -->

					<!-- Start Column 3 -->
					<div class="col-12 col-md-4 col-lg-3 mb-5">
						<a class="product-item" href="<?php echo SITE_URL?>./pages/product-detail.php">
                            <img src="<?php echo SITE_URL?>./assets/image/products/product-3.png" class="img-fluid product-thumbnail">
							<h3 class="product-title">Kruzo Aero Chair</h3>
							<strong class="product-price">$78.00</strong>

							<span class="icon-cross">
								<img src="<?php echo SITE_URL?>./assets/image/products/product-3.png" class="img-fluid">
							</span>
						</a>
					</div>
					<!-- End Column 3 -->

					<!-- Start Column 4 -->
					<div class="col-12 col-md-4 col-lg-3 mb-5">
						<a class="product-item" href="<?php echo SITE_URL?>./pages/product-detail.php">
                        <img src="<?php echo SITE_URL?>./assets/image/products/product-1.png" class="img-fluid product-thumbnail">
							<h3 class="product-title">Ergonomic Chair</h3>
							<strong class="product-price">$43.00</strong>

							<span class="icon-cross">
								<img src="<?php echo SITE_URL?>./assets/image/products/product-1.png" class="img-fluid">
							</span>
						</a>
					</div>
					<!-- End Column 4 -->


					<!-- Start Column 1 -->
					<div class="col-12 col-md-4 col-lg-3 mb-5">
						<a class="product-item" href="<?php echo SITE_URL?>./pages/product-detail.php">
							<img src="<?php echo SITE_URL?>./assets/image/products/product-1.png" class="img-fluid product-thumbnail">
							<h3 class="product-title">Nordic Chair</h3>
							<strong class="product-price">$50.00</strong>

							<span class="icon-cross">
								<img src="<?php echo SITE_URL?>./assets/image/products/product-1.png" class="img-fluid">
							</span>
						</a>
					</div> 
					<!-- End Column 1 -->
						
					<!-- Start Column 2 -->
					<div class="col-12 col-md-4 col-lg-3 mb-5">
						<a class="product-item" href="<?php echo SITE_URL?>./pages/product-detail.php">
							<img src="<?php echo SITE_URL?>./assets/image/products/product-2.png" class="img-fluid product-thumbnail">
							<h3 class="product-title">Nordic Chair</h3>
							<strong class="product-price">$50.00</strong>

							<span class="icon-cross">
								<img src="<?php echo SITE_URL?>./assets/image/products/product-2.png" class="img-fluid">
							</span>
						</a>
					</div> 
					<!-- End Column 2 -->

					<!-- Start Column 3 -->
					<div class="col-12 col-md-4 col-lg-3 mb-5">
						<a class="product-item" href="<?php echo SITE_URL?>./pages/product-detail.php">
							<img src="<?php echo SITE_URL?>./assets/image/products/product-3.png" class="img-fluid product-thumbnail">
							<h3 class="product-title">Kruzo Aero Chair</h3>
							<strong class="product-price">$78.00</strong>

							<span class="icon-cross">
								<img src="<?php echo SITE_URL?>./assets/image/products/product-3.png" class="img-fluid">
							</span>
						</a>
					</div>
					<!-- End Column 3 -->

					<!-- Start Column 4 -->
					<div class="col-12 col-md-4 col-lg-3 mb-5">
						<a class="product-item" href="<?php echo SITE_URL?>./pages/product-detail.php">
							<img src="<?php echo SITE_URL?>./assets/image/products/product-1.png" class="img-fluid product-thumbnail">
							<h3 class="product-title">Ergonomic Chair</h3>
							<strong class="product-price">$43.00</strong>

							<span class="icon-cross">
								<img src="<?php echo SITE_URL?>./assets/image/products/product-1.png" class="img-fluid">
							</span>
						</a>
					</div>
					<!-- End Column 4 -->

		      	</div>
				
		    </div>
</div>
<?php include_once '../templates/footer.php'; ?>