<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Simulate product data (normally you'd query from DB)
$products = [
    2 => ['name' => 'Nordic Chair', 'price' => 50.00]
];

// Handle add to cart
if (isset($_GET['action']) && $_GET['action'] === 'add') {
    $id = intval($_GET['id']);

    if (isset($products[$id])) {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $products[$id]['name'],
                'price' => $products[$id]['price'],
                'quantity' => 1
            ];
        }
    }
}

// Display cart
echo '<h3>Your Cart:</h3>';
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $item) {
        echo "<p>{$item['name']} x {$item['quantity']} = $" . ($item['price'] * $item['quantity']) . "</p>";
    }
} else {
    echo "<p>Your cart is empty.</p>";
}
