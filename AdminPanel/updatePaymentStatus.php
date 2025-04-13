<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}

require '../dbCon.php';
$obj = new Foodies();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_id'])) {
    $payment_id = $_POST['payment_id'];

    try {
        if ($obj->updatePaymentStatus($payment_id)) {
            $_SESSION['success'] = "Payment is Refunded successfully.";
        } else {
            $_SESSION['error'] = "Failed to Refund Payment. Please try again.";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    header('location:payments.php');
    exit();
}
?>