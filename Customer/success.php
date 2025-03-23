<?php
if (isset($_GET['payment_id'])) {
    echo "Payment Successful! Your Payment ID: " . htmlspecialchars($_GET['payment_id']);
} else {
    echo "Payment Failed!";
}
?>
