<?php
session_start();
require '../dbCon.php';

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}
$obj = new Foodies();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnDelete'])) {
    $order_id = $_POST['order_id'];

    try {
        $obj->deleteOrder($order_id);
        header('location:orders.php');
        exit();
    } catch (Exception $e) {
        $error_message = "Order deletion failed: " . $e->getMessage();
    }
}
?>