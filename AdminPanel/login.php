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
            $admin = $obj->loginAdmin($email, $password);
            $_SESSION['admin'] = $admin;
            $_SESSION['id'] = $admin['user_id'];
            $login_success = true;
            // header("Location: dashboard.php");
        } catch (Exception $e) {
            $error_message = "Login failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#000000',
                        accent: '#eab308',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 h-screen flex items-center justify-center">
    <!-- Login Container -->
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
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
                    window.location.href = 'users.php';
                });
            </script>
        <?php else: ?>
            <!-- Logo and Title -->
            <div class="text-center mb-8">
            <div class="flex items-center justify-center pb-6">
                        <div class="mr-2">
                            <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="text-black w-6 h-6" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chef-hat">
                                    <path
                                        d="M17 21a1 1 0 0 0 1-1v-5.35c0-.457.316-.844.727-1.041a4 4 0 0 0-2.134-7.589 5 5 0 0 0-9.186 0 4 4 0 0 0-2.134 7.588c.411.198.727.585.727 1.041V20a1 1 0 0 0 1 1Z" />
                                    <path d="M6 17h12" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-yellow-500 font-bold italic text-xl leading-none">Village</span>
                            <span class="font-bold text-xl leading-none">CHEF</span>
                        </div>
                    </div>
                <h1 class="text-3xl font-bold text-primary">Village Chef Admin</h1>
                <p class="text-gray-600 mt-2">Sign in to your account</p>
            </div>

            <form method="POST">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
                    <input type="email" id="email" name="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent"
                        placeholder="admin@example.com" required>
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                    <input type="password" id="password" name="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent"
                        placeholder="••••••••" required>
                </div>

                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember"
                            class="h-4 w-4 text-accent focus:ring-accent border-gray-300 rounded" required> 
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>
                    <a href="#" class="text-sm text-accent hover:underline">Forgot password?</a>
                </div>

                <button type="submit" name="btnLogin"
                    class="w-full bg-accent hover:bg-accent/90 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline transition duration-150">
                    Sign In
                </button>
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

        <?php if (!empty($error_message)): ?>
            <div class="mt-4 text-red-500 text-center">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>