<?php
session_start();
require '../dbCon.php';

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}
$obj = new Foodies();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnDelete'])) {
    $cuisine_id = $_POST['cuisine_id'];

    try {
        $obj->deleteCuisine($cuisine_id);
        header('location:cuisine.php');
        exit();
    } catch (Exception $e) {
        $error_message = "cuisine deletion failed: " . $e->getMessage();
        echo $error_message;
    }
}
?>