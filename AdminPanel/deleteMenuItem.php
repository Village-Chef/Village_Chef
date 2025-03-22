<?php
session_start();
require '../dbCon.php';

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}
$obj = new Foodies();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnDelete'])) {
    $item_id = $_POST['item_id'];

    try {
        $obj->deleteMenuItem($item_id);
        header('location:menuItems.php');
        exit();
    } catch (Exception $e) {
        $error_message = "Menu iTEm deletion failed: " . $e->getMessage();
        echo $error_message;
    }
}
?>