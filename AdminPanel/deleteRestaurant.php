<?php
session_start();
require '../dbCon.php';

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}
$obj = new Foodies();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnDelete'])) {
    $restaurant_id = $_POST['restaurant_id'];

    try {
        if ($obj->deleteRestaurant($restaurant_id)) {
            $_SESSION['success'] = "Restaurant deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete restaurant. Please try again.";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Restaurant deletion failed: " . $e->getMessage();
    }

    header('location:restaurants.php');
    exit();
}
?>