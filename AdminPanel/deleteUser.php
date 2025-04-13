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
    // echo $user_id;

    try {
        if ($obj->deleteUser($user_id)) {
            $_SESSION['success'] = "User deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete user. Please try again.";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "User deletion failed: " . $e->getMessage();
    }

    // header('location:users.php');
    exit();
}
?>