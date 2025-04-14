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

    public function getUserByEmail($email)
    {
        try {
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch user by email: " . $e->getMessage());
        }
    }


    public function updatePassword($email, $password_hash)
    {
        try {

            $sql = "UPDATE users SET password_hash = :password_hash WHERE email = :email";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':password_hash', $password_hash);
            $stmt->bindParam(':email', $email);

            if ($stmt->execute()) {
                return true;
            } else {
                $errorInfo = $stmt->errorInfo();
                echo "Error updating password: " . implode(", ", $errorInfo) . "<br>";
                return false;
            }
        } catch (PDOException $e) {
            throw new Exception("Failed to update password: " . $e->getMessage());
        }
    }

    public function changePassword($user_id, $current_password, $new_password, $confirm_password)
    {
        try {
            // Check if new passwords match
            if ($new_password !== $confirm_password) {
                throw new Exception("New passwords do not match.");
            }

            // Fetch the current user data
            $user = $this->getUserById($user_id);

            // Verify the current password
            if (!password_verify($current_password, $user['password_hash'])) {
                throw new Exception("Current password is incorrect.");
            }

            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $sql = "UPDATE users SET password_hash = :password_hash WHERE user_id = :user_id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':password_hash', $hashed_password);
            $stmt->bindParam(':user_id', $user_id);

            if ($stmt->execute()) {
                return "Password changed successfully!";
            } else {
                throw new Exception("Failed to update the password.");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
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
        } catch (Exception $e) {
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
        } catch (Exception $e) {
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

    function getFilteredUsers($filters = [])
    {
        try {
            $sql = "SELECT * FROM users WHERE 1=1";

            // Add filters dynamically
            if (!empty($filters['search'])) {
                $sql .= " AND (first_name LIKE :search OR last_name LIKE :search OR email LIKE :search)";
            }
            if (!empty($filters['status'])) {
                $sql .= " AND status = :status";
            }
            if (!empty($filters['role'])) {
                $sql .= " AND role = :role";
            }

            $stmt = $this->con->prepare($sql);

            // Bind parameters
            if (!empty($filters['search'])) {
                $searchTerm = '%' . $filters['search'] . '%';
                $stmt->bindParam(':search', $searchTerm);
            }
            if (!empty($filters['status'])) {
                $stmt->bindParam(':status', $filters['status']);
            }
            if (!empty($filters['role'])) {
                $stmt->bindParam(':role', $filters['role']);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch filtered users: " . $e->getMessage());
        }
    }

    function getAllUserStatuses()
    {
        try {
            $sql = "SELECT DISTINCT status FROM users";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch only the status column
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch user statuses: " . $e->getMessage());
        }
    }

    function getAllUserRoles()
    {
        try {
            $sql = "SELECT DISTINCT role FROM users";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch only the role column
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch user roles: " . $e->getMessage());
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


    public function deleteUser($user_id)
    {
        try {
            $sql = "DELETE FROM users WHERE user_id = :user_id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
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

            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
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

    function getFilteredRestaurants($filters = [])
    {
        try {
            $sql = "SELECT * FROM restaurants WHERE 1=1";

            // Add filters dynamically
            if (!empty($filters['search'])) {
                $sql .= " AND (name LIKE :search OR address LIKE :search)";
            }
            if (!empty($filters['status'])) {
                $sql .= " AND status = :status";
            }

            $stmt = $this->con->prepare($sql);

            // Bind parameters
            if (!empty($filters['search'])) {
                $searchTerm = '%' . $filters['search'] . '%';
                $stmt->bindParam(':search', $searchTerm);
            }
            if (!empty($filters['status'])) {
                $stmt->bindParam(':status', $filters['status']);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch filtered restaurants: " . $e->getMessage());
        }
    }

    function getAllRestaurantStatuses()
    {
        try {
            $sql = "SELECT DISTINCT status FROM restaurants";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch only the status column
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch restaurant statuses: " . $e->getMessage());
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

                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
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

    function getFilteredCuisines($filters = [])
    {
        try {
            $sql = "SELECT * FROM cuisines WHERE 1=1";

            // Add search filter dynamically
            if (!empty($filters['search'])) {
                $sql .= " AND (cuisine_name LIKE :search OR description LIKE :search)";
            }

            $stmt = $this->con->prepare($sql);

            // Bind search parameter
            if (!empty($filters['search'])) {
                $searchTerm = '%' . $filters['search'] . '%';
                $stmt->bindParam(':search', $searchTerm);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch filtered cuisines: " . $e->getMessage());
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

    function addMenuItem($restaurant_id, $cuisine_id, $item_name, $description, $price, $image_url, $is_available, $tags)
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
                        (restaurant_id, cuisine_id, item_name, description, price, image_url, is_available, tags) 
                        VALUES 
                        (:restaurant_id, :cuisine_id, :item_name, :description, :price, :image_url, :is_available, :tags)";

                $stmt = $this->con->prepare($sql);

                $stmt->bindParam(':restaurant_id', $restaurant_id);
                $stmt->bindParam(':cuisine_id', $cuisine_id);
                $stmt->bindParam(':item_name', $item_name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':image_url', $filePath);
                $stmt->bindParam(':is_available', $is_available);
                $stmt->bindParam(':tags', $tags);

                return $stmt->execute();
            } else {
                throw new Exception("Failed to move uploaded file.");
            }
        } catch (PDOException $e) {
            throw new Exception("Failed to add menu item: " . $e->getMessage());
        }
    }

    function getFilteredMenuItems($filters = [])
    {
        try {
            $sql = "SELECT mi.*, r.name AS restaurant_name, c.cuisine_name 
                    FROM menu_items mi
                    JOIN restaurants r ON mi.restaurant_id = r.restaurant_id
                    JOIN cuisines c ON mi.cuisine_id = c.cuisine_id
                    WHERE 1=1";

            // Add filters dynamically
            if (!empty($filters['search'])) {
                $sql .= " AND (mi.item_name LIKE :search OR mi.description LIKE :search)";
            }
            if (!empty($filters['restaurant'])) {
                $sql .= " AND mi.restaurant_id = :restaurant";
            }
            if (!empty($filters['cuisine'])) {
                $sql .= " AND mi.cuisine_id = :cuisine";
            }
            // Check if 'availability' key exists and is not an empty string
            if (isset($filters['availability']) && $filters['availability'] !== '') {
                $sql .= " AND mi.is_available = :availability";
            }
            if (!empty($filters['price_range'])) {
                $range = explode('-', $filters['price_range']);
                if (count($range) == 2) {
                    $sql .= " AND mi.price BETWEEN :price_min AND :price_max";
                } elseif (strpos($filters['price_range'], '+') !== false) {
                    $sql .= " AND mi.price >= :price_min";
                }
            }

            $stmt = $this->con->prepare($sql);

            // Bind parameters
            if (!empty($filters['search'])) {
                $searchTerm = '%' . $filters['search'] . '%';
                $stmt->bindParam(':search', $searchTerm);
            }
            if (!empty($filters['restaurant'])) {
                $stmt->bindParam(':restaurant', $filters['restaurant'], PDO::PARAM_INT);
            }
            if (!empty($filters['cuisine'])) {
                $stmt->bindParam(':cuisine', $filters['cuisine'], PDO::PARAM_INT);
            }
            // Bind availability only if it exists and is not empty
            if (isset($filters['availability']) && $filters['availability'] !== '') {
                $stmt->bindParam(':availability', $filters['availability'], PDO::PARAM_INT);
            }
            if (!empty($filters['price_range'])) {
                $range = explode('-', $filters['price_range']);
                if (count($range) == 2) {
                    $stmt->bindParam(':price_min', $range[0]);
                    $stmt->bindParam(':price_max', $range[1]);
                } elseif (strpos($filters['price_range'], '+') !== false) {
                    $min = str_replace('+', '', $filters['price_range']);
                    $stmt->bindParam(':price_min', $min);
                }
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch filtered menu items: " . $e->getMessage());
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

    function updateMenuItem($id, $restaurant_id, $cuisine_id, $item_name, $description, $price, $image_url, $is_available, $tags)
    {
        try {
            $sql = "UPDATE menu_items 
                    SET restaurant_id = :restaurant_id, cuisine_id = :cuisine_id, item_name = :item_name, description = :description, price = :price, is_available = :is_available, tags = :tags";

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
            $stmt->bindParam(':tags', json_encode($tags));
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

    function getAllTags()
    {
        try {
            $sql = "SELECT * FROM tags";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch tags: " . $e->getMessage());
        }
    }

    function getAllOrders($filters = [])
    {
        try {
            $sql = "SELECT o.*, u.profile_pic, u.email, u.first_name, u.last_name, r.name as restaurant_name 
                FROM orders o
                JOIN users u ON o.user_id = u.user_id
                JOIN restaurants r ON o.restaurant_id = r.restaurant_id
                WHERE 1=1";

            // Add filters dynamically
            if (!empty($filters['status'])) {
                $sql .= " AND o.status = :status";
            }
            if (!empty($filters['restaurant'])) {
                $sql .= " AND r.name = :restaurant";
            }
            if (!empty($filters['date_from'])) {
                $sql .= " AND o.order_date >= :date_from";
            }
            if (!empty($filters['date_to'])) {
                $sql .= " AND o.order_date <= :date_to";
            }
            if (!empty($filters['search'])) {
                $sql .= " AND (o.order_id LIKE :search OR u.first_name LIKE :search OR u.last_name LIKE :search OR r.name LIKE :search)";
            }

            // Add default ordering by order_date
            $sql .= " ORDER BY o.order_date DESC";
            date_default_timezone_set('Asia/Kolkata');
            $stmt = $this->con->prepare($sql);

            // Bind parameters
            if (!empty($filters['status'])) {
                $stmt->bindParam(':status', $filters['status']);
            }
            if (!empty($filters['restaurant'])) {
                $stmt->bindParam(':restaurant', $filters['restaurant']);
            }
            if (!empty($filters['date_from'])) {
                $stmt->bindParam(':date_from', $filters['date_from']);
            }
            if (!empty($filters['date_to'])) {
                $stmt->bindParam(':date_to', $filters['date_to']);
            }
            if (!empty($filters['search'])) {
                $searchTerm = '%' . $filters['search'] . '%';
                $stmt->bindParam(':search', $searchTerm);
            }

            $stmt->execute();
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch order items for each order
            foreach ($orders as &$order) {
                $order['items'] = $this->getOrderItems($order['order_id']);
            }

            return $orders;
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch orders: " . $e->getMessage());
        }
    }
    function getAllOrderStatuses()
    {
        try {
            $sql = "SELECT DISTINCT status FROM orders";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch only the status column
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch order statuses: " . $e->getMessage());
        }
    }

    function updateOrderStatus($order_id, $status)
    {
        try {
            $sql = "UPDATE orders SET status = :status WHERE order_id = :order_id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':order_id', $order_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to update order status: " . $e->getMessage());
        }
    }

    function deleteOrder($order_id)
    {
        try {
            $sql = "DELETE FROM orders WHERE order_id = :order_id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':order_id', $order_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to delete order: " . $e->getMessage());
        }
    }

    function getOrderById($order_id)
    {
        try {
            // SQL Query with :order_id placeholder
            $sql = "SELECT 
                    o.order_id, 
                    o.user_id, 
                    o.restaurant_id, 
                    o.order_date, 
                    o.delivery_address, 
                    o.total_amount, 
                    o.status AS order_status, 
                    o.notes, 
                    u.first_name AS customer_first_name, 
                    u.last_name AS customer_last_name, 
                    r.name AS restaurant_name, 
                    p.payment_method,
                    p.payment_id , 
                    p.amount AS payment_amount, 
                    p.status AS payment_status, 
                    GROUP_CONCAT(mi.item_name SEPARATOR ', ') AS items, 
                    GROUP_CONCAT(oi.quantity SEPARATOR ', ') AS quantities,  
                    GROUP_CONCAT(oi.price SEPARATOR ', ') AS prices 
                FROM 
                    orders o 
                JOIN 
                    users u ON o.user_id = u.user_id 
                JOIN 
                    restaurants r ON o.restaurant_id = r.restaurant_id 
                LEFT JOIN 
                    payments p ON o.order_id = p.order_id 
                LEFT JOIN 
                    order_items oi ON o.order_id = oi.order_id 
                LEFT JOIN 
                    menu_items mi ON oi.item_id = mi.item_id 
                WHERE 
                    o.order_id = :order_id 
                GROUP BY 
                    o.order_id";

            // Prepare and execute the query
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_STR);
            $stmt->execute();

            // Fetch the result as an associative array
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch order: " . $e->getMessage());
        }
    }

    function getAllPayments($filters = [])
    {
        try {
            $sql = "SELECT 
                p.payment_id, 
                p.order_id, 
                p.amount, 
                p.payment_method, 
                p.status AS payment_status, 
                p.payment_date, 
                u.first_name, 
                u.last_name, 
                u.email 
            FROM payments p
            JOIN orders o ON p.order_id = o.order_id
            JOIN users u ON o.user_id = u.user_id
            WHERE 1=1";

            // Add filters dynamically
            if (!empty($filters['status'])) {
                $sql .= " AND p.status = :status";
            }
            if (!empty($filters['payment_method'])) {
                $sql .= " AND p.payment_method = :payment_method";
            }
            if (!empty($filters['date_from'])) {
                $sql .= " AND p.payment_date >= :date_from";
            }
            if (!empty($filters['date_to'])) {
                $sql .= " AND p.payment_date <= :date_to";
            }
            if (!empty($filters['amount_range'])) {
                $range = explode('-', $filters['amount_range']);
                if (count($range) == 2) {
                    $sql .= " AND p.amount BETWEEN :amount_min AND :amount_max";
                } elseif (strpos($filters['amount_range'], '+') !== false) {
                    $sql .= " AND p.amount >= :amount_min";
                }
            }
            if (!empty($filters['search'])) {
                $sql .= " AND (p.payment_id LIKE :search OR 
                           p.order_id LIKE :search OR 
                           u.first_name LIKE :search OR 
                           u.last_name LIKE :search OR 
                           u.email LIKE :search)";
            }

            // Add default ordering by payment_date
            $sql .= " ORDER BY p.payment_date DESC";

            $stmt = $this->con->prepare($sql);

            // Bind parameters
            if (!empty($filters['status'])) {
                $stmt->bindParam(':status', $filters['status']);
            }
            if (!empty($filters['payment_method'])) {
                $stmt->bindParam(':payment_method', $filters['payment_method']);
            }
            if (!empty($filters['date_from'])) {
                $stmt->bindParam(':date_from', $filters['date_from']);
            }
            if (!empty($filters['date_to'])) {
                $stmt->bindParam(':date_to', $filters['date_to']);
            }
            if (!empty($filters['amount_range'])) {
                $range = explode('-', $filters['amount_range']);
                if (count($range) == 2) {
                    $stmt->bindParam(':amount_min', $range[0]);
                    $stmt->bindParam(':amount_max', $range[1]);
                } elseif (strpos($filters['amount_range'], '+') !== false) {
                    $amountMin = str_replace('+', '', $filters['amount_range']); // Assign to a variable
                    $stmt->bindParam(':amount_min', $amountMin); // Use the variable here
                }
            }
            if (!empty($filters['search'])) {
                $searchTerm = '%' . $filters['search'] . '%';
                $stmt->bindParam(':search', $searchTerm);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch payments: " . $e->getMessage());
        }
    }

    function getPaymentMethods()
    {
        try {
            $sql = "SELECT DISTINCT payment_method FROM payments";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch only the payment_method column
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch payment methods: " . $e->getMessage());
        }
    }


    function getPaymentStatuses()
    {
        try {
            $sql = "SELECT DISTINCT status FROM payments";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch only the status column
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch payment statuses: " . $e->getMessage());
        }
    }

    public function updatePaymentStatus($payment_id)
    {
        try {
            $sql = "UPDATE payments SET status = 'refunded' WHERE payment_id = :payment_id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':payment_id', $payment_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to update payment status: " . $e->getMessage());
        }
    }
    function getPaymentSummary()
    {
        try {
            $sql = "
                SELECT 
                    COUNT(*) AS total_payments,
                    SUM(CASE WHEN status = 'successful' THEN 1 ELSE 0 END) AS successful_payments,
                    SUM(CASE WHEN status = 'successful' THEN amount ELSE 0 END) AS total_successful_amount,
                    SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) AS failed_payments,
                    SUM(CASE WHEN status = 'failed' THEN amount ELSE 0 END) AS total_failed_amount,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_payments,
                    SUM(CASE WHEN status = 'pending' THEN amount ELSE 0 END) AS total_pending_amount,
                    SUM(CASE WHEN status = 'refunded' THEN 1 ELSE 0 END) AS refunded_payments,
                    SUM(CASE WHEN status = 'refunded' THEN amount ELSE 0 END) AS total_refunded_payments,
                    SUM(amount) AS total_amount
                FROM payments
            ";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch payment summary: " . $e->getMessage());
        }
    }

    public function getPaymentDetails($paymentId)
    {
        try {
            $sql = "SELECT 
                p.payment_id, 
                p.order_id, 
                p.amount, 
                p.payment_method, 
                p.status AS payment_status, 
                p.payment_date, 
                u.first_name, 
                u.last_name, 
                u.email, 
                u.phone, 
                o.delivery_address, 
                o.total_amount AS subtotal
                FROM payments p
                JOIN orders o ON p.order_id = o.order_id
                JOIN users u ON o.user_id = u.user_id
                WHERE p.payment_id = :payment_id";

            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':payment_id', $paymentId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch payment details: " . $e->getMessage());
        }
    }

    function getOrderItems($order_id)
    {
        try {
            $sql = "SELECT 
                        oi.item_id, 
                        oi.quantity, 
                        oi.price, 
                        mi.item_name, 
                        mi.image_url, 
                        mi.description 
                    FROM order_items oi
                    JOIN menu_items mi ON oi.item_id = mi.item_id
                    WHERE oi.order_id = :order_id";

            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch order items: " . $e->getMessage());
        }
    }




    public function getFilteredReviews($filters)
    {
        $sql = "SELECT r.*, u.first_name, u.last_name, u.email, u.profile_pic, res.name as restaurant_name 
            FROM reviews r
            JOIN users u ON r.user_id = u.user_id
            JOIN restaurants res ON r.restaurant_id = res.restaurant_id
            WHERE 1=1";

        if (!empty($filters['rating'])) {
            $sql .= " AND r.rating = :rating";
        }
        if (!empty($filters['restaurant'])) {
            $sql .= " AND r.restaurant_id = :restaurant";
        }
        if (!empty($filters['status'])) {
            $sql .= " AND r.status = :status";
        }
        if (!empty($filters['date_from'])) {
            $sql .= " AND r.created_at >= :date_from";
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND r.created_at <= :date_to";
        }
        if (!empty($filters['search'])) {
            $sql .= " AND (u.first_name LIKE :search OR u.last_name LIKE :search OR r.review_text LIKE :search)";
        }

        $stmt = $this->con->prepare($sql);

        // Bind parameters
        if (!empty($filters['rating'])) {
            $stmt->bindValue(':rating', $filters['rating']);
        }
        if (!empty($filters['restaurant'])) {
            $stmt->bindValue(':restaurant', $filters['restaurant']);
        }
        if (!empty($filters['status'])) {
            $stmt->bindValue(':status', $filters['status']);
        }
        if (!empty($filters['date_from'])) {
            $stmt->bindValue(':date_from', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $stmt->bindValue(':date_to', $filters['date_to']);
        }
        if (!empty($filters['search'])) {
            $searchTerm = '%' . $filters['search'] . '%';
            $stmt->bindValue(':search', $searchTerm);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllReviewsWithDetails()
    {
        $sql = "SELECT r.*, u.first_name, u.last_name, u.email, u.profile_pic, res.name as restaurant_name 
                FROM reviews r
                JOIN users u ON r.user_id = u.user_id
                JOIN restaurants res ON r.restaurant_id = res.restaurant_id";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteReview($id)
    {
        $sql = "DELETE FROM reviews WHERE review_id = :id";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function updateReviewStatus($id, $status)
    {
        $sql = "UPDATE reviews SET status = :status WHERE review_id = :id";
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }






    // ! Cart & Cart_Items

    function addCart($user_id)
    {
        try {
            $sql = "INSERT INTO carts (user_id, status) 
                    VALUES (:user_id,'active')";

            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);

            if ($stmt->execute()) {
                return $this->con->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            throw new Exception("Failed to create cart: " . $e->getMessage());
        }
    }
    function getAllCarts()
    {
        try {
            $sql = "SELECT * FROM carts";

            $stmt = $this->con->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to get carts: " . $e->getMessage());
        }
    }

    function addCartItem($cart_id, $item_id, $quantity, $price)
    {
        try {
            $sql = "INSERT INTO cart_items (cart_id, item_id, quantity, price) 
                    VALUES (:cart_id, :item_id, :quantity, :price)";

            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':cart_id', $cart_id);
            $stmt->bindParam(':item_id', $item_id);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':price', $price);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to add cart item: " . $e->getMessage());
        }
    }

    function getCartItems($user_id)
    {
        try {
            $sql = "SELECT ci.*, mi.item_name, mi.image_url, mi.description, mi.tags 
                    FROM cart_items ci
                    JOIN carts c ON ci.cart_id = c.cart_id 
                    JOIN menu_items mi ON ci.item_id = mi.item_id
                    WHERE c.user_id = :user_id AND c.status = 'active'";

            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to get cart items: " . $e->getMessage());
        }
    }

    function updateCartItemQuantity($cart_id, $item_id, $quantity)
    {
        try {
            if ($quantity <= 0) {
                $sql = "DELETE FROM cart_items 
                        WHERE cart_id = :cart_id AND item_id = :item_id";

                $stmt = $this->con->prepare($sql);
                $stmt->bindParam(':cart_id', $cart_id);
                $stmt->bindParam(':item_id', $item_id);
            } else {
                $sql = "UPDATE cart_items 
                        SET quantity = :quantity 
                        WHERE cart_id = :cart_id AND item_id = :item_id";

                $stmt = $this->con->prepare($sql);
                $stmt->bindParam(':cart_id', $cart_id);
                $stmt->bindParam(':item_id', $item_id);
                $stmt->bindParam(':quantity', $quantity);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to update cart item quantity: " . $e->getMessage());
        }
    }



    public function deleteCartItem($cart_id, $item_id)
    {
        try {
            $sql = "DELETE FROM cart_items WHERE cart_id = :cart_id AND item_id = :item_id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':cart_id', $cart_id);
            $stmt->bindParam(':item_id', $item_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to delete cart item: " . $e->getMessage());
        }
    }

    public function updateUserAddress($id, $address)
    {
        try {
            // Prepare the SQL statement to update the user's address
            $sql = "UPDATE users SET address = :address WHERE user_id = :id";

            // Prepare the statement
            $stmt = $this->con->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':id', $id);

            // Execute the statement
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Address update failed: " . $e->getMessage());
        }
    }


    // Order Items
    public function deleteCartItemByCartId($cart_id)
    {
        try {
            $sql = "DELETE FROM cart_items WHERE cart_id = :cart_id ";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':cart_id', $cart_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to delete cart item: " . $e->getMessage());
        }
    }

    public function addOrder($user_id, $restaurant_id, $order_date, $delivery_address, $total_amount, $notes = null)
    {
        try {
            // Insert into orders table
            $sql = "INSERT INTO orders (user_id, restaurant_id, order_date, delivery_address, total_amount, notes) 
                        VALUES (:user_id, :restaurant_id, :order_date, :delivery_address, :total_amount, :notes)";

            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':restaurant_id', $restaurant_id);
            $stmt->bindParam(':order_date', $order_date);
            $stmt->bindParam(':delivery_address', $delivery_address);
            $stmt->bindParam(':total_amount', $total_amount);
            $stmt->bindParam(':notes', $notes);
            $stmt->execute();

            // Retrieve the generated order_id from the session variable [[6]]
            $stmt = $this->con->query("SELECT @last_order_id AS order_id");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $order_id = $result['order_id'];



            return $order_id;



            // // Return the custom order id (e.g. "#ORD-123")
            // return $result;

        } catch (PDOException $e) {
            throw new Exception("Failed to add order: " . $e->getMessage());
        }
    }

    function getRestaurantIdByItemId($item_id)
    {
        try {
            $sql = "SELECT restaurant_id FROM menu_items WHERE item_id = :item_id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':item_id', $item_id);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['restaurant_id'] : null; // Return restaurant_id or null if not found
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch restaurant ID: " . $e->getMessage());
        }
    }

    public function addOrderItems($order_id, $item_id, $quantity, $price)
    {
        try {
            $sql = "INSERT INTO order_items (order_id, item_id, quantity, price) 
                VALUES (:order_id, :item_id, :quantity, :price)";

            $stmt = $this->con->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':order_id', $order_id);
            $stmt->bindParam(':item_id', $item_id);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':price', $price);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to add order items: " . $e->getMessage());
        }
    }

    public function getOrdersByUserId($user_id)
    {
        try {
            $sql = "SELECT 
                    o.order_id, 
                    o.order_date, 
                    o.delivery_address, 
                    o.total_amount, 
                    o.notes, 
                    o.status, 
                    r.name AS restaurant_name,
                    GROUP_CONCAT(oi.item_id) AS item_ids,
                    GROUP_CONCAT(oi.quantity) AS quantities,
                    GROUP_CONCAT(oi.price) AS prices,
                    GROUP_CONCAT(mi.item_name) AS item_names,
                    GROUP_CONCAT(mi.image_url) AS image_urls,
                    p.payment_id,
                    p.payment_method,
                    p.amount AS payment_amount,
                    p.status AS payment_status
                FROM orders o
                JOIN restaurants r ON o.restaurant_id = r.restaurant_id
                JOIN order_items oi ON o.order_id = oi.order_id
                JOIN menu_items mi ON oi.item_id = mi.item_id
                LEFT JOIN payments p ON o.order_id = p.order_id
                WHERE o.user_id = :user_id
                GROUP BY o.order_id
                ORDER BY o.order_date DESC";

            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch orders for user: " . $e->getMessage());
        }
    }



    function getOrderDetailsById($order_id)
    {
        try {
            // SQL Query to fetch order details along with user, restaurant, payment, and review information
            $sql = "
            SELECT 
                o.order_id, 
                o.user_id, 
                o.restaurant_id, 
                o.order_date, 
                o.delivery_address, 
                o.total_amount, 
                o.status AS order_status, 
                o.notes, 
                u.first_name AS customer_first_name, 
                u.last_name AS customer_last_name, 
                u.email AS customer_email, 
                r.name AS restaurant_name, 
                r.address AS restaurant_address, 
                p.payment_id,  
                p.payment_method, 
                p.amount AS payment_amount, 
                p.status AS payment_status, 
                GROUP_CONCAT(DISTINCT mi.item_name ORDER BY mi.item_id SEPARATOR ', ') AS items, 
                GROUP_CONCAT(DISTINCT oi.quantity ORDER BY oi.item_id SEPARATOR ', ') AS quantities,  
                GROUP_CONCAT(DISTINCT oi.price ORDER BY oi.item_id SEPARATOR ', ') AS prices,
                GROUP_CONCAT(DISTINCT mi.image_url ORDER BY mi.item_id SEPARATOR ', ') AS image_urls,
                rv.review_id, 
                rv.rating, 
                rv.review_text, 
                rv.status AS review_status, 
                rv.created_at AS review_created_at
            FROM 
                orders o 
            JOIN 
                users u ON o.user_id = u.user_id 
            JOIN 
                restaurants r ON o.restaurant_id = r.restaurant_id 
            LEFT JOIN 
                payments p ON o.order_id = p.order_id 
            LEFT JOIN 
                order_items oi ON o.order_id = oi.order_id 
            LEFT JOIN 
                menu_items mi ON oi.item_id = mi.item_id 
            LEFT JOIN 
                reviews rv ON o.order_id = rv.order_id 
            WHERE 
                o.order_id = :order_id 
            GROUP BY 
                o.order_id
        ";

            // Prepare and execute the statement
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_STR); // Ensure it's bound as a string
            $stmt->execute();

            // Fetch the result as an associative array
            $orderDetails = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if any order details were found
            if (!$orderDetails) {
                throw new Exception("No order found with the provided order ID.");
            }

            return $orderDetails;
        } catch (PDOException $e) {
            throw new Exception("Database error while fetching order details: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function addReview($user_id, $restaurant_id, $order_id, $rating, $review_text, $status = 'archived')
    {
        try {
            $sql = "INSERT INTO reviews (user_id, restaurant_id, order_id, rating, review_text, status, created_at, updated_at) 
                VALUES (:user_id, :restaurant_id, :order_id, :rating, :review_text, :status, NOW(), NOW())";

            $stmt = $this->con->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':restaurant_id', $restaurant_id);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':review_text', $review_text);
            $stmt->bindParam(':status', $status);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to add review: " . $e->getMessage());
        }
    }


    public function getRestaurantReviews($restaurant_id)
    {
        try {
            $sql = "SELECT 
                    rv.review_id, 
                    rv.user_id, 
                    rv.order_id, 
                    rv.rating, 
                    rv.review_text, 
                    rv.status, 
                    rv.created_at, 
                    u.first_name AS user_first_name, 
                    u.last_name AS user_last_name, 
                    u.profile_pic AS user_profile_pic
                FROM reviews rv
                JOIN users u ON rv.user_id = u.user_id
                WHERE rv.restaurant_id = :restaurant_id
                ORDER BY rv.created_at DESC";

            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':restaurant_id', $restaurant_id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch reviews for the restaurant: " . $e->getMessage());
        }
    }


    public function calculateAverageRating($restaurant_id)
    {
        try {
            $reviews = $this->getRestaurantReviews($restaurant_id);

            if (count($reviews) === 0) {
                return null; // No reviews available
            }

            $totalRating = 0;
            foreach ($reviews as $review) {
                $totalRating += $review['rating'];
            }

            $averageRating = $totalRating / count($reviews);
            return round($averageRating, 1); // Round to 1 decimal place
        } catch (Exception $e) {
            throw new Exception("Failed to calculate average rating: " . $e->getMessage());
        }
    }


    // public function getOrderItemsByOrderId($order_id)
    // {
    //     try {
    //         $sql = "SELECT 
    //                 oi.item_id, 
    //                 oi.quantity, 
    //                 oi.price, 
    //                 mi.item_name, 
    //                 mi.image_url
    //             FROM order_items oi
    //             JOIN menu_items mi ON oi.item_id = mi.item_id
    //             WHERE oi.order_id = :order_id";

    //         $stmt = $this->con->prepare($sql);
    //         $stmt->bindParam(':order_id', $order_id);
    //         $stmt->execute();

    //         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     } catch (PDOException $e) {
    //         throw new Exception("Failed to fetch order items: " . $e->getMessage());
    //     }
    // }

    public function addPayment($order_id, $amount, $payment_method, $transaction_id = null, $status = 'pending')
    {
        try {
            $sql = "INSERT INTO payments (order_id, amount, payment_method, transaction_id, status) 
                VALUES (:order_id, :amount, :payment_method, :transaction_id, :status)";

            $stmt = $this->con->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':order_id', $order_id);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':payment_method', $payment_method);
            $stmt->bindParam(':transaction_id', $transaction_id);
            $stmt->bindParam(':status', $status);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to add payment: " . $e->getMessage());
        }
    }

    public function updateUserProfile($user_id, $first_name, $last_name, $email, $phone, $address, $profile_pic = null)
    {
        try {
            // Base SQL query to update user details
            $sql = "UPDATE users 
                    SET first_name = :first_name, 
                        last_name = :last_name, 
                        email = :email, 
                        phone = :phone, 
                        address = :address";

            // Check if a new profile picture is provided
            if (!empty($profile_pic['name'])) {
                $fileName = $profile_pic['name'];
                $fileTmpPath = $profile_pic['tmp_name'];
                $uploadDir = '../AdminPanel/uploads/'; // Absolute path for moving the file
                $relativePath = 'uploads/' . basename($fileName); // Relative path for storing in DB

                // Ensure the upload directory exists
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $filePath = $uploadDir . basename($fileName);

                // Validate file type
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $fileMimeType = mime_content_type($fileTmpPath);

                if (!in_array($fileMimeType, $allowedMimeTypes)) {
                    throw new Exception("File format not supported.");
                }

                // Move the uploaded file to the target directory
                if (move_uploaded_file($fileTmpPath, $filePath)) {
                    $sql .= ", profile_pic = :profile_pic";
                } else {
                    throw new Exception("Failed to upload profile picture.");
                }
            }

            $sql .= " WHERE user_id = :user_id";

            // Prepare the SQL statement
            $stmt = $this->con->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':user_id', $user_id);

            // Bind the profile picture path if provided
            if (!empty($profile_pic['name'])) {
                $stmt->bindParam(':profile_pic', $relativePath); // Store relative path in DB
            }

            // Execute the query
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Failed to update user profile: " . $e->getMessage());
        }
    }

    // ! Search 

    public function search($query)
    {
        try {
            $sql = "
                SELECT 
                    'restaurant' AS type,
                    r.restaurant_id AS rest_id,
                    r.name AS rest_name,
                    r.address AS rest_description,
                    r.city AS rest_city,
                    r.state AS rest_state,
                    r.zip_code AS rest_zip_code,
                    r.phone AS rest_phone,
                    r.restaurant_pic AS rest_image_url,
                    r.status AS rest_status,
                    NULL AS item_id, -- Placeholder for menu item-specific columns
                    NULL AS item_name,
                    NULL AS item_description,
                    NULL AS item_price,
                    NULL AS item_image_url,
                    NULL AS item_availability,
                    NULL AS item_tags,
                    NULL AS item_restaurant_name,
                    NULL AS item_restaurant_status -- Placeholder for restaurant status in menu items
                FROM restaurants r
                WHERE r.name LIKE :query OR r.address LIKE :query
    
                UNION ALL
    
                SELECT 
                    'menu_item' AS type,
                    NULL AS rest_id, -- Placeholder for restaurant-specific columns
                    NULL AS rest_name,
                    NULL AS rest_description,
                    NULL AS rest_city,
                    NULL AS rest_state,
                    NULL AS rest_zip_code,
                    NULL AS rest_phone,
                    NULL AS rest_image_url,
                    NULL AS rest_status,
                    mi.item_id AS item_id,
                    mi.item_name AS item_name,
                    mi.description AS item_description,
                    mi.price AS item_price,
                    mi.image_url AS item_image_url,
                    mi.is_available AS item_availability,
                    mi.tags AS item_tags,
                    r.name AS item_restaurant_name,
                    r.status AS item_restaurant_status -- Fetch restaurant status for menu items
                FROM menu_items mi
                JOIN restaurants r ON mi.restaurant_id = r.restaurant_id
                WHERE mi.item_name LIKE :query OR mi.description LIKE :query
            ";

            $stmt = $this->con->prepare($sql);

            // Bind the search query with wildcards
            $searchTerm = '%' . $query . '%';
            $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Search failed: " . $e->getMessage());
        }
    }

    public function getAllResults()
    {
        try {
            $sql = "
            SELECT 
                'restaurant' AS type,
                r.restaurant_id AS rest_id,
                r.name AS rest_name,
                r.address AS rest_description,
                r.city AS rest_city,
                r.state AS rest_state,
                r.zip_code AS rest_zip_code,
                r.phone AS rest_phone,
                r.restaurant_pic AS rest_image_url,
                r.status AS rest_status,
                NULL AS item_id,
                NULL AS item_name,
                NULL AS item_description,
                NULL AS item_price,
                NULL AS item_image_url,
                NULL AS item_availability,
                NULL AS item_tags,
                NULL AS item_restaurant_name,
                NULL AS item_restaurant_status
            FROM restaurants r

            UNION ALL

            SELECT 
                'menu_item' AS type,
                NULL AS rest_id,
                NULL AS rest_name,
                NULL AS rest_description,
                NULL AS rest_city,
                NULL AS rest_state,
                NULL AS rest_zip_code,
                NULL AS rest_phone,
                NULL AS rest_image_url,
                NULL AS rest_status,
                mi.item_id AS item_id,
                mi.item_name AS item_name,
                mi.description AS item_description,
                mi.price AS item_price,
                mi.image_url AS item_image_url,
                mi.is_available AS item_availability,
                mi.tags AS item_tags,
                r.name AS item_restaurant_name,
                r.status AS item_restaurant_status
            FROM menu_items mi
            JOIN restaurants r ON mi.restaurant_id = r.restaurant_id
        ";

            $stmt = $this->con->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch all results: " . $e->getMessage());
        }
    }

    public function getRecentUsers($limit)
    {
        try {
            $sql = "SELECT * FROM users ORDER BY created_at DESC LIMIT :limit";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch recent users: " . $e->getMessage());
        }
    }

    public function getRecentRestaurants($limit)
    {
        try {
            $sql = "SELECT * FROM restaurants ORDER BY created_at DESC LIMIT :limit";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch recent restaurants: " . $e->getMessage());
        }
    }

    public function getRecentMenuItems($limit)
    {
        try {
            $sql = "SELECT mi.*, r.name as restaurant_name 
                    FROM menu_items mi 
                    JOIN restaurants r ON mi.restaurant_id = r.restaurant_id 
                    ORDER BY mi.created_at DESC LIMIT :limit";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch recent menu items: " . $e->getMessage());
        }
    }

    public function getRecentOrders($limit)
    {
        try {
            $sql = "SELECT o.*, u.first_name, u.last_name, r.name as restaurant_name 
                    FROM orders o 
                    JOIN users u ON o.user_id = u.user_id 
                    JOIN restaurants r ON o.restaurant_id = r.restaurant_id 
                    ORDER BY o.order_date DESC LIMIT :limit";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch recent orders: " . $e->getMessage());
        }
    }

    public function getRecentReviews($limit)
    {
        try {
            $sql = "SELECT r.*, u.first_name, u.last_name, res.name as restaurant_name 
                    FROM reviews r 
                    JOIN users u ON r.user_id = u.user_id 
                    JOIN restaurants res ON r.restaurant_id = res.restaurant_id 
                    ORDER BY r.created_at DESC LIMIT :limit";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch recent reviews: " . $e->getMessage());
        }
    }


    function getMenuItemByIdForDisplay($id)
    {
        try {
            $sql = "SELECT mi.*, r.name as restaurant_name, c.cuisine_name 
                FROM menu_items mi
                JOIN restaurants r ON mi.restaurant_id = r.restaurant_id
                JOIN cuisines c ON mi.cuisine_id = c.cuisine_id
                WHERE mi.item_id = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch menu item: " . $e->getMessage());
        }
    }

    function getRelatedMenuItems($restaurant_id, $exclude_item_id, $limit = 4)
    {
        try {
            $sql = "SELECT mi.*, r.name as restaurant_name, c.cuisine_name 
                FROM menu_items mi
                JOIN restaurants r ON mi.restaurant_id = r.restaurant_id
                JOIN cuisines c ON mi.cuisine_id = c.cuisine_id
                WHERE mi.restaurant_id = :restaurant_id AND mi.item_id != :exclude_item_id
                LIMIT :limit";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':restaurant_id', $restaurant_id);
            $stmt->bindParam(':exclude_item_id', $exclude_item_id);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch related menu items: " . $e->getMessage());
        }
    }

    function getMenuItemReviews($item_id)
    {
        try {
            $sql = "SELECT r.*, u.first_name, u.last_name, u.email, u.profile_pic
                FROM reviews r
                JOIN users u ON r.user_id = u.user_id
                JOIN orders o ON r.order_id = o.order_id
                JOIN order_items oi ON o.order_id = oi.order_id
                WHERE oi.item_id = :item_id
                GROUP BY r.review_id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':item_id', $item_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch menu item reviews: " . $e->getMessage());
        }
    }

    public function getTopCustomers($limit = 5)
    {
        try {
            $sql = "SELECT u.user_id, u.first_name, u.last_name, u.email, COUNT(o.order_id) as order_count
                FROM users u
                JOIN orders o ON u.user_id = o.user_id
                GROUP BY u.user_id
                ORDER BY order_count DESC
                LIMIT :limit";

            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch top customers: " . $e->getMessage());
        }
    }
    function getAllMenuItemsWithCuisineMap()
    {
        try {
            // Fetch all cuisines and create a map (cuisine_id => cuisine_name)
            $cuisines = $this->getAllCuisines();
            $cuisineMap = [];
            foreach ($cuisines as $cuisine) {
                $cuisineMap[$cuisine['cuisine_id']] = $cuisine['cuisine_name'];
            }

            // Fetch menu items with restaurant info (no cuisine join)
            $sql = "SELECT mi.*, r.name as restaurant_name 
                FROM menu_items mi
                JOIN restaurants r ON mi.restaurant_id = r.restaurant_id";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Attach cuisine_name to each menu item using the map
            foreach ($menuItems as &$item) {
                $item['cuisine_name'] = $cuisineMap[$item['cuisine_id']] ?? 'Unknown';
            }
            return $menuItems;
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch menu items: " . $e->getMessage());
        }
    }
}



