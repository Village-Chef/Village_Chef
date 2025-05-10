<!DOCTYPE html>
<html lang="en" class="bg-black">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Village Chef - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://unpkg.com/lucide-icons/dist/umd/lucide.css" rel="stylesheet">
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

    require '../vendor/autoload.php'; // Include Composer's autoloader
// require '../Customer/Assets/logo.png';
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;



    $signup_success = false;
    $error_message = '';

    if(isset($_SESSION['user'])) {
        echo "<script>window.location.href = 'menu.php';</script>";
    }

    if (isset($_POST['btnSubmit'])) {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];



        if (empty($fname) || empty($lname) || empty($email) || empty($phone) || empty($password)) {
            $error_message = 'All fields are required';
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = 'Invalid email address';
        } else if (strlen($password) < 8) {
            $error_message = 'Password must be at least 8 characters long.';
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $error_message = 'Password must contain at least one uppercase letter.';
        } elseif (!preg_match('/[a-z]/', $password)) {
            $error_message = 'Password must contain at least one lowercase letter.';
        } elseif (!preg_match('/[0-9]/', $password)) {
            $error_message = 'Password must contain at least one number.';
        } elseif (!preg_match('/[\W_]/', $password)) {
            $error_message = 'Password must contain at least one special character.';
        } elseif (strlen($phone) != 10) {
            $error_message = 'Phone number must be 10 digits long';
        } else {
            try {
                $obj->addUser($fname, $lname, $email, $password, $phone);
                // echo "Registration successful!";
                $signup_success = true;
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'villagechefparthiv@gmail.com';
                    $mail->Password = 'EmailPassword';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;


                    $mail->setFrom('villagechefparthiv@gmail.com', 'Village Chef');
                    $mail->addAddress($email, $fname . ' ' . $lname);


                    $mail->isHTML(true);
                    $mail->Subject = 'Welcome to Village Chef!';


                    $mail->Body = '
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <style>
                            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                            .header { background-color: #f8f9fa; padding: 20px; text-align: center; }
                            .logo { max-width: 200px; height: auto; }
                            .content { padding: 30px 20px; }
                            .section { margin-bottom: 25px; }
                            .greeting { font-size: 24px; color: #2c5f2d; margin-bottom: 20px; }
                            .cta-button {
                                display: inline-block;
                                padding: 12px 25px;
                                background-color: #2c5f2d;
                                color: white !important;
                                text-decoration: none;
                                border-radius: 5px;
                                margin: 20px 0;
                            }
                            .footer { 
                                background-color: #f8f9fa; 
                                padding: 20px;
                                text-align: center;
                                font-size: 12px;
                                color: #666;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="header">
                            <img src="https://media-hosting.imagekit.io//fca9e6ca7b3f4e3f/logo.png?Expires=1837177792&Key-Pair-Id=K2ZIVPTIP2VGHC&Signature=Fr7pfetD1QH-pGF7ZRELRK4uXBHrSycyFZNytUU4UPhY-va3JCFlUkDZ74hC4-c0VyDa15O80nWdKzWNoNG66n5L9SlSbgelpClbMhO~FOsaUuo00pMoFTeaL8t7rhRQ2rwsFyuGOuRs6C9pLkqY~~DWNwqK6wgBXQre86vIiu2mQtSz~sVVovcIWJ22CSt6Bg7h9i7-4HksrVmMH9bh8AxYOpTS07KzlLqIKV~40GODtEmfAfTjZ9gcIyCou0UTyWBoLnIVKOjkrDAeh9FVFk9eVfrrPSY9cn5aluZ8ARloYCIYYghxOIkpZt4nYnEV9nQSANXxvY56jcyeu4KPCg__" class="logo" alt="Village Chef Logo">
                        </div>
    
                        <div class="content">
                            <div class="section">
                                <h1 class="greeting">Welcome to Village Chef, ' . $fname . '!</h1>
                                <p>We\'re thrilled to have you join our community of food enthusiasts who appreciate authentic, home-cooked meals.</p>
                            </div>
    
                            <div class="section" style="background-color: #fff3e6; padding: 20px; border-radius: 10px;">
                                <h2 style="color: #2c5f2d; margin-top: 0;">Our Promise to You</h2>
                                <p>Fresh ingredients sourced from local farmers<br>
                                Expert chefs specializing in regional cuisines<br>
                                Nutritious meals prepared with love and care<br>
                                Sustainable practices supporting our community</p>
                            </div>
    
                            <div class="section">
                                <h3 style="color: #2c5f2d;">Get Started</h3>
                                <p>Explore our menu and place your first order:</p>
                                <a href="https://www.yourdomain.com/menu" class="cta-button">View Our Menu</a>
                            </div>
    
                            <div class="section">
                                <h3 style="color: #2c5f2d;">Your Account Details</h3>
                                <p>Email: ' . $email . '<br>
                                Phone: ' . $phone . '</p>
                            </div>
                        </div>
    
                        <div class="footer">
                            <p>Follow us on social media:</p>
                            <p style="margin: 15px 0;">
                                <a href="[Facebook URL]" style="color: #2c5f2d; text-decoration: none; margin: 0 10px;">Facebook</a> | 
                                <a href="[Instagram URL]" style="color: #2c5f2d; text-decoration: none; margin: 0 10px;">Instagram</a> | 
                                <a href="[Twitter URL]" style="color: #2c5f2d; text-decoration: none; margin: 0 10px;">Twitter</a>
                            </p>
                            <p>Village Chef © ' . date('Y') . '<br>
                            Bringing authentic home-cooked meals since 2020</p>
                            <p style="font-size: 10px; color: #999;">
                                <a href="[Unsubscribe Link]" style="color: #999;">Unsubscribe</a> | 
                                <a href="[Privacy Policy]" style="color: #999;">Privacy Policy</a>
                            </p>
                        </div>
                    </body>
                    </html>';

                    // Add plain text version for non-HTML email clients
                    $mail->AltBody = "Welcome to Village Chef, $fname $lname!\n\n" .
                        "We're excited to have you join our community of food enthusiasts who appreciate authentic, home-cooked meals.\n\n" .
                        "Our Promise:\n" .
                        "- Fresh ingredients from local farmers\n" .
                        "- Expert chefs specializing in regional cuisines\n" .
                        "- Nutritious meals prepared with care\n" .
                        "- Sustainable practices\n\n" .
                        "Start your culinary journey: https://www.yourdomain.com/menu\n\n" .
                        "Your account details:\n" .
                        "Email: $email\n" .
                        "Phone: $phone\n\n" .
                        "Follow us on social media!\n" .
                        "Village Chef © " . date('Y');

                    $mail->send();
                    // echo 'Welcome email has been sent';
                    // header("Location: login.php");
                    // exit();
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } catch (Throwable $e) {
                $error_message =  "Registration failed: " . $e->getMessage();
            }
        }





    }
    ?>
    <div class="">

    </div>
    <!-- Reg Section -->
    <main class="flex-grow flex items-center  justify-center px-4 py-20">
        <div class="bg-zinc-900 relative p-8 rounded-xl   shadow-lg max-w-md w-full">
            <?php if ($signup_success): ?>
                <!-- Success alert and redirect -->
                <script>
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: '<?PHP $error_message ?>',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(function () {
                        window.location.href = 'login.php';
                    });
                </script>
            <?php else: ?>
                <h2 class="text-3xl font-bold mb-6 text-center">Create Your Account 🍕</h2>
                <form method="POST">
                    <div class="mb-4">
                        <label for="fname" class="block text-sm font-medium text-gray-400 mb-2">First Name</label>
                        <input type="text" id="fname" name="fname"
                            class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
                            placeholder="First Name">
                    </div>
                    <div class="mb-4">
                        <label for="lname" class="block text-sm font-medium text-gray-400 mb-2">Last Name</label>
                        <input type="text" id="lname" name="lname"
                            class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
                            placeholder="Last Name">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email Address</label>
                        <input type="email" id="email" name="email"
                            class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
                            placeholder="example@email.com">
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-400 mb-2">Mobile Number</label>
                        <input type="number" maxlength="10" id="phone" name="phone"
                            class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
                            placeholder="+91 12345 12345">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-400 mb-2">Password</label>
                        <input type="password" id="password" name="password"
                            class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
                            placeholder="••••••••">
                    </div>
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" required
                                class="form-checkbox bg-zinc-800 border-zinc-700 text-yellow-500 rounded">
                            <span class="ml-2 text-sm text-gray-400">I agree to the <a href="#"
                                    class="text-yellow-500 hover:underline">Terms of Service</a> and <a href="#"
                                    class="text-yellow-500 hover:underline">Privacy Policy</a></span>
                        </label>
                    </div>
                    <input type="submit" name="btnSubmit" value="Register"
                        class="w-full bg-yellow-500 hover:bg-yellow-600 cursor-pointer text-black font-bold py-2 px-4 rounded-md transition-colors" />
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
                    <p class="text-gray-400">Already have an account?</p>
                    <a href="login.php" class="text-yellow-500 hover:underline font-medium">Log in here</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php require"footer.php"; ?>

    <!-- Add Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>