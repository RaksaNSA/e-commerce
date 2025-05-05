<?php
session_start();

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php'; // $pdo should be defined here

// Redirect if cart is empty
if (empty($_SESSION['cart'])) {
    $_SESSION['cart_message'] = [
        'type' => 'warning',
        'text' => 'Your cart is empty. Please add items before checking out.'
    ];
    header('Location: ' . SITE_URL . '/cart.php');
    exit;
}

// Totals calculation
$subtotal = 0;
$shipping = 0;
$tax_rate = 0.07;
$tax = 0;
$total = 0;

foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$shipping = ($subtotal < 50) ? 10 : 0;
$tax = $subtotal * $tax_rate;
$total = $subtotal + $shipping + $tax;

// Save order to database using PDO
try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status, created_at) VALUES (?, ?, 'pending', NOW())");
    $stmt->execute([
        $_SESSION['user_id'] ?? null,
        $total
    ]);
    $order_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_name, price, quantity) VALUES (?, ?, ?, ?)");

    foreach ($_SESSION['cart'] as $item) {
        $stmt->execute([
            $order_id,
            $item['name'],
            $item['price'],
            $item['quantity']
        ]);
    }

    $pdo->commit();

    // Clear cart
    unset($_SESSION['cart']);

} catch (Exception $e) {
    $pdo->rollBack();
    die("Failed to process order: " . $e->getMessage());
}

$pageTitle = "Complete Your Payment";
include_once '../templates/header.php';
?>

<div class="untree_co-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h2 class="h3 mb-4">Scan to Pay with ABA</h2>
                <p class="mb-4">Please scan the QR code below using your ABA Mobile app to pay:</p>

                <h3 class="text-primary">Total: $<?php echo number_format($total, 2); ?></h3>

                <!-- Static or dynamic QR code -->
                <div class="my-4">
                    <!-- Replace with your dynamic QR or keep static -->
                    <img src="<?php echo SITE_URL; ?>/assets/image/aba-qr-placeholder.png" alt="ABA QR Code" class="img-fluid" style="max-width: 300px;">
                </div>

                <p class="text-muted">Once paid, please confirm or wait for payment verification.</p>

                <a href="<?php echo SITE_URL; ?>/pages/order-confirmation.php?order_id=<?php echo $order_id; ?>" class="btn btn-primary">I Have Paid</a>
            </div>
        </div>
    </div>
</div>

<?php include_once '../templates/footer.php'; ?>
