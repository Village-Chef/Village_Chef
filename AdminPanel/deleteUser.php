<?php
session_start();
require '../dbCon.php';

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}
$obj = new Foodies();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnDelete'])) {
    $user_id = $_POST['user_id'];

    try {
        $obj->deleteUser($user_id);
        header('location:users.php');
        exit();
    } catch (Exception $e) {
        $error_message = "User deletion failed: " . $e->getMessage();
    }
}
?>