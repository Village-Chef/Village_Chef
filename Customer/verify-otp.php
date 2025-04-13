<?php
session_start();

// Redirect if OTP session not set
if (!isset($_SESSION['otp']) || !isset($_SESSION['email'])) {
    header('Location: forgot-password.php');
    exit;
}

$error_message = '';
$success_message = '';

// Handle form submission
if (isset($_POST['verify_otp'])) {
    $entered_otp = trim($_POST['otp']);
    $time_elapsed = time() - $_SESSION['otp_time'];

    if ($entered_otp == $_SESSION['otp'] && $time_elapsed <= 300) {
        $_SESSION['otp_verified'] = true;
        $_SESSION['success'] = 'OTP verified successfully. You can now reset your password.';
        header('Location: reset-password.php');
        exit;
    } else {
        $error_message = $time_elapsed > 300 ? 'OTP has expired.' : 'Invalid OTP.';
        $_SESSION['error'] = $error_message;
        header('Location: verify-otp.php');
        exit;
    }
}

// Check for session messages
if (isset($_SESSION['success']) && $_SESSION['success'] === 'OTP verified successfully. You can now reset your password.') {
    $success_message = $_SESSION['success'];
    unset($_SESSION['success']);
} else {
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    $error_message = $_SESSION['error'];
    unset($_SESSION['error']);
}

$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en" class="bg-black">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Village Chef - Verify OTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/lucide-icons/dist/umd/lucide.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .otp-input {
            letter-spacing: 0.5em;
            text-align: center;
        }
    </style>
</head>

<body class="min-h-screen bg-black text-white flex flex-col">
    <?php require "navbar.php"; ?>

    <!-- OTP Verification Section -->
    <main class="flex-grow flex items-center justify-center px-4 py-20">
        <div class="bg-zinc-900 p-8 rounded-xl shadow-lg max-w-md w-full">
            <?php if (!empty($success_message)): ?>
                <script>
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: '<?php echo $success_message; ?>',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(function () {
                        window.location.href = 'reset-password.php';
                    });
                </script>
                <div class="text-center">
                    <h2 class="text-3xl font-bold mb-6">OTP Verified</h2>
                    <p class="text-gray-400 mb-6">Redirecting you to reset your password...</p>
                </div>
            <?php else: ?>
                <h2 class="text-3xl font-bold mb-6 text-center">Verify OTP</h2>
                <p class="text-gray-400 mb-6 text-center">
                    We've sent a 6-digit code to <span
                        class="text-yellow-500"><?php echo htmlspecialchars($email); ?></span>.
                    Enter the code below to verify your identity.
                </p>

                <form method="POST">
                    <div class="mb-6">
                        <label for="otp" class="block text-sm font-medium text-gray-400 mb-2">Enter OTP</label>
                        <div class="relative">
                            <input type="text" id="otp" name="otp" maxlength="6"
                                class="otp-input w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
                                placeholder="• • • • • •" required>
                            <span class="absolute right-3 top-2.5">
                                <i class="lucide-shield-check h-5 w-5 text-gray-400"></i>
                            </span>
                        </div>
                    </div>

                    <input name="verify_otp" value="Verify OTP" type="submit"
                        class="w-full bg-yellow-500 hover:bg-yellow-600 hover:cursor-pointer text-black font-bold py-2 px-4 rounded-md transition-colors" />
                </form>

                <?php if (!empty($error_message)): ?>
                    <script>
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: '<?php echo $error_message; ?>',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    </script>
                <?php endif; ?>

                <div class="mt-4 text-center">
                    <p class="text-gray-400 text-sm">Didn't receive the code?</p>
                    <a href="#" class="text-yellow-500 hover:underline text-sm font-medium">Resend OTP</a>
                </div>

                <div class="mt-6 border-t border-zinc-800 pt-6 text-center">
                    <a href="forgot-password.php" class="text-yellow-500 hover:underline font-medium">
                        <i class="lucide-arrow-left h-4 w-4 inline-block mr-1"></i> Back to forgot password
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php require 'footer.php' ?>
</body>

</html>