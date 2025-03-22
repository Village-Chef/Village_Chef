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


    function updateUserStatus($user_id, $status)
    {
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


    function deleteUser($user_id)
    {
        try {
            $sql = "DELETE FROM users WHERE user_id = :user_id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to delete user: " . $e->getMessage());
        }
    }

    function getUserById($id)
    {
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

    function addCuisine($cuisine_name, $description): bool
    {
        try {
            $sql = "INSERT INTO cuisines (cuisine_name, description) VALUES (:name, :description)";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':name', $cuisine_name);
            $stmt->bindParam(':description', $description);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to add cuisine: " . $e->getMessage());
        }
    }

    function getAllCuisines()
    {
        try {
            $sql = "SELECT * FROM cuisines";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch cuisines: " . $e->getMessage());
        }
    }

    function getCuisineById($id)
    {
        try {
            $sql = "SELECT * FROM cuisines WHERE cuisine_id = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch cuisine: " . $e->getMessage());
        }
    }

    function updateCuisine($id, $cuisine_name, $description)
    {
        try {
            $sql = "UPDATE cuisines SET cuisine_name = :name, description = :description WHERE cuisine_id = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':name', $cuisine_name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to update cuisine: " . $e->getMessage());
        }
    }

    function deleteCuisine($id)
    {
        try {
            $sql = "DELETE FROM cuisines WHERE cuisine_id = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to delete cuisine: " . $e->getMessage());
        }
    }

    function addMenuItem($restaurant_id, $cuisine_id, $item_name, $description, $price, $image_url, $is_available)
    {
        try {
            $fileName = $image_url['name'];
            $fileTmpPath = $image_url['tmp_name'];
            $uploadDir = 'uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $filePath = $uploadDir . basename($fileName);

            $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $fileMimeType = mime_content_type($fileTmpPath);

            if (!in_array($fileMimeType, $allowedMimeTypes)) {
                throw new Exception("File format not supported.");
            }

            if (move_uploaded_file($fileTmpPath, $filePath)) {
                $sql = "INSERT INTO menu_items 
                        (restaurant_id, cuisine_id, item_name, description, price, image_url, is_available) 
                        VALUES 
                        (:restaurant_id, :cuisine_id, :item_name, :description, :price, :image_url, :is_available)";

                $stmt = $this->con->prepare($sql);

                $stmt->bindParam(':restaurant_id', $restaurant_id);
                $stmt->bindParam(':cuisine_id', $cuisine_id);
                $stmt->bindParam(':item_name', $item_name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':image_url', $filePath);
                $stmt->bindParam(':is_available', $is_available);

                return $stmt->execute();
            } else {
                throw new Exception("Failed to move uploaded file.");
            }
        } catch (PDOException $e) {
            throw new Exception("Failed to add menu item: " . $e->getMessage());
        }
    }

    function getAllMenuItems()
    {
        try {
            $sql = "SELECT mi.*, r.name as restaurant_name, c.cuisine_name 
                    FROM menu_items mi
                    JOIN restaurants r ON mi.restaurant_id = r.restaurant_id
                    JOIN cuisines c ON mi.cuisine_id = c.cuisine_id";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch menu items: " . $e->getMessage());
        }
    }

    function getMenuItemById($id)
    {
        try {
            $sql = "SELECT * FROM menu_items WHERE item_id = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch menu item: " . $e->getMessage());
        }
    }

    function updateMenuItem($id, $restaurant_id, $cuisine_id, $item_name, $description, $price, $image_url, $is_available)
    {
        try {
            $sql = "UPDATE menu_items 
                    SET restaurant_id = :restaurant_id, cuisine_id = :cuisine_id, item_name = :item_name, description = :description, price = :price, is_available = :is_available";

            if (!empty($image_url['name'])) {
                $fileName = $image_url['name'];
                $fileTmpPath = $image_url['tmp_name'];
                $uploadDir = 'uploads/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $filePath = $uploadDir . basename($fileName);

                $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                $fileMimeType = mime_content_type($fileTmpPath);

                if (!in_array($fileMimeType, $allowedMimeTypes)) {
                    throw new Exception("File format not supported.");
                }

                if (move_uploaded_file($fileTmpPath, $filePath)) {
                    $sql .= ", image_url = :image_url";
                }
            }

            $sql .= " WHERE item_id = :id";

            $stmt = $this->con->prepare($sql);

            $stmt->bindParam(':restaurant_id', $restaurant_id);
            $stmt->bindParam(':cuisine_id', $cuisine_id);
            $stmt->bindParam(':item_name', $item_name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':is_available', $is_available);
            $stmt->bindParam(':id', $id);

            if (!empty($image_url['name'])) {
                $stmt->bindParam(':image_url', $filePath);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Menu item update failed: " . $e->getMessage());
        }
    }

    function deleteMenuItem($id)
    {
        try {
            $sql = "DELETE FROM menu_items WHERE item_id = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to delete menu item: " . $e->getMessage());
        }
    }
}
?>