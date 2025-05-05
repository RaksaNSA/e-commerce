<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../templates/navegation.php';

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Calculate totals
$subtotal = 0;
$shipping = 0;
$tax_rate = 0.07; // 7% tax rate
$tax = 0;
$total = 0;

foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

// Calculate shipping based on subtotal
if ($subtotal > 0) {
    $shipping = ($subtotal < 50) ? 10 : 0; // Free shipping for orders over $50
}

$tax = $subtotal * $tax_rate;
$total = $subtotal + $shipping + $tax;

$pageTitle = 'Shopping Cart';
include_once '../templates/header.php';
?>

<div class="untree_co-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="border p-4 rounded" role="alert">
                    Returning customer? <a href="<?php echo SITE_URL; ?>/pages/login.php">Click here</a> to login
                </div>
            </div>
        </div>
        
        <!-- Display cart messages if any -->
        <?php if (isset($_SESSION['cart_message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['cart_message']['type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['cart_message']['text']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['cart_message']); ?>
        <?php endif; ?>
        
        <!-- Cart Content -->
        <div class="row">
            <div class="col-md-12 mb-5 mb-md-0">
                <h2 class="h3 mb-4 text-black">Your Cart</h2>
                
                <?php if (empty($_SESSION['cart'])): ?>
                    <div class="p-4 bg-light rounded">
                        <p class="mb-0">Your cart is empty.</p>
                        <p class="mt-2">
                            <a href="<?php echo SITE_URL; ?>" class="btn btn-primary">Continue Shopping</a>
                        </p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="product-thumbnail">Image</th>
                                    <th class="product-name">Product</th>
                                    <th class="product-price">Price</th>
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-total">Total</th>
                                    <th class="product-remove">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                                <tr>
                                    <td class="product-thumbnail">
                                        <img src="<?php echo SITE_URL; ?>/assets/image/products/<?php echo $item['image']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="img-fluid" style="max-width: 80px;">
                                    </td>
                                    <td class="product-name">
                                        <h2 class="h5 text-black"><?php echo htmlspecialchars($item['name']); ?></h2>
                                    </td>
                                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                                    <td>
                                        <form action="<?php echo SITE_URL; ?>/cart/update.php" method="post" class="d-flex align-items-center">
                                            <input type="hidden" name="index" value="<?php echo $index; ?>">
                                            <div class="input-group quantity-selector" style="max-width: 120px;">
                                                <button type="button" class="btn btn-outline-black quantity-minus">-</button>
                                                <input type="number" name="quantity" class="form-control text-center quantity" value="<?php echo $item['quantity']; ?>" min="1">
                                                <button type="button" class="btn btn-outline-black quantity-plus">+</button>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-outline-secondary ms-2">Update</button>
                                        </form>
                                    </td>
                                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    <td>
                                        <a href="<?php echo SITE_URL; ?>/cart/remove.php?index=<?php echo $index; ?>" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Cart Actions -->
                    <div class="d-flex justify-content-between mt-4">
                        <div>
                            <a href="<?php echo SITE_URL; ?>" class="btn btn-outline-primary">Continue Shopping</a>
                        </div>
                        <div>
                            <form action="<?php echo SITE_URL; ?>/cart/clear.php" method="post">
                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to clear your cart?');">Clear Cart</button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (!empty($_SESSION['cart'])): ?>
        <!-- Order Summary -->
        <div class="row mt-5">
            <div class="col-md-6 offset-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="h5 mb-0">Order Summary</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td>Subtotal</td>
                                    <td class="text-end">$<?php echo number_format($subtotal, 2); ?></td>
                                </tr>
                                <tr>
                                    <td>Shipping</td>
                                    <td class="text-end">
                                        <?php if ($shipping > 0): ?>
                                            $<?php echo number_format($shipping, 2); ?>
                                        <?php else: ?>
                                            <span class="text-success">Free</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tax (<?php echo $tax_rate * 100; ?>%)</td>
                                    <td class="text-end">$<?php echo number_format($tax, 2); ?></td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <th class="text-end">$<?php echo number_format($total, 2); ?></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <a href="<?php echo SITE_URL; ?>/pages/checkout.php" class="btn btn-primary w-100">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity selector functionality
    const quantityInputs = document.querySelectorAll('.quantity');
    const minusButtons = document.querySelectorAll('.quantity-minus');
    const plusButtons = document.querySelectorAll('.quantity-plus');
    
    for (let i = 0; i < minusButtons.length; i++) {
        minusButtons[i].addEventListener('click', function() {
            const input = this.parentNode.querySelector('input');
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        });
    }
    
    for (let i = 0; i < plusButtons.length; i++) {
        plusButtons[i].addEventListener('click', function() {
            const input = this.parentNode.querySelector('input');
            const currentValue = parseInt(input.value);
            input.value = currentValue + 1;
        });
    }
});
</script>

<?php include_once '../templates/footer.php'; ?>