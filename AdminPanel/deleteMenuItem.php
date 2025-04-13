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
        if ($obj->deleteMenuItem($item_id)) {
            $_SESSION['success'] = "Menu Item Deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete Menu Item. Please try again.";
        }
        header('location:menuItems.php');
        exit();
    } catch (Exception $e) {
        $error_message = "Menu iTEm deletion failed: " . $e->getMessage();
        echo $error_message;
    }
}
?>