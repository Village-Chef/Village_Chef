<?php
session_start();
require '../vendor/autoload.php'; // PHPMailer installed via Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../dbCon.php';
$obj = new Foodies();

$error_message = '';
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

if (isset($_POST['send_otp'])) {
    $email = trim($_POST['email']);
    $user = $obj->getUserByEmail($email);
    if ($user) {
        $otp = rand(1000, 9999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;
        $_SESSION['otp_time'] = time();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'villagechefparthiv@gmail.com';
            $mail->Password = 'lkwp fbwk ehpw vbyd';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('villagechefparthiv@gmail.com', 'Village Chef Admin');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP for Password Reset';
            $mail->Body = 'Your OTP is: <strong>' . $otp . '</strong>. It is valid for 5 minutes.';

            $mail->send();
            $_SESSION['success'] = 'OTP sent to your email. Please check your inbox.';
            header('Location: verify-otp.php');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = 'Failed to send OTP. Please try again.';
        }
    } else {
        $error_message = 'Email not found.';
        $_SESSION['error'] = 'Email not found.';
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






    ?>

    <main class="flex-grow flex items-center justify-center px-4 py-20">
        <div class="bg-zinc-900 p-8 rounded-xl shadow-lg max-w-md w-full">
            <h2 class="text-3xl font-bold mb-6 text-center">Reset Password</h2>
            <p class="text-gray-400 mb-6 text-center">
                Enter your email address and we'll send you a link to reset your password.
            </p>

            <?php if (!empty($msg)): ?>
                <script>
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: '<?php echo $icon; ?>',
                        title: '<?php echo $msg; ?>',
                        showConfirmButton: false,
                        timer: 1500
                    })
                </script>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email Address</label>
                    <div class="relative">
                        <input type="email" id="email" name="email"
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                            class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
                            placeholder="your@email.com" required>
                        <span class="absolute right-3 top-2.5">
                            <i class="lucide-mail h-5 w-5 text-gray-400"></i>
                        </span>
                    </div>
                </div>

                <input name="send_otp" value="Send OTP" type="submit"
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

            <div class="mt-6 border-t border-zinc-800 pt-6 text-center">
                <p class="text-gray-400">Remember your password?</p>
                <a href="login.php" class="text-yellow-500 hover:underline font-medium">Back to login</a>
            </div>
        </div>
    </main>

    <?php require 'footer.php'; ?>
</body>

</html>