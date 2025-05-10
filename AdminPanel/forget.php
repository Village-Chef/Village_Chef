<?php
session_start();
require '../dbCon.php';
require '../vendor/autoload.php'; // PHPMailer installed via Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
        // Generate a 4-digit OTP
        $otp = rand(1000, 9999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;
        $_SESSION['otp_time'] = time();

        // Configure PHPMailer
        $mail = new PHPMailer(true);
        try {
            // SMTP settings (replace with your actual SMTP details)
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Example: Gmail SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'villagechefparthiv@gmail.com'; // Your email
            $mail->Password = 'EmailPassword'; // Your password or App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('villagechefparthiv@gmail.com', 'Village Chef Admin');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP for Password Reset';
            $mail->Body = 'Your OTP is: <strong>' . $otp . '</strong>. It is valid for 5 minutes.';

            $mail->send();
            $_SESSION['success'] = 'OTP sent to your email. Please check your inbox.';
            header('Location: verify_otp.php');
            exit;
        } catch (Exception $e) {
            $error_message = 'Failed to send OTP. Please try again.';
            $_SESSION['error'] = 'Failed to send OTP. Please try again.';
        }
    } else {
        $error_message = 'Email not found.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password - Village Chef Admin</title>
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
        </div>
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-primary">Forget Password</h1>
            <p class="text-gray-600 mt-2">Reset your admin password</p>
        </div>
        <?php if (!empty($msg)): ?>
            <script>
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: '<?php echo $icon; ?>',
                    title: '<?php echo $msg; ?>',
                    showConfirmButton: false,
                    timer: 1000
                });
            </script>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
                <input type="email" id="email" name="email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent"
                    placeholder="admin@example.com" required>
            </div>
            <button type="submit" name="send_otp"
                class="w-full bg-accent hover:bg-accent/90 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline transition duration-150">
                Send OTP
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
    </div>
</body>

</html>