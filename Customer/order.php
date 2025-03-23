<?php
require 'vendor/autoload.php'; // Include Razorpay SDK

use Razorpay\Api\Api;

$keyId = "rzp_test_FFm35IphRdzhve"; 
$keySecret = "eJVbPm8ip9aCGnHi8pWOuFnw"; 

$api = new Api($keyId, $keySecret);

$order = $api->order->create([
    'amount' => 50000,  // ₹500 (Amount in paise)
    'currency' => 'INR',
    'payment_capture' => 1 // Auto capture payment
]);

echo json_encode(['orderId' => $order['id']]);

?>
