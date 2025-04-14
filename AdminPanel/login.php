<?php
require '../dbCon.php';
session_start();
$obj = new Foodies();

if (isset($_POST['btnLogin'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and password are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $_SESSION['error'] = "Password must be at least 8 characters.";
    } else {
        try {
            $admin = $obj->loginAdmin($email, $password);
            $_SESSION['admin'] = $admin;
            $_SESSION['id'] = $admin['user_id'];
            $_SESSION['success'] = "Login successful!";
            $_SESSION['redirect'] = 'dashboard.php'; // NEW
        } catch (Exception $e) {
            $_SESSION['error'] = "Login failed: " . $e->getMessage();
        }

    }
    header("Location: login.php");
    exit();
}

$msg = '';
$icon = '';

if (isset($_SESSION['success'])) {
    $msg = $_SESSION['success'];
    $icon = 'success';
    unset($_SESSION['success']);
} elseif (isset($_SESSION['error'])) {
    $msg = $_SESSION['error'];
    $icon = 'error';
    unset($_SESSION['error']);
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
    <!-- Toast Message -->
    <?php if (!empty($msg)): ?>
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: '<?php echo $icon; ?>',
                title: '<?php echo $msg; ?>',
                showConfirmButton: false,
                timer: 1200
            }).then(function () {
                <?php if ($icon === 'success' && isset($_SESSION['redirect'])): ?>
                    window.location.href = '<?php echo $_SESSION['redirect']; ?>';
                <?php endif; ?>
            });
        </script>
        <?php unset($_SESSION['redirect']); ?>
    <?php endif; ?>

    <!-- Login Container -->
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <!-- Logo and Title -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center pb-6">
                <div class="mr-2">
                    <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-black w-6 h-6" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
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
            <p class="text-gray-600 mt-2">Log in to your account</p>
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
                <div class="relative">
                    <input type="password" id="password" name="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent"
                        placeholder="••••••••" required>
                    <button type="button" id="togglePassword"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M10 3a7 7 0 0 0-7 7c0 1.657 2.686 5 7 5s7-3.343 7-5a7 7 0 0 0-7-7zm0 12c-3.866 0-6-2.686-6-5s2.134-5 6-5 6 2.686 6 5-2.134 5-6 5zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z" />
                        </svg>
                    </button>
                </div>
            </div>
            <script>
                const passwordInput = document.getElementById('password');
                const togglePasswordButton = document.getElementById('togglePassword');

                togglePasswordButton.addEventListener('click', function () {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.querySelector('svg').setAttribute('fill', type === 'password' ? 'currentColor' : '#eab308');
                });
            </script>

            <div class="flex items-center justify-start mb-6">
                <div class="flex items-center">
                    <!-- <input type="checkbox" id="remember"
                        class="h-4 w-4 text-accent focus:ring-accent border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label> -->
                </div>
                <a href="forget.php" class="text-sm text-accent hover:underline">Forgot password?</a>
            </div>

            <button type="submit" name="btnLogin"
                class="w-full bg-accent hover:bg-accent/90 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline transition duration-150">
                Log-in
            </button>
        </form>
    </div>
</body>

</html>