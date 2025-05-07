<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/config.php';

// Clear the cart
$_SESSION['cart'] = [];
$_SESSION['cart_count'] = 0;

// Set success message
$_SESSION['cart_message'] = [
    'type' => 'success',
    'text' => 'Your cart has been cleared!'
];

// Redirect back to cart page
header('Location: ' . SITE_URL . '/pages/cart.php');
exit;
?>