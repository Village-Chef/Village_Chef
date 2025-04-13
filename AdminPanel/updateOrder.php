<?php
session_start();
require '../dbCon.php';

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}
$obj = new Foodies();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnUpdate'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    try {
        if ($obj->updateOrderStatus($order_id, $status)) {
            $_SESSION['success'] = "Order status updated To " . $status . " successfully!";
        } else {
            $_SESSION['error'] = "Failed to update order status. Please try again.";
        }
        header('location:orders.php');
        exit();
    } catch (Exception $e) {
        $error_message = "User update failed: " . $e->getMessage();
    }
}
?>