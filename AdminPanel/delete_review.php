<?php
session_start();
require '../dbCon.php';

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}
$obj = new Foodies();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnDelete'])) {
    $review_id = $_POST['review_id'];

    try {
        $obj->deleteReview($review_id);
        header('location:reviews.php');
        exit();
    } catch (Exception $e) {
        $error_message = "review deletion failed: " . $e->getMessage();
        echo $error_message;
    }
}
?>