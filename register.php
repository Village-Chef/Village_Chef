<?php
require 'dbCon.php';
$obj = new Foodies();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
</head>

<body>
    <center>
        <h1>Demo Program</h1>
        <form action="register.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="file" id="">
            <input type="text" name="fname" placeholder="Enter your First-name" required><br><br>
            <input type="text" name="lname" placeholder="Enter your Last-Name" required><br><br>
            <input type="email" name="email" placeholder="Enter your email" required><br><br>
            <input type="tel" name="phone" placeholder="Enter your Phone Number"><br><br>
            <input type="password" name="password" placeholder="Enter your Password" required><br><br>
            <input type="submit" name="btnSubmit" value="Register">
        </form>
    </center>
</body>

</html>

<?php

if (isset($_POST['btnSubmit'])) {
    $profile_pic = $_FILES['file'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    try {
        $obj->registerUser($profile_pic, $fname, $lname, $email, $password, $phone);
        echo "Registration successful!";
    } catch (Exception $e) {
        echo "Registration failed: " . $e->getMessage();
    }
}

?>