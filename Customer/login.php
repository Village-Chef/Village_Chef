<?php
require '../dbCon.php';
session_start();
$obj = new Foodies();

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
    <!-- Header (unchanged) -->
    <header class="py-4 lg:container mx-auto px-4 flex items-center justify-between">
        <div class="flex items-center">
            <div class="mr-2">
                <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-black w-6 h-6" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chef-hat">
                        <path d="M17 21a1 1 0 0 0 1-1v-5.35c0-.457.316-.844.727-1.041a4 4 0 0 0-2.134-7.589 5 5 0 0 0-9.186 0 4 4 0 0 0-2.134 7.588c.411.198.727.585.727 1.041V20a1 1 0 0 0 1 1Z" />
                        <path d="M6 17h12" />
                    </svg>
                </div>
            </div>
            <div class="flex flex-col">
                <span class="text-yellow-500 font-bold italic text-xl leading-none">Village</span>
                <span class="font-bold text-xl leading-none">CHEF</span>
            </div>
        </div>
        <nav class="hidden md:flex items-center space-x-8">
            <a href="home.php" class="hover:text-yellow-500 transition-colors">Home</a>
            <a href="about.php" class="hover:text-yellow-500 transition-colors">About Us</a>
            <a href="menu.php" class="hover:text-yellow-500 transition-colors">Menu</a>
            <a href="contact.php" class="hover:text-yellow-500 transition-colors">Contact</a>
        </nav>
        <div class="flex items-center space-x-4">
            <button class="p-2 hover:text-yellow-500 transition-colors">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
            </button>
            <button class="p-2 hover:text-yellow-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart">
                    <circle cx="8" cy="21" r="1" />
                    <circle cx="19" cy="21" r="1" />
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                </svg>
            </button>
            <a href="login.php">
                <button class="hidden md:flex items-center border border-yellow-500 text-yellow-500 hover:bg-yellow-500 hover:cursor-pointer hover:text-black px-4 py-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-in">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                        <polyline points="10 17 15 12 10 7" />
                        <line x1="15" x2="3" y1="12" y2="12" />
                    </svg>
                    Login
                </button>
            </a>
        </div>
    </header>

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
                    }).then(function() {
                        window.location.href = 'home.php';
                    });
                </script>
            <?php else: ?>
                <!-- Show login form if not successful -->
                <h2 class="text-3xl font-bold mb-6 text-center">Welcome Back</h2>
                <form method="POST">
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email Address</label>
                        <input type="email" id="email" name="email" class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white" placeholder="your@email.com">
                    </div>
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-400 mb-2">Password</label>
                        <input type="password" id="password" name="password" class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white" placeholder="••••••••">
                    </div>
                    <input name="btnLogin" value="Login" type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 hover:cursor-pointer text-black font-bold py-2 px-4 rounded-md transition-colors" />
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
                    <a href="#" class="text-sm text-yellow-500 hover:underline">Forgot your password?</a>
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