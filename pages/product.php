<?php
// Include configuration and database connection
require_once '../includes/config.php';
// require_once '../includes/db.php';

// Get products from database
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);
?>

<?php include '../templates/header.php'; ?>

<main class="product-listing">
    <h1>Our Products</h1>
    
    <div class="products-container">
        <?php while($product = mysqli_fetch_assoc($result)): ?>
            <div class="product-card">
                <img src="../assets/images/products/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                <h3><?php echo $product['name']; ?></h3>
                <p class="price">$<?php echo $product['price']; ?></p>
                <button class="add-to-cart" data-product-id="<?php echo $product['id']; ?>">Add to Cart</button>
            </div>
        <?php endwhile; ?>
    </div>
</main>

<?php include '../templates/footer.php'; ?>