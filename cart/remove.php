<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/config.php';

// Check if index was submitted
if (isset($_GET['index']) && isset($_SESSION['cart'][$_GET['index']])) {
    $index = (int)$_GET['index'];
    $product_name = $_SESSION['cart'][$index]['name'];
    
    // Remove item from cart
    unset($_SESSION['cart'][$index]);
    
    // Reindex array
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    
    // Set success message
    $_SESSION['cart_message'] = [
        'type' => 'success',
        'text' => $product_name . ' removed from your cart!'
    ];
}

// Calculate total items in cart for header display
$_SESSION['cart_count'] = 0;
foreach ($_SESSION['cart'] as $item) {
    $_SESSION['cart_count'] += $item['quantity'];
}

// Redirect back to cart page
header('Location: ' . SITE_URL . '/pages/cart.php');
exit;
?>