<!DOCTYPE html>
<html lang="en" class="bg-black">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Village Chef - Past Orders</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/@themesberg/flowbite@latest/dist/flowbite.bundle.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .mobile-menu.open {
            max-height: 300px;
        }

        .profile-card {
            transition: all 0.3s ease;
        }

        .profile-card:hover {
            box-shadow: 0 10px 25px -5px rgba(249, 180, 42, 0.1);
        }

        /* Hide scrollbar but allow scrolling */
        .no-scrollbar {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari, Opera */
        }

        /* Custom file input */
        .custom-file-input::-webkit-file-upload-button {
            visibility: hidden;
            display: none;
        }

        .custom-file-input::before {
            content: 'Choose Photo';
            display: inline-block;
            background: #374151;
            color: white;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            outline: none;
            white-space: nowrap;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .custom-file-input:hover::before {
            background: #4B5563;
        }

        .custom-file-input:active::before {
            background: #374151;
        }
    </style>
</head>


<body class="min-h-screen bg-black text-white">

    <?php
    require "navbar.php";
    require '../dbCon.php';
    $obj = new Foodies();

    $uid = $_SESSION['user']['user_id'];
    $cartItems = $obj->getCartItems($uid);
    $currentUser = $obj->getUserById($uid);
    $userdata = $obj->getUserById($uid);
    // $getitems=$obj->getOrderItemsByOrderId();

    $usersAllOrders = $obj->getOrdersByUserId($uid);

   
    ?>


    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Get form data
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $phone = $_POST['phone'];
        $address = $_POST['address_line1'];
        $email = $userdata['email'];
        $uidDB = $userdata['user_id'];

        // Handle profile picture upload
        $file = null;
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['profile_pic'];
        }

        // Update user profile
        $obj->updateUserProfile($uidDB, $first_name, $last_name, $email, $phone, $address, $file);
        echo "<script> window.location.href='account_user.php'; </script>";
    }
    ?>




    <!-- Account Navigation -->
    <div class="pt-20">
        <div class="bg-zinc-900 py-4 border-b border-zinc-800">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex overflow-x-auto no-scrollbar space-x-6 py-2">
                    <a href="account_user.php" class="text-yellow-500 whitespace-nowrap border-b-2 border-yellow-500 pb-2">
                        <i class="fa-solid fa-clock-rotate-left mr-2"></i>Profile
                    </a>
                    <a href="orders_user.php" class="text-gray-400 hover:text-yellow-500 whitespace-nowrap transition-colors">
                        <i class="fa-solid fa-heart mr-2"></i>Past Orders
                    </a>
                    <a href="#" class="text-gray-400 hover:text-yellow-500 whitespace-nowrap transition-colors">
                        <i class="fa-solid fa-heart mr-2"></i>Favorites
                    </a>
                    <a href="#" class="text-gray-400 hover:text-yellow-500 whitespace-nowrap transition-colors">
                        <i class="fa-solid fa-address-book mr-2"></i>Addresses
                    </a>
                    <a href="#" class="text-gray-400 hover:text-yellow-500 whitespace-nowrap transition-colors">
                        <i class="fa-solid fa-credit-card mr-2"></i>Payment Methods
                    </a>
                    <a href="#" class="text-gray-400 hover:text-yellow-500 whitespace-nowrap transition-colors">
                        <i class="fa-solid fa-bell mr-2"></i>Notifications
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Edit Profile</h1>
                <p class="text-gray-400 mt-1">Update your personal information</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="account_user.php" class="flex items-center bg-zinc-800 hover:bg-zinc-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Back to Profile
                </a>
            </div>
        </div>



        <!-- Edit Profile Form -->
        <form method="POST" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Left Column - Profile Picture -->
                <div class="md:col-span-1">
                    <div class="profile-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 p-6">
                        <h3 class="text-lg font-semibold mb-4">Profile Picture</h3>

                        <div class="flex flex-col items-center">
                            <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-yellow-500 mb-4">
                                <?php
                                $userProfile = $userdata['profile_pic'] ? $userdata['profile_pic'] : '';
                                ?>
                                <img src="../AdminPanel/<?php echo $userProfile ? $userProfile : 'uploads/dp.png'; ?>" alt="Profile Picture" class="w-full h-full object-cover" id="profile-preview" />
                            </div>

                            <div class="space-y-3 w-full">
                                <label class="block text-center text-gray-400 text-sm mb-1">Upload New Picture</label>
                                <input type="file" name="profile_pic" id="profile_pic" accept="image/*" class="flex justify-center justify-items-center custom-file-input w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-zinc-800 file:text-white hover:file:bg-zinc-700" onchange="previewImage(this)" />
                            </div>
                        </div>

                        <!-- <div class="mt-6 pt-4 border-t border-zinc-800">
                            <h4 class="font-medium mb-3">Account Status</h4>
                            <div class="flex items-center">
                                <select name="status" class="bg-zinc-800 border border-zinc-700 text-white rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
                                    <option value="active" selected>Active</option>
                                    <option value="away">Away</option>
                                    <option value="busy">Busy</option>
                                    <option value="offline">Offline</option>
                                </select>
                            </div>
                        </div> -->
                    </div>
                </div>

                <!-- Right Column - Profile Details -->
                <div class="md:col-span-2">
                    <!-- Personal Information -->
                    <div class="profile-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 p-6">
                        <h3 class="text-lg font-semibold mb-4">Personal Information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- First Name -->
                            <div>
                                <label for="first_name" class="block text-gray-400 text-sm mb-1">First Name</label>
                                <input type="text" id="first_name" name="first_name" value="<?php echo $userdata['first_name']; ?>" class="bg-zinc-800 border border-zinc-700 text-white rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" required />
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="last_name" class="block text-gray-400 text-sm mb-1">Last Name</label>
                                <input type="text" id="last_name" name="last_name" value="<?php echo $userdata['last_name']; ?>" class="bg-zinc-800 border border-zinc-700 text-white rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" required />
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-gray-400 text-sm mb-1">Email</label>
                                <div class="bg-zinc-800 px-4 py-3 rounded-lg text-white"><?php echo $userdata['email']; ?></div>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-gray-400 text-sm mb-1">Phone</label>
                                <input type="tel" id="phone" name="phone" value="<?php echo $userdata['phone']; ?>" class="bg-zinc-800 border border-zinc-700 text-white rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" />
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="profile-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 p-6 mt-6">
                        <h3 class="text-lg font-semibold mb-4">Address</h3>

                        <div class="space-y-4">
                            <div>
                                <label for="address_line1" class="block text-gray-400 text-sm mb-1">Complete Address</label>
                                <input type="text" id="address_line1" name="address_line1" value="<?php echo $userdata['address']; ?>" class="bg-zinc-800 border border-zinc-700 text-white rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" />
                            </div>
                        </div>
                    </div>

                    <!-- Password Change -->
                    <!-- <div class="profile-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 p-6 mt-6">
                        <h3 class="text-lg font-semibold mb-4">Change Password</h3>

                        <div class="space-y-4">
                            <div>
                                <label for="current_password" class="block text-gray-400 text-sm mb-1">Current Password</label>
                                <input type="password" id="current_password" name="current_password" placeholder="••••••••" class="bg-zinc-800 border border-zinc-700 text-white rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" />
                            </div>

                            <div>
                                <label for="new_password" class="block text-gray-400 text-sm mb-1">New Password</label>
                                <input type="password" id="new_password" name="new_password" placeholder="••••••••" class="bg-zinc-800 border border-zinc-700 text-white rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" />
                            </div>

                            <div>
                                <label for="confirm_password" class="block text-gray-400 text-sm mb-1">Confirm New Password</label>
                                <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" class="bg-zinc-800 border border-zinc-700 text-white rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5" />
                            </div>

                            <p class="text-sm text-gray-400">Leave password fields empty if you don't want to change it.</p>
                        </div>
                    </div> -->

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 mt-8">
                        <a href="profile.php" class="bg-zinc-800 hover:bg-zinc-700 text-white px-6 py-3 rounded-lg transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-3 rounded-lg transition-colors">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <!-- Footer -->
    <footer class="bg-zinc-900 pt-16 pb-8 mt-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="mr-2">
                            <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                                <i class="fa-solid fa-utensils text-black"></i>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-yellow-500 font-bold italic text-xl leading-none">Village</span>
                            <span class="font-bold text-xl leading-none">CHEF</span>
                        </div>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Bringing restaurant-quality meals to your doorstep since 2020.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">
                            <i class="fa-brands fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-lg mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">Home</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">Menu</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">Contact</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">FAQ</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-lg mb-4">Contact Us</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>123 Culinary Street, Foodville</li>
                        <li>+1 (555) 123-4567</li>
                        <li>info@villagechef.com</li>
                        <li>Mon-Sun: 10:00 AM - 10:00 PM</li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-lg mb-4">Newsletter</h4>
                    <p class="text-gray-400 mb-4">Subscribe to get special offers and updates.</p>
                    <div class="flex">
                        <input
                            type="email"
                            placeholder="Your Email"
                            class="px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-l-md text-white">
                        <button class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-r-md">
                            Send
                        </button>
                    </div>
                </div>
            </div>

            <div class="border-t border-zinc-800 pt-8 text-center text-gray-500 text-sm">
                <p>&copy; <script>
                        document.write(new Date().getFullYear())
                    </script> Village Chef. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <script>
        // Profile image preview
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    document.getElementById('profile-preview').src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>