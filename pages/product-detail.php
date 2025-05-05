<?php 
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/navegation.php';

// Check if product ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Redirect to home page if no valid ID is provided
    header('Location: ' . SITE_URL);
    exit;
}

$product_id = (int)$_GET['id'];

// Fetch product details from database
try {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // If no product found with that ID
    if (!$product) {
        header('Location: ' . SITE_URL);
        exit;
    }
    
    // You might want to fetch additional product data like features, gallery, etc.
    // For now, we'll use sample data similar to your paste-2.txt
    
    // Example: Adding features if they don't exist in the database
    if (!isset($product['features']) || empty($product['features'])) {
        $product['features'] = [
            'Solid oak construction',
            'Ergonomic design',
            'Stain-resistant fabric',
            'Available in multiple colors',
            'Dimensions: 60cm x 55cm x 82cm (W x D x H)'
        ];
    }
    
    // Example: Adding gallery images if they don't exist
    if (!isset($product['gallery']) || empty($product['gallery'])) {
        $product['gallery'] = [
            'product-1.png',
            'product-2.png',
            'product-3.png'
        ];
    }
    
    $pageTitle = $product['name'] . ' - Product Details';
} catch (PDOException $e) {
    // Handle database error
    die("Database Error: " . $e->getMessage());
}

include_once '../templates/header.php';
?>

