<?php
session_start();
require '../dbCon.php';

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}
$obj = new Foodies();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnUpdate'])) {
    $user_id = $_POST['user_id'];
    $status = $_POST['status'];

    try {
        $obj->updateUserStatus($user_id, $status);
        header('location:users.php');
        exit();
    } catch (Exception $e) {
        $error_message = "User update failed: " . $e->getMessage();
    }
}
?>