<?php
session_start();
if (!isset($_SESSION['otp_verified']) || !isset($_SESSION['email'])) {
    header('Location: forgot-password.php');
    exit;
}

// require '../dbCon.php';
// $obj = new Foodies();
$error_message = '';
$success_message = '';


?>

<!DOCTYPE html>
<html lang="en" class="bg-black">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Village Chef - Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/lucide-icons/dist/umd/lucide.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-black text-white flex flex-col">
    <?php
    require "navbar.php";
    if (isset($_POST['btnResetPassword'])) {
        $new_password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

        if (empty($new_password) || empty($confirm_password)) {
            $error_message = 'Both password fields are required.';
        } elseif (strlen($new_password) < 8) {
            $error_message = 'Password must be at least 8 characters long.';
        } elseif (!preg_match('/[A-Z]/', $new_password)) {
            $error_message = 'Password must contain at least one uppercase letter.';
        } elseif (!preg_match('/[a-z]/', $new_password)) {
            $error_message = 'Password must contain at least one lowercase letter.';
        } elseif (!preg_match('/[0-9]/', $new_password)) {
            $error_message = 'Password must contain at least one number.';
        } elseif (!preg_match('/[\W_]/', $new_password)) {
            $error_message = 'Password must contain at least one special character.';
        } elseif ($new_password !== $confirm_password) {
            $error_message = 'Passwords do not match.';
        } else {
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            if ($obj->updatePassword($_SESSION['email'], $password_hash)) {
                $success_message = 'Password updated successfully. Redirecting to login...';
                session_unset();
                session_destroy();
            } else {
                $error_message = 'Failed to update password. Please try again.';
            }
        }
    }

    ?>
    <!-- Reset Password Section -->
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
                        window.location.href = 'login.php';
                    });
                </script>
                <div class="text-center">
                    <h2 class="text-3xl font-bold mb-6">Password Reset</h2>
                    <p class="text-gray-400 mb-6">Your password has been reset successfully.</p>
                    <a href="login.php" class="text-yellow-500 hover:underline font-medium">Return to login</a>
                </div>
            <?php else: ?>
                <h2 class="text-3xl font-bold mb-6 text-center">Create New Password</h2>
                <p class="text-gray-400 mb-6 text-center">
                    Your identity has been verified. Please create a new password.
                </p>
                <form method="POST">
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-400 mb-2">New Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password"
                                class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
                                placeholder="••••••••" required>
                            <span class="absolute right-3 top-2.5">
                                <i class="lucide-lock h-5 w-5 text-gray-400"></i>
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            Password must be at least 8 characters with uppercase, lowercase, numbers, and symbols.
                        </p>
                    </div>
                    <div class="mb-6">
                        <label for="confirm_password" class="block text-sm font-medium text-gray-400 mb-2">Confirm
                            Password</label>
                        <div class="relative">
                            <input type="password" id="confirm_password" name="confirm_password"
                                class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
                                placeholder="••••••••" required>
                            <span class="absolute right-3 top-2.5">
                                <i class="lucide-check-circle h-5 w-5 text-gray-400"></i>
                            </span>
                        </div>
                    </div>
                    <input name="btnResetPassword" value="Reset Password" type="submit"
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
            <?php endif; ?>
        </div>
    </main>
    <?php require 'footer.php' ?>
</body>

</html>