<div class="untree_co-section product-detail-section">
    <div class="container">
        <div class="row">
            <!-- Product Images -->
            <div class="col-12 col-md-6 mb-5">
                <div class="product-detail-image mb-4">
                    <img src="<?php echo SITE_URL; ?>/assets/image/products/<?php echo $product['image']; ?>" class="img-fluid main-product-image" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                
                <!-- Product Gallery -->
                <div class="product-thumbnail-gallery d-flex">
                    <?php foreach($product['gallery'] as $galleryImage): ?>
                    <div class="thumbnail-item me-2">
                        <img src="<?php echo SITE_URL; ?>/assets/image/products/<?php echo $galleryImage; ?>" class="img-fluid product-thumbnail" alt="Product image">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Product Details -->
            <div class="col-12 col-md-6 ps-md-5">
                <h2 class="product-title mb-3"><?php echo htmlspecialchars($product['name']); ?></h2>
                
                <div class="product-price-wrapper mb-4">
                    <strong class="product-price h4">$<?php echo number_format($product['price'], 2); ?></strong>
                </div>
                
                <div class="product-description mb-4">
                    <p><?php echo htmlspecialchars($product['description'] ?? 'No description available.'); ?></p>
                </div>
                
                <!-- Product Features -->
                <div class="product-features mb-4">
                    <h4>Features</h4>
                    <ul class="list-unstyled">
                        <?php foreach($product['features'] as $feature): ?>
                        <li><i class="bi bi-check2"></i> <?php echo htmlspecialchars($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Product Meta -->
                <div class="product-meta mb-4">
                    <div class="stock-info mb-2">
                        <?php if(isset($product['stock']) && $product['stock'] > 0): ?>
                        <span class="text-success">In Stock (<?php echo $product['stock']; ?> available)</span>
                        <?php else: ?>
                        <span class="text-danger">Out of Stock</span>
                        <?php endif; ?>
                    </div>
                    <div class="category-info">
                        <span>Category: </span>
                        <a href="<?php echo SITE_URL; ?>/shop.php?category=<?php echo urlencode($product['category'] ?? 'Uncategorized'); ?>"><?php echo htmlspecialchars($product['category'] ?? 'Uncategorized'); ?></a>
                    </div>
                </div>
                
                <!-- Product Actions -->
                <div class="product-actions mb-5">
                    <form action="<?php echo SITE_URL; ?>/cart/add.php" method="post" class="product-form">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        
                        <div class="row g-3 align-items-center mb-4">
                            <!-- Quantity Selector -->
                            <div class="col-auto">
                                <label for="quantity" class="visually-hidden">Quantity</label>
                                <div class="input-group quantity-selector">
                                    <button type="button" class="btn btn-outline-black quantity-minus">-</button>
                                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['stock'] ?? 10; ?>" class="form-control text-center">
                                    <button type="button" class="btn btn-outline-black quantity-plus">+</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-block">
                            <button type="submit" class="btn btn-primary btn-lg" <?php echo (isset($product['stock']) && $product['stock'] <= 0) ? 'disabled' : ''; ?>>
                                Add to Cart
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-lg add-to-wishlist">
                                <i class="bi bi-heart"></i> Add to Wishlist
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Product Share -->
                <div class="product-share">
                    <h5>Share this product:</h5>
                    <div class="social-share">
                        <a href="#" class="me-2"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="me-2"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="me-2"><i class="bi bi-pinterest"></i></a>
                        <a href="#" class="me-2"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Details Tabs -->
        <div class="row mt-5">
            <div class="col-12">
                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab" aria-controls="specifications" aria-selected="false">Specifications</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews</button>
                    </li>
                </ul>
                <div class="tab-content p-4 border border-top-0" id="productTabsContent">
                    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                        <h4>Product Description</h4>
                        <p><?php echo htmlspecialchars($product['description'] ?? 'The '.$product['name'].' combines elegant design with practical comfort. Each piece is crafted with attention to detail, ensuring quality and longevity.'); ?></p>
                        <p>The minimalist design fits perfectly in both modern and traditional interiors, making it a versatile addition to any home.</p>
                    </div>
                    <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                        <h4>Technical Specifications</h4>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Materials</th>
                                    <td><?php echo htmlspecialchars($product['materials'] ?? 'Premium quality materials'); ?></td>
                                </tr>
                                <tr>
                                    <th>Dimensions</th>
                                    <td><?php echo htmlspecialchars($product['dimensions'] ?? 'Please refer to product description'); ?></td>
                                </tr>
                                <tr>
                                    <th>Weight</th>
                                    <td><?php echo htmlspecialchars($product['weight'] ?? 'Please refer to product description'); ?></td>
                                </tr>
                                <tr>
                                    <th>Assembly</th>
                                    <td><?php echo htmlspecialchars($product['assembly'] ?? 'Easy assembly required, tools included'); ?></td>
                                </tr>
                                <tr>
                                    <th>Warranty</th>
                                    <td><?php echo htmlspecialchars($product['warranty'] ?? '2 years limited warranty'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                        <h4>Customer Reviews</h4>
                        
                        <!-- Sample Reviews Section -->
                        <div class="customer-reviews">
                            <!-- Review Item -->
                            <div class="review-item border-bottom pb-4 mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <h5 class="mb-0">Great design and comfort</h5>
                                    <div class="review-stars">
                                        ★★★★★ <span class="text-muted">(5/5)</span>
                                    </div>
                                </div>
                                <p class="review-author text-muted small">By Sarah T. on March 15, 2025</p>
                                <p class="review-text">I absolutely love this product! The design is sleek and modern, and it fits perfectly with my home decor.</p>
                            </div>
                            
                            <!-- Write a Review Form -->
                            <div class="write-review mt-5">
                                <h5>Write a Review</h5>
                                <form action="<?php echo SITE_URL; ?>/product/review.php" method="post" class="review-form mt-3">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    
                                    <div class="mb-3">
                                        <label for="reviewName" class="form-label">Your Name</label>
                                        <input type="text" class="form-control" id="reviewName" name="name" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="reviewEmail" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="reviewEmail" name="email" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Rating</label>
                                        <div class="rating-selector">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="rating" id="rating5" value="5" checked>
                                                <label class="form-check-label" for="rating5">5 stars</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="rating" id="rating4" value="4">
                                                <label class="form-check-label" for="rating4">4 stars</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="rating" id="rating3" value="3">
                                                <label class="form-check-label" for="rating3">3 stars</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="rating" id="rating2" value="2">
                                                <label class="form-check-label" for="rating2">2 stars</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="rating" id="rating1" value="1">
                                                <label class="form-check-label" for="rating1">1 star</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="reviewTitle" class="form-label">Review Title</label>
                                        <input type="text" class="form-control" id="reviewTitle" name="title" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="reviewBody" class="form-label">Your Review</label>
                                        <textarea class="form-control" id="reviewBody" name="body" rows="5" required></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">Submit Review</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Products -->
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="section-title mb-4">You May Also Like</h3>
                
                <?php
                // Get related products - you might want to adjust this query based on your needs
                try {
                    $category = $product['category'] ?? '';
                    $stmt = $pdo->prepare("SELECT * FROM products WHERE id != ? " . 
                                          ($category ? "AND category = ?" : "") . 
                                          " ORDER BY RAND() LIMIT 4");
                    
                    if ($category) {
                        $stmt->execute([$product_id, $category]);
                    } else {
                        $stmt->execute([$product_id]);
                    }
                    
                    $related_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    $related_products = [];
                }
                ?>
                
                <div class="row">
                    <?php if (!empty($related_products)): ?>
                        <?php foreach ($related_products as $related): ?>
                            <div class="col-6 col-md-3 mb-4">
                                <a class="product-item" href="<?php echo SITE_URL; ?>/pages/product-detail.php?id=<?php echo $related['id']; ?>">
                                    <img src="<?php echo SITE_URL; ?>/assets/image/products/<?php echo $related['image']; ?>" class="img-fluid product-thumbnail">
                                    <h3 class="product-title"><?php echo htmlspecialchars($related['name']); ?></h3>
                                    <strong class="product-price">$<?php echo number_format($related['price'], 2); ?></strong>
                                    <span class="icon-cross">
                                        <img src="<?php echo SITE_URL; ?>/assets/image/products/<?php echo $related['image']; ?>" class="img-fluid">
                                    </span>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Fallback related products if query returns no results -->
                        <div class="col-6 col-md-3 mb-4">
                            <a class="product-item" href="#">
                                <img src="<?php echo SITE_URL; ?>/assets/image/products/product-2.png" class="img-fluid product-thumbnail">
                                <h3 class="product-title">Kruzo Aero Chair</h3>
                                <strong class="product-price">$78.00</strong>
                                <span class="icon-cross">
                                    <img src="<?php echo SITE_URL; ?>/assets/image/products/product-2.png" class="img-fluid">
                                </span>
                            </a>
                        </div>
                        
                        <div class="col-6 col-md-3 mb-4">
                            <a class="product-item" href="#">
                                <img src="<?php echo SITE_URL; ?>/assets/image/products/product-3.png" class="img-fluid product-thumbnail">
                                <h3 class="product-title">Ergonomic Chair</h3>
                                <strong class="product-price">$43.00</strong>
                                <span class="icon-cross">
                                    <img src="<?php echo SITE_URL; ?>/assets/image/products/product-3.png" class="img-fluid">
                                </span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity selector functionality
    const quantityInput = document.getElementById('quantity');
    const minusButton = document.querySelector('.quantity-minus');
    const plusButton = document.querySelector('.quantity-plus');
    
    if (quantityInput && minusButton && plusButton) {
        minusButton.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });
        
        plusButton.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value);
            const maxValue = parseInt(quantityInput.getAttribute('max'));
            if (currentValue < maxValue) {
                quantityInput.value = currentValue + 1;
            }
        });
    }
    
    // Gallery image selection functionality
    const mainImage = document.querySelector('.main-product-image');
    const thumbnails = document.querySelectorAll('.thumbnail-item img');
    
    if (mainImage && thumbnails.length > 0) {
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                const newSrc = this.getAttribute('src');
                mainImage.setAttribute('src', newSrc);
            });
        });
    }
});
</script>

<?php include_once '../templates/footer.php'; ?>