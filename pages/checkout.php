<?php
session_start();

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php'; // $pdo should be defined here
require_once __DIR__ . '/../templates/navegation.php';
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

// Process payment if form submitted
$payment_processed = false;
$payment_error = '';
$payment_method = $_POST['payment_method'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_payment'])) {
    // Save order to database first
    try {
        $pdo->beginTransaction();

        // Set payment method in orders table
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, payment_method, status, created_at) 
                              VALUES (?, ?, ?, 'pending', NOW())");
        $stmt->execute([
            $_SESSION['user_id'] ?? null,
            $total,
            $payment_method
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

        // Process the payment based on selected method
        if ($payment_method === 'credit_card') {
            // In a real implementation, you would integrate with a payment processor here
            // like Stripe, PayPal, etc. This is a simplified example
            $card_number = $_POST['card_number'] ?? '';
            $card_expiry = $_POST['card_expiry'] ?? '';
            $card_cvv = $_POST['card_cvv'] ?? '';
            $card_name = $_POST['card_name'] ?? '';
            
            // Basic validation
            if (empty($card_number) || empty($card_expiry) || empty($card_cvv) || empty($card_name)) {
                throw new Exception("All card details are required");
            }
            
            // Strip spaces from card number
            $card_number = str_replace(' ', '', $card_number);
            
            // Basic card number validation (simple Luhn algorithm check)
            if (!validateCreditCard($card_number)) {
                throw new Exception("Invalid card number");
            }
            
            // In production, you would make an API call to a payment processor here
            // Simulating successful payment
            $payment_processed = true;
            
            // Update order status to paid
            $stmt = $pdo->prepare("UPDATE orders SET status = 'paid' WHERE id = ?");
            $stmt->execute([$order_id]);
            
        } elseif ($payment_method === 'aba') {
            // For ABA, we'll leave the status as 'pending' until manual confirmation
            $payment_processed = true; // This just means we've recorded the order
        } else {
            throw new Exception("Invalid payment method");
        }

        $pdo->commit();

        // If payment processed successfully
        if ($payment_processed) {
            // Clear cart
            unset($_SESSION['cart']);
            
            if ($payment_method === 'credit_card') {
                // Redirect to order confirmation for credit card payments
                header('Location: ' . SITE_URL . '/pages/order-confirmation.php?order_id=' . $order_id);
                exit;
            }
            // For ABA payments, we continue showing the QR code
        }

    } catch (Exception $e) {
        $pdo->rollBack();
        $payment_error = $e->getMessage();
    }
}

// Function to validate credit card using Luhn algorithm
function validateCreditCard($number) {
    // Remove non-digits
    $number = preg_replace('/\D/', '', $number);
    
    // Get length
    $length = strlen($number);
    
    // Check length (most cards are 13-19 digits)
    if ($length < 13 || $length > 19) {
        return false;
    }
    
    // Luhn algorithm
    $sum = 0;
    $double = false;
    
    for ($i = $length - 1; $i >= 0; $i--) {
        $digit = (int)$number[$i];
        
        if ($double) {
            $digit *= 2;
            if ($digit > 9) {
                $digit -= 9;
            }
        }
        
        $sum += $digit;
        $double = !$double;
    }
    
    return ($sum % 10) === 0;
}

$pageTitle = "Complete Your Payment";
include_once '../templates/header.php';
?>

<div class="untree_co-section">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-5 mb-md-0">
                <h2 class="h3 mb-3">Billing Details</h2>
                
                <?php if ($payment_error): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($payment_error); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="https://buy.stripe.com/test_aEU4gA4FJ7VFcH66oo" class="p-3 p-lg-5 border bg-white">
                    <div class="p-3 p-lg-5 border bg-white">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="text-black">Payment Method <span class="text-danger">*</span></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card" checked>
                                    <label class="form-check-label" for="credit_card">
                                        Credit Card
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="aba" value="aba">
                                    <label class="form-check-label" for="aba">
                                        ABA Payment
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Credit Card Details - Initially visible -->
                        <div id="credit_card_details">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="card_name" class="text-black">Name on Card <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="card_name" name="card_name">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="card_number" class="text-black">Card Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="card_number" name="card_number" placeholder="XXXX XXXX XXXX XXXX">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="card_expiry" class="text-black">Expiry Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="card_expiry" name="card_expiry" placeholder="MM/YY">
                                </div>
                                <div class="col-md-6">
                                    <label for="card_cvv" class="text-black">CVV <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="card_cvv" name="card_cvv" placeholder="123">
                                </div>
                            </div>
                        </div>
                        
                        <!-- ABA Payment Details - Initially hidden -->
                        <div id="aba_payment_details" style="display: none;">
                            <div class="form-group">
                                <p class="text-muted">After submitting, you'll be shown a QR code to scan with your ABA Mobile app.</p>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="submit" name="process_payment" class="btn btn-primary btn-lg btn-block">Process Payment</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="col-md-6">
                <div class="row mb-5">
                    <div class="col-md-12">
                        <h2 class="h3 mb-3 text-black">Your Order</h2>
                        <div class="p-3 p-lg-5 border bg-white">
                            <table class="table site-block-order-table mb-5">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['cart'] as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['name']); ?> <strong class="mx-2">x</strong> <?php echo $item['quantity']; ?></td>
                                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td class="text-black font-weight-bold"><strong>Cart Subtotal</strong></td>
                                        <td class="text-black">$<?php echo number_format($subtotal, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-black font-weight-bold"><strong>Shipping</strong></td>
                                        <td class="text-black">$<?php echo number_format($shipping, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-black font-weight-bold"><strong>Tax</strong></td>
                                        <td class="text-black">$<?php echo number_format($tax, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-black font-weight-bold"><strong>Order Total</strong></td>
                                        <td class="text-black font-weight-bold"><strong>$<?php echo number_format($total, 2); ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if ($payment_method === 'aba' && $payment_processed): ?>
        <!-- Show ABA QR Code after selecting ABA payment and submitting -->
        <div class="row justify-content-center mt-5">
            <div class="col-md-8 text-center">
                <h2 class="h3 mb-4">Scan to Pay with ABA</h2>
                <p class="mb-4">Please scan the QR code below using your ABA Mobile app to pay:</p>

                <h3 class="text-primary">Total: $<?php echo number_format($total, 2); ?></h3>

                <!-- Static or dynamic QR code -->
                <div class="my-4">
                    <!-- Replace with your dynamic QR or keep static -->
                    <img src="<?php echo SITE_URL; ?>/assets/images/aba-qr-placeholder.png" alt="ABA QR Code" class="img-fluid" style="max-width: 300px;">
                </div>

                <p class="text-muted">Once paid, please confirm or wait for payment verification.</p>

                <a href="<?php echo SITE_URL; ?>/pages/order-confirmation.php?order_id=<?php echo $order_id; ?>" class="btn btn-primary">I Have Paid</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get radio buttons and detail sections
    const creditCardRadio = document.getElementById('credit_card');
    const abaRadio = document.getElementById('aba');
    const creditCardDetails = document.getElementById('credit_card_details');
    const abaPaymentDetails = document.getElementById('aba_payment_details');
    
    // Function to toggle payment details based on selection
    function togglePaymentDetails() {
        if (creditCardRadio.checked) {
            creditCardDetails.style.display = 'block';
            abaPaymentDetails.style.display = 'none';
        } else if (abaRadio.checked) {
            creditCardDetails.style.display = 'none';
            abaPaymentDetails.style.display = 'block';
        }
    }
    
    // Set initial state
    togglePaymentDetails();
    
    // Add event listeners
    creditCardRadio.addEventListener('change', togglePaymentDetails);
    abaRadio.addEventListener('change', togglePaymentDetails);
    
    // Format credit card number with spaces
    const cardNumberInput = document.getElementById('card_number');
    cardNumberInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 0) {
            value = value.match(new RegExp('.{1,4}', 'g')).join(' ');
        }
        e.target.value = value;
    });
    
    // Format expiry date
    const expiryInput = document.getElementById('card_expiry');
    expiryInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        e.target.value = value;
    });
    
    // Limit CVV to 3-4 digits
    const cvvInput = document.getElementById('card_cvv');
    cvvInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        e.target.value = value.substring(0, 4);
    });
});
</script>

<?php include_once '../templates/footer.php'; ?>