<?php 
global $pdo;
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ .'/../templates/header.php'; 
require_once __DIR__ . '/../templates/navegation.php';
require_once __DIR__ . '/../templates/hero_section.php';
?>

<?php 
    // Fetch all products from the database
    $stmt = $pdo->query('SELECT * FROM products');
    $products = $stmt->fetchAll();
    $pageTitle = 'Home';
    
?>

<div class="untree_co-section product-section before-footer-section">
    <div class="container">
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-12 col-md-4 col-lg-3 mb-5">
                    <a class="product-item" href="<?php echo SITE_URL; ?>/pages/product-detail.php?id=<?php echo $product['id']; ?>">
                        <img src="<?php echo SITE_URL; ?>/assets/image/products/<?php echo $product['image']; ?>" class="img-fluid product-thumbnail" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <strong class="product-price">$<?php echo number_format($product['price'], 2); ?></strong>
                        <span class="icon-cross">
                            <img src="<?php echo SITE_URL; ?>/assets/image/products/<?php echo $product['image']; ?>" class="img-fluid">
                        </span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
