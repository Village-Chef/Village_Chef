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
        if ($obj->updateReviewStatus($review_id, $status)) {
            $_SESSION['success'] = "Review " . $status . " successfully!";
        } else {
            $_SESSION['error'] = "Failed to update Review Status. Please try again.";
        }
        header('location:reviews.php');
        exit();
    } catch (Exception $e) {
        $error_message = "User update failed: " . $e->getMessage();
    }
}
?>