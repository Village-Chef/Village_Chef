<?php
$apiKey = "rzp_test_FFm35IphRdzhve"; // Replace with your Test Key
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Razorpay Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>

<body>
    <button id="pay-btn">Pay Now</button>

    <script>
        var options = {
            "key": "<?php echo $apiKey; ?>",
            "amount": 50000, // Amount in paise (₹500)
            "currency": "INR",
            "name": "Village Chef Payment",
            "description": "Testing Razorpay",
            "image": "https://your-logo-url.com/logo.png",
            "handler": function (response) {
                alert("Payment Successful! Payment ID: " + response.razorpay_payment_id);
                window.location.href = "success.php?payment_id=" + response.razorpay_payment_id;
            },
            "prefill": {
                "name": "Parthiv Shingala",
                "email": "parthivshingala@gmail.com",
                "contact": "9727181143"
            },
            "theme": {
                "color": "#3399cc"
            }
        };

        document.getElementById("pay-btn").onclick = function () {
            var rzp1 = new Razorpay(options);
            rzp1.open();
        };
    </script>
</body>

</html>