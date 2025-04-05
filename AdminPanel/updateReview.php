<?php
session_start();
require '../dbCon.php';

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}
$obj = new Foodies();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnUpdate'])) {
    $review_id = $_POST['review_id'];
    $status = $_POST['status'];

    try {
        $obj->updateReviewStatus($review_id, $status);
        header('location:reviews.php');
        exit();
    } catch (Exception $e) {
        $error_message = "User update failed: " . $e->getMessage();
    }
}
?>