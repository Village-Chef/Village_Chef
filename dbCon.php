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
    function getAllUsers()
    {
        try {
            $sql = "SELECT * FROM users";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch users: " . $e->getMessage());
        }
    }


    function updateUserStatus($user_id, $status){
        try {
            $sql = "UPDATE users SET status = :status WHERE user_id = :user_id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':user_id', $user_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to update user status: " . $e->getMessage());
        }
    }


    function deleteUser($user_id){
        try {
            $sql = "DELETE FROM users WHERE user_id = :user_id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to delete user: " . $e->getMessage());
        }
    }

    function getUserById($id) {
        try {
            $sql = "SELECT * FROM users WHERE user_id = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch user: " . $e->getMessage());
        }
    }

    function updateProfile($id, $profile_pic, $fname, $lname, $email, $phone)
    {
        try {
            $sql = "UPDATE users SET first_name = :fname, last_name = :lname, email = :email, phone = :phone";

            if (!empty($profile_pic['name'])) {
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
                    throw new Exception("File format not supported.");
                }

                if (move_uploaded_file($fileTmpPath, $filePath)) {
                    $sql .= ", profile_pic = :profile_pic";
                }
            }

            $sql .= " WHERE user_id = :id";

            $stmt = $this->con->prepare($sql);

            $stmt->bindParam(':fname', $fname);
            $stmt->bindParam(':lname', $lname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':id', $id);

            if (!empty($profile_pic['name'])) {
                $stmt->bindParam(':profile_pic', $filePath);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Profile update failed: " . $e->getMessage());
        }
    }

    function addRestaurant($restaurant_pic, $rname, $rphone, $raddress, $rcity, $rstate, $rzip_code, $status)
    {
        try {
            $fileName = $restaurant_pic['name'];
            $fileTmpPath = $restaurant_pic['tmp_name'];
            $uploadDir = 'uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $filePath = $uploadDir . basename($fileName);

            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif','image/jpg'];
            $fileMimeType = mime_content_type($fileTmpPath);

            if (!in_array($fileMimeType, $allowedMimeTypes)) {
                throw new Exception("File format not supported.");
            }

            if (move_uploaded_file($fileTmpPath, $filePath)) {
                $sql = "INSERT INTO restaurants 
                        (restaurant_pic,name, phone, address, city, state, zip_code, status) 
                        VALUES 
                        (:restaurant_pic, :rname, :rphone, :raddress, :rcity, :rstate, :rzip_code, :status)";

                $stmt = $this->con->prepare($sql);

                $stmt->bindParam(':restaurant_pic', $filePath);
                $stmt->bindParam(':rname', $rname);
                $stmt->bindParam(':rphone', $rphone);
                $stmt->bindParam(':raddress', $raddress);
                $stmt->bindParam(':rcity', $rcity);
                $stmt->bindParam(':rstate', $rstate);
                $stmt->bindParam(':rzip_code', $rzip_code);
                $stmt->bindParam(':status', $status);

                return $stmt->execute();
            } else {
                throw new Exception("Failed to move uploaded file.");
            }
        } catch (PDOException $e) {
            throw new Exception("Failed to add restaurant: " . $e->getMessage());
        }
    }

    function getAllRestaurants()
    {
        try {
            $sql = "SELECT * FROM restaurants";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch restaurants: " . $e->getMessage());
        }
    }

    function getRestaurantById($id)
    {
        try {
            $sql = "SELECT * FROM restaurants WHERE restaurant_id = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch restaurant: " . $e->getMessage());
        }
    }

    function updateRestaurant($id, $restaurant_pic, $rname, $rphone, $raddress, $rcity, $rstate, $rzip_code, $status)
    {
        try {
            $sql = "UPDATE restaurants SET name = :rname, phone = :rphone, address = :raddress, city = :rcity, state = :rstate, zip_code = :rzip_code, status = :status";

            if (!empty($restaurant_pic['name'])) {
                $fileName = $restaurant_pic['name'];
                $fileTmpPath = $restaurant_pic['tmp_name'];
                $uploadDir = 'uploads/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $filePath = $uploadDir . basename($fileName);

                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif','image/jpg'];
                $fileMimeType = mime_content_type($fileTmpPath);

                if (!in_array($fileMimeType, $allowedMimeTypes)) {
                    throw new Exception("File format not supported.");
                }

                if (move_uploaded_file($fileTmpPath, $filePath)) {
                    $sql .= ", restaurant_pic = :restaurant_pic";
                }
            }

            $sql .= " WHERE restaurant_id = :id";

            $stmt = $this->con->prepare($sql);

            $stmt->bindParam(':rname', $rname);
            $stmt->bindParam(':rphone', $rphone);
            $stmt->bindParam(':raddress', $raddress);
            $stmt->bindParam(':rcity', $rcity);
            $stmt->bindParam(':rstate', $rstate);
            $stmt->bindParam(':rzip_code', $rzip_code);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id);

            if (!empty($restaurant_pic['name'])) {
                $stmt->bindParam(':restaurant_pic', $filePath);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Restaurant update failed: " . $e->getMessage());
        }
    }

    function deleteRestaurant($id)
    {
        try {
            $sql = "DELETE FROM restaurants WHERE restaurant_id = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to delete restaurant: " . $e->getMessage());
        }
    }
}
?>