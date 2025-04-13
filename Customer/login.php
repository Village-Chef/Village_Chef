<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en" class="bg-black">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Village Chef - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/lucide-icons/dist/umd/lucide.css" rel="stylesheet">
    <!-- SweetAlert2  -->
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
    $login_success = false;
    $error_message = '';

    if (isset($_POST['btnLogin'])) {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if (empty($email) || empty($password)) {
            $error_message = "Email and password are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = "Invalid email format.";
        } elseif (strlen($password) < 8) {
            $error_message = "Password must be at least 8 characters.";
        } else {
            try {
                $user = $obj->loginUser($email, $password);
                $_SESSION['user'] = $user;
                $login_success = true;
            } catch (Exception $e) {
                $error_message = "Login failed: " . $e->getMessage();
            }
        }
    }
    ?>

    <!-- Login Section -->
    <main class="flex-grow flex items-center justify-center px-4 py-20">
        <div class="bg-zinc-900 p-8 rounded-xl shadow-lg max-w-md w-full">
            <?php if ($login_success): ?>
                <!-- Success alert and redirect -->
                <script>
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Login successful',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(function () {
                        window.location.href = 'account_user.php';
                    });
                </script>
            <?php else: ?>
                <!-- Show login form if not successful -->
                <h2 class="text-3xl font-bold mb-6 text-center">Welcome Back</h2>
                <form method="POST">
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email Address</label>
                        <input type="email" id="email" name="email"
                            class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
                            placeholder="your@email.com">
                    </div>
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-400 mb-2">Password</label>
                        <input type="password" id="password" name="password"
                            class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
                            placeholder="••••••••">
                    </div>
                    <input name="btnLogin" value="Login" type="submit"
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
                    <a href="forgot-password.php" class="text-sm text-yellow-500 hover:underline">Forgot your password?</a>
                </div>
                <div class="mt-6 border-t border-zinc-800 pt-6 text-center">
                    <p class="text-gray-400">Don't have an account?</p>
                    <a href="signup.php" class="text-yellow-500 hover:underline font-medium">Sign up now</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php require 'footer.php' ?>
</body>

</html>