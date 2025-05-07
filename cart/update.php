<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

// Check if index and quantity were submitted
if (isset($_POST['index']) && isset($_POST['quantity'])) {
    $index = (int)$_POST['index'];
    $quantity = (int)$_POST['quantity'];
    
    // Validate quantity
    if ($quantity <= 0) {
        $quantity = 1;
    }
    
    // Check if index exists in cart
    if (isset($_SESSION['cart'][$index])) {
        $product_id = $_SESSION['cart'][$index]['id'];
        
        // Fetch product from database to check stock
        try {
            $stmt = $pdo->prepare('SELECT stock FROM products WHERE id = ?');
            $stmt->execute([$product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // If product exists
            if ($product) {
                // Check if stock is available
                if ($product['stock'] >= $quantity) {
                    // Update quantity
                    $_SESSION['cart'][$index]['quantity'] = $quantity;
                    
                    // Set success message
                    $_SESSION['cart_message'] = [
                        'type' => 'success',
                        'text' => 'Cart updated successfully!'
                    ];
                } else {
                    // Not enough stock
                    $_SESSION['cart'][$index]['quantity'] = $product['stock'];
                    
                    $_SESSION['cart_message'] = [
                        'type' => 'warning',
                        'text' => 'Only ' . $product['stock'] . ' items available. Your cart has been updated.'
                    ];
                }
            } else {
                // Product not found
                $_SESSION['cart_message'] = [
                    'type' => 'danger',
                    'text' => 'Product not found!'
                ];
            }
        } catch (PDOException $e) {
            // Database error
            $_SESSION['cart_message'] = [
                'type' => 'danger',
                'text' => 'Database error. Please try again later.'
            ];
        }
    } else {
        // Index not found in cart
        $_SESSION['cart_message'] = [
            'type' => 'danger',
            'text' => 'Cart item not found!'
        ];
    }
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