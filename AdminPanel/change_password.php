<?php
session_start();
if (!isset($_SESSION['otp_verified']) || !isset($_SESSION['email'])) {
    header('Location: forget.php');
    exit;
}

require '../dbCon.php';
$obj = new Foodies();
$error_message = '';

$msg = '';

if (isset($_SESSION['success'])) {
    $msg = $_SESSION['success'];
    $icon = 'success';
    unset($_SESSION['success']);
} elseif (isset($_SESSION['error'])) {
    $msg = $_SESSION['error'];
    $icon = 'error';
    unset($_SESSION['error']);
} else {
    $msg = '';
    $icon = '';
}

if (isset($_POST['change_password'])) {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);


    if (empty($new_password) || empty($confirm_password)) {
        $msg = 'Both password fields are required.';
        $icon = 'error';
    } elseif (strlen($new_password) < 8) {
        $msg = 'Password must be at least 8 characters long.';
        $icon = 'error';
    } elseif (!preg_match('/[A-Z]/', $new_password)) {
        $msg = 'Password must contain at least one uppercase letter.';
        $icon = 'error';
    } elseif (!preg_match('/[a-z]/', $new_password)) {
        $msg = 'Password must contain at least one lowercase letter.';
        $icon = 'error';
    } elseif (!preg_match('/[0-9]/', $new_password)) {
        $msg = 'Password must contain at least one number.';
        $icon = 'error';
    } elseif (!preg_match('/[\W_]/', $new_password)) {
        $msg = 'Password must contain at least one special character.';
        $icon = 'error';
    } elseif ($new_password !== $confirm_password) {
        $msg = 'Passwords do not match.';
        $icon = 'error';
    } else {

        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        if ($obj->updatePassword($_SESSION['email'], $password_hash)) {
            $msg = 'Password updated successfully. You can now log in.';
            $icon = 'success';
            $redirect = true;
            session_unset();
            session_destroy();
        } else {
            $msg = 'Failed to update password. Please try again.';
            $icon = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Village Chef Admin</title>
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
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <!-- Logo and Title -->
        <div class="text-center mb-">
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
        </div>
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-primary">Change Password</h1>
            <p class="text-gray-600 mt-2">Set your new password</p>
        </div>
        <form method="POST">
            <div class="mb-4">
                <label for="new_password" class="block text-gray-700 text-sm font-medium mb-2">New Password</label>
                <div class="relative">
                    <input type="password" id="new_password" name="new_password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent"
                        placeholder="••••••••" required>
                    <button type="button" id="toggleNewPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 3a7 7 0 0 0-7 7c0 1.657 2.686 5 7 5s7-3.343 7-5a7 7 0 0 0-7-7zm0 12c-3.866 0-6-2.686-6-5s2.134-5 6-5 6 2.686 6 5-2.134 5-6 5zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="mb-4">
                <label for="confirm_password" class="block text-gray-700 text-sm font-medium mb-2">Confirm Password</label>
                <div class="relative">
                    <input type="password" id="confirm_password" name="confirm_password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent"
                        placeholder="••••••••" required>
                    <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 3a7 7 0 0 0-7 7c0 1.657 2.686 5 7 5s7-3.343 7-5a7 7 0 0 0-7-7zm0 12c-3.866 0-6-2.686-6-5s2.134-5 6-5 6 2.686 6 5-2.134 5-6 5zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z" />
                        </svg>
                    </button>
                </div>
            </div>
            <script>
                const newPasswordInput = document.getElementById('new_password');
                const toggleNewPasswordButton = document.getElementById('toggleNewPassword');
                const confirmPasswordInput = document.getElementById('confirm_password');
                const toggleConfirmPasswordButton = document.getElementById('toggleConfirmPassword');

                toggleNewPasswordButton.addEventListener('click', function () {
                    const type = newPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    newPasswordInput.setAttribute('type', type);
                    this.querySelector('svg').setAttribute('fill', type === 'password' ? 'currentColor' : '#eab308');
                });

                toggleConfirmPasswordButton.addEventListener('click', function () {
                    const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    confirmPasswordInput.setAttribute('type', type);
                    this.querySelector('svg').setAttribute('fill', type === 'password' ? 'currentColor' : '#eab308');
                });
            </script>
            <button type="submit" name="change_password"
                class="w-full bg-accent hover:bg-accent/90 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline transition duration-150">
                Change Password
            </button>
        </form>
        <?php if (!empty($msg)): ?>
            <script>
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: '<?php echo $icon; ?>',
                    title: '<?php echo $msg; ?>',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didClose: () => {
                        <?php if (isset($redirect) && $redirect): ?>
                            window.location.href = 'login.php';
                        <?php endif; ?>
                    }
                });
            </script>
        <?php endif; ?>

    </div>
</body>

</html>