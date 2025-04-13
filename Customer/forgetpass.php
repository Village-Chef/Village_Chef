<?php
session_start();

// Initialize error message
$error_message = '';
$success_message = '';

// Process form submission
if (isset($_POST['btnResetPassword'])) {
    $email = trim($_POST['email']);

    // Validate email
    if (empty($email)) {
        $error_message = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {
        // Here you would add your code to:
        // 1. Check if the email exists in your database
        // 2. Generate a reset token and store it in the database
        // 3. Send an email with the reset link
        
        // For demonstration, we'll just show a success message
        // In a real application, you would call a function like:
        // $obj->sendPasswordResetEmail($email);
        
        $success_message = "Password reset link has been sent to your email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="bg-black">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Village Chef - Forgot Password</title>
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
<?php require "navbar.php"; ?>

    <!-- Forgot Password Section -->
    <main class="flex-grow flex items-center justify-center px-4 py-20">
        <div class="bg-zinc-900 p-8 rounded-xl shadow-lg max-w-md w-full">
            <?php if (!empty($success_message)): ?>
                <!-- Success alert and redirect -->
                <script>
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: '<?php echo $success_message; ?>',
                        showConfirmButton: false,
                        timer: 3000
                    }).then(function() {
                        window.location.href = 'login.php';
                    });
                </script>
                <div class="text-center">
                    <h2 class="text-3xl font-bold mb-6">Email Sent</h2>
                    <p class="text-gray-400 mb-6">A password reset link has been sent to your email address.</p>
                    <a href="login.php" class="text-yellow-500 hover:underline font-medium">Return to login</a>
                </div>
            <?php else: ?>
                <!-- Show forgot password form -->
                <h2 class="text-3xl font-bold mb-6 text-center">Reset Password</h2>
                <p class="text-gray-400 mb-6 text-center">
                    Enter your email address and we'll send you a link to reset your password.
                </p>
                
                <form method="POST">
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email Address</label>
                        <div class="relative">
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white" 
                                placeholder="your@email.com"
                            >
                            <span class="absolute right-3 top-2.5">
                                <i class="lucide-mail h-5 w-5 text-gray-400"></i>
                            </span>
                        </div>
                    </div>
                    
                    <input 
                        name="btnResetPassword" 
                        value="Send Reset Link" 
                        type="submit" 
                        class="w-full bg-yellow-500 hover:bg-yellow-600 hover:cursor-pointer text-black font-bold py-2 px-4 rounded-md transition-colors"
                    />
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

                <div class="mt-6 border-t border-zinc-800 pt-6 text-center">
                    <p class="text-gray-400">Remember your password?</p>
                    <a href="login.php" class="text-yellow-500 hover:underline font-medium">Back to login</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php require 'footer.php' ?>
</body>
</html>