<?php
session_start();
require '../dbCon.php';

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}

$msg = '';

if (isset($_SESSION['success'])) {
    $msg = $_SESSION['success'];
    $icon = 'success';
    unset($_SESSION['success']);
} elseif (isset($_SESSION['error'])) {
    $msg = $_SESSION['error'];
    $icon = 'error';
    unset($_SESSION['error']);
} else {
    $msg = '';
    $icon = '';
}

$obj = new Foodies();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnDelete'])) {
    $cuisine_id = $_POST['cuisine_id'];

    try {
        if ($obj->deleteCuisine($cuisine_id)) {
            $_SESSION['success'] = "Cuisine Deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete Cuisine. Please try again.";
        }
        header('location:cuisine.php');
        exit();
    } catch (Exception $e) {
        $error_message = "cuisine deletion failed: " . $e->getMessage();
        echo $error_message;
    }
}
?>