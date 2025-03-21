<?php
class Foodies
{

    private $con;

    function __construct()
    {
        $dsn = 'mysql:host=localhost;dbname=wdos_foodies';
        $user = 'root';
        $pass = '';

        $this->con = new PDO($dsn, username: $user, password: $pass);
    }

    function registerUser($profile_pic, $fname, $lname, $email, $password, $phone = null)
    {
        try {
            $fileName = $profile_pic['name'];

            $fileTmpPath = $profile_pic['tmp_name'];
            $uploadDir = 'uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $filePath = $uploadDir . basename($fileName);

            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileMimeType = mime_content_type($fileTmpPath);

            if (!in_array($fileMimeType, $allowedMimeTypes)) {
                echo "File format not supported.";
                return;
            }

            if (move_uploaded_file($fileTmpPath, $filePath)) {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                $sql = "INSERT INTO users 
                        (profile_pic,first_name, last_name, email, phone, password_hash, role, status) 
                        VALUES 
                        (:profile_pic,:fname, :lname, :email, :phone, :pass, 'customer', 'active')";

                $stmt = $this->con->prepare($sql);

                $stmt->bindParam(':profile_pic', $filePath);
                $stmt->bindParam(':fname', $fname);
                $stmt->bindParam(':lname', $lname);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':pass', $password_hash);

                return $stmt->execute();
            }



        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new Exception("Email already exists...");
            }
            throw new Exception("Registration failed" . $e->getMessage());
        }
    }

    function addUser($fname, $lname, $email, $password, $phone = null)
    {
        try {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users 
                    (first_name, last_name, email, phone, password_hash, role, status) 
                    VALUES 
                    (:fname, :lname, :email, :phone, :pass, 'customer', 'active')";

            $stmt = $this->con->prepare($sql);

            $stmt->bindParam(':fname', $fname);
            $stmt->bindParam(':lname', $lname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':pass', $password_hash);

            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new Exception("Email already exists...");
            }
            throw new Exception("Registration failed: " . $e->getMessage());
        }
    }

    function loginUser($email, $password)
    {
        try {
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password_hash'])) {
                return $user;
            } else {
                throw new Exception("Invalid email or password.");
            }
        } catch (PDOException $e) {
            throw new Exception("Login failed: " . $e->getMessage());
        }
    }

    function loginAdmin($email, $password)
    {
        try {
            $sql = "SELECT * FROM users WHERE email = :email AND role = 'admin'";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password, $admin['password_hash'])) {
                return $admin;
            } else {
                throw new Exception("Invalid email or password.");
            }
        } catch (PDOException $e) {
            throw new Exception("Login failed: " . $e->getMessage());
        }
    }
}
?>