<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}
require '../dbCon.php';
$obj = new Foodies();
$id = $_GET['id'];
$result = $obj->getRestaurantById($id);
$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnAddRestaurant'])) {
    $rname = $_POST['rname'];
    $rphone = $_POST['rphone'];
    $raddress = $_POST['raddress'];
    $rcity = $_POST['rcity'];
    $rstate = $_POST['rstate'];
    $rzip_code = $_POST['rzip_code'];
    $status = $_POST['status'];
    $restaurant_pic = $_FILES['restaurant_pic'];

    try {

        if (empty($rname) || empty($rphone) || empty($raddress) || empty($rcity) || empty($rstate) || empty($rzip_code)) {
            throw new Exception("All fields are required.");
        }

        if (!preg_match("/^[0-9]{10}$/", $rphone)) {
            throw new Exception("Invalid phone number format. Please enter a 10-digit number.");
        }

        if (!preg_match("/^[0-9]{6}$/", $rzip_code)) {
            throw new Exception("Invalid zip code format. Please enter a 6-digit number.");
        }

        if (!in_array($status, ['open', 'closed', 'inactive'])) {
            throw new Exception("Invalid status selected.");
        }




        if ($restaurant_pic['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($restaurant_pic['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Error uploading the restaurant image.");
            }

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($restaurant_pic['type'], $allowedTypes)) {
                throw new Exception("Invalid image format. Only JPG, PNG, and GIF are allowed.");
            }

            if ($restaurant_pic['size'] > 2 * 1024 * 1024) { // 2MB limit
                throw new Exception("Image size exceeds the 2MB limit.");
            }
        }



        if ($obj->updateRestaurant($id, $restaurant_pic, $rname, $rphone, $raddress, $rcity, $rstate, $rzip_code, $status)) {
            $_SESSION['success'] = "Restaurant Updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update restaurant. Please try again.";
        }
        header('location:restaurants.php');
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Restaurant | Food Ordering System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-primary text-gray-100">
    <div class="flex h-screen overflow-hidden">
        <?php include 'sidebar.php'; ?>

        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <?php include 'header.php'; ?>

            <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
                <div class="max-w-4xl mx-auto">
                    <!-- Page Header -->
                    <div class="mb-8">
                        <h1 class="text-2xl font-bold text-accent">Update Restaurant</h1>
                        <p class="text-gray-400 mt-1">Update a new restaurant in the system</p>
                    </div>

                    <?php if ($error): ?>
                        <div class="mb-6 p-4 bg-red-900/30 border border-red-800 rounded-xl text-red-400">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="mb-6 p-4 bg-green-900/30 border border-green-800 rounded-xl text-green-400">
                            <?php echo $success; ?>
                        </div>
                    <?php endif; ?>
                    <!-- Add Restaurant Form -->
                    <form class="bg-gray-800 rounded-2xl border border-gray-700 p-6 space-y-6" method="POST"
                        enctype="multipart/form-data">

                        <!-- Image Upload -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-300">Restaurant Image</label>
                            <div class="mt-1 flex flex-col items-center">
                                <div class="relative w-48 h-48 rounded-2xl border-2 border-dashed border-gray-600 hover:border-accent transition-colors cursor-pointer group"
                                    onclick="document.getElementById('restaurantImage').click()">
                                    <img id="imagePreview" class="w-full h-full object-cover rounded-2xl"
                                        src="<?php echo $result['restaurant_pic'] ?>" alt="Preview">
                                    <div
                                        class="absolute inset-0 flex flex-col items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl">
                                        <i class="fas fa-camera text-2xl text-accent mb-2"></i>
                                        <span class="text-sm text-accent">Click to upload</span>
                                    </div>
                                </div>
                                <input type="file" id="restaurantImage" name="restaurant_pic" accept="image/*"
                                    class="hidden" onchange="previewImage(event)">
                            </div>
                        </div>

                        <!-- Form Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Restaurant Name</label>
                                    <input type="text" name="rname" value="<?php echo $result['name'] ?>"
                                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-accent focus:ring-1 focus:ring-accent">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Phone Number</label>
                                    <input type="tel" name="rphone" value="<?php echo $result['phone'] ?>"
                                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-accent focus:ring-1 focus:ring-accent">
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Address</label>
                                    <input type="text" name="raddress" value="<?php echo $result['address'] ?>"
                                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-accent focus:ring-1 focus:ring-accent">
                                </div>

                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-2">City</label>
                                        <input type="text" name="rcity" value="<?php echo $result['city'] ?>"
                                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-accent focus:ring-1 focus:ring-accent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-2">State</label>
                                        <input type="text" name="rstate" value="<?php echo $result['state'] ?>"
                                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-accent focus:ring-1 focus:ring-accent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-2">Zip Code</label>
                                        <input type="text" name="rzip_code" value="<?php echo $result['zip_code'] ?>"
                                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-accent focus:ring-1 focus:ring-accent">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                                    <select name="status" value="<?php echo $result['status'] ?>"
                                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-accent focus:ring-1 focus:ring-accent">
                                        <option value="open" class="bg-gray-800">Open</option>
                                        <option value="closed" class="bg-gray-800">Closed</option>
                                        <option value="inactive" class="bg-gray-800">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4 pt-6">
                            <a href="restaurants.php"
                                class="px-6 py-2 border border-gray-600 rounded-xl hover:bg-gray-700/30 transition-colors cursor-pointer">Back</a>
                            <input type="reset" value="Reset"
                                class="px-6 py-2 border border-gray-600 rounded-xl hover:bg-gray-700/30 transition-colors cursor-pointer" />
                            <input type="submit" name="btnAddRestaurant" value="Update Restaurant"
                                class="px-6 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors cursor-pointer" />
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            const preview = document.getElementById('imagePreview');
            const file = event.target.files[0];

            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('Invalid image format. Only JPG, PNG, and GIF are allowed.');
                event.target.value = '';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Image size exceeds the 2MB limit.');
                event.target.value = '';
                return;
            }

            reader.onload = function () {
                preview.src = reader.result;
            };

            reader.readAsDataURL(file);
        }
    </script>
</body>

</html>