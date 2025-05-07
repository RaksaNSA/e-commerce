<?php
include_once __DIR__ . '/../includes/config.php';
include_once __DIR__ . '/../includes/db.php';
// Sandbox endpoint
$endpoint = "https://payway-staging.ababank.com/api/purchase";

// Your ABA sandbox credentials
$merchant_id = "ec460198";
$api_key = "https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/purchase";

// Payment details
$invoice = uniqid('INV-');
$amount = 1.00; // USD
$firstname = "Test";
$lastname = "User";
$phone = "012345678";
$email = "test@example.com";
$return_url = "<?php echo SITE_URL; ?>/pages/cart.php"; // URL to redirect after payment
$cancel_url = "<?php echo SITE_URL; ?>/pages/cart.php"; // URL to redirect if payment is cancelled

$data = [
    "merchant_id" => $merchant_id,
    "tran_id" => $invoice,
    "amount" => number_format($amount, 2, '.', ''),
    "firstname" => $firstname,
    "lastname" => $lastname,
    "phone" => $phone,
    "email" => $email,
    "return_url" => $return_url,
    "timeout" => 60, // Optional: seconds
];

// Sort and build hash
ksort($data);
$hash_data = http_build_query($data);
$hash = hash_hmac('sha256', $hash_data, $api_key);

// Add hash to data
$data['hash'] = $hash;

// Send request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

$response = curl_exec($ch);
curl_close($ch);

// Parse response
$result = json_decode($response, true);

if ($result && isset($result['payment_url'])) {
    // Redirect to ABA payment gateway
    header("Location: " . $result['payment_url']);
    exit();
} else {
    echo "Error creating payment. Response: ";
    print_r($result);
}
?>
