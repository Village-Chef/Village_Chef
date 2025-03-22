<?php
session_start();
require '../dbCon.php';

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}
$obj = new Foodies();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnDelete'])) {
    $user_id = $_POST['restaurant_id'];

    try {
        $obj->deleteRestaurant($user_id);
        header('location:restaurants.php');
        exit();
    } catch (Exception $e) {
        $error_message = "User deletion failed: " . $e->getMessage();
        echo $error_message;
    }
}
?>