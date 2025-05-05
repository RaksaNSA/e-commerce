<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if product_id and quantity were submitted
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    
    // Validate quantity
    if ($quantity <= 0) {
        $quantity = 1;
    }
    
    // Fetch product from database to ensure it exists and get current price
    try {
        $stmt = $pdo->prepare('SELECT id, name, price, image, stock FROM products WHERE id = ?');
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // If product exists
        if ($product) {
            // Check if stock is available
            if ($product['stock'] >= $quantity) {
                // Check if product already exists in cart
                $product_exists = false;
                foreach ($_SESSION['cart'] as $key => $item) {
                    if ($item['id'] == $product_id) {
                        // Update quantity
                        $new_quantity = $item['quantity'] + $quantity;
                        
                        // Make sure it doesn't exceed available stock
                        if ($new_quantity > $product['stock']) {
                            $new_quantity = $product['stock'];
                        }
                        
                        $_SESSION['cart'][$key]['quantity'] = $new_quantity;
                        $product_exists = true;
                        break;
                    }
                }
                
                // If product doesn't exist in cart, add it
                if (!$product_exists) {
                    $_SESSION['cart'][] = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'quantity' => $quantity,
                        'image' => $product['image']
                    ];
                }
                
                // Set success message
                $_SESSION['cart_message'] = [
                    'type' => 'success',
                    'text' => $product['name'] . ' added to your cart!'
                ];
            } else {
                // Not enough stock
                $_SESSION['cart_message'] = [
                    'type' => 'danger',
                    'text' => 'Sorry, there are only ' . $product['stock'] . ' items available.'
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
    // Invalid request
    $_SESSION['cart_message'] = [
        'type' => 'danger',
        'text' => 'Invalid request!'
    ];
}

// Calculate total items in cart for header display
$_SESSION['cart_count'] = 0;
foreach ($_SESSION['cart'] as $item) {
    $_SESSION['cart_count'] += $item['quantity'];
}

// Redirect back to previous page or to cart page
$redirect_to = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : SITE_URL . '/pages/cart.php';
header('Location: ' . $redirect_to);
exit;
?>