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
    </style>
</head>

<body class="min-h-screen bg-black text-white">

    <?php require "navbar.php"; 

    if(isset(($_SESSION['user']['user_id']))){
        $uid= $_SESSION['user']['user_id'];
        $cartItems = $obj->getCartItems($uid);
        $currentUser = $obj->getUserById($uid);
        $userdata = $obj->getUserById($uid);
        $usersAllOrders = $obj->getOrdersByUserId($uid);
    }

    // $getitems=$obj->getOrderItemsByOrderId();
    

    $totalOrders = 0;
    foreach ($usersAllOrders as $usersorders) {
        $totalOrders += 1;
    }

    ?>


    <!-- Account Navigation -->
    <div class="pt-20">
        <div class="bg-zinc-900 py-4 border-b border-zinc-800">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex overflow-x-auto no-scrollbar space-x-6 py-2">
                    <a href="account_user.php"
                        class="text-yellow-500 whitespace-nowrap border-b-2 border-yellow-500 pb-2">
                        <i class="fa-solid fa-clock-rotate-left mr-2"></i>Profile
                    </a>
                    <a href="orders_user.php"
                        class="text-gray-400 hover:text-yellow-500 whitespace-nowrap transition-colors">
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

    <?php
    if (isset($_SESSION['user'])) {
        ?>
        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold">Profile</h1>
                    <p class="text-gray-400 mt-1">View and manage your profile</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="profileEdit.php"
                        class="flex items-center bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg transition-colors">
                        <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Profile
                    </a>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Left Column - Profile Picture and Status -->
                <div class="md:col-span-1">
                    <div
                        class="profile-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 p-6 flex flex-col items-center">
                        <div class="relative mb-4">
                            <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-yellow-500">
                                <?php
                                $userProfile = $userdata['profile_pic'] ? $userdata['profile_pic'] : '';
                                ?>
                                <img src="../AdminPanel/<?php echo $userProfile ? $userProfile : 'uploads/dp.png'; ?>"
                                    alt="Profile Picture" class="w-full h-full object-cover" />
                            </div>
                            <div
                                class="absolute bottom-0 right-0 bg-green-500 w-6 h-6 rounded-full border-4 border-zinc-900">
                            </div>
                        </div>
                        <h2 class="text-xl font-bold mb-1"><?php echo $userdata['first_name']; ?>
                            <?php echo $userdata['last_name']; ?>
                        </h2>
                        <p class="text-yellow-500 text-sm mb-4">Premium Member</p>
                        <div class="bg-zinc-800 text-green-500 px-3 py-1 rounded-full text-sm font-medium mb-4">
                            Active
                        </div>
                        <div class="w-full border-t border-zinc-800 pt-4 mt-2">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-gray-400 text-sm">Member Since</span>
                                <span class="text-white">
                                    <?php
                                    $date = new DateTime($userdata['created_at']);
                                    echo $date->format('M d, Y'); // Outputs: Mar 22, 2023
                                    ?>
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400 text-sm">Last Updated</span>
                                <span class="text-white">
                                    <?php
                                    $date = new DateTime($userdata['updated_at'] == null ? 'Not Updated' : $userdata['updated_at']);
                                    echo $date->format('M d, Y'); // Outputs: Mar 22, 2023
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Account Actions -->
                    <div class="profile-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 p-6 mt-6">
                        <h3 class="text-lg font-semibold mb-4">Account Actions</h3>
                        <div class="space-y-3">
                            <a href="#" class="flex items-center text-gray-400 hover:text-yellow-500 transition-colors">
                                <i class="fa-solid fa-lock mr-3 w-5 text-center"></i>
                                <span>Change Password</span>
                            </a>
                            <a href="#" class="flex items-center text-gray-400 hover:text-yellow-500 transition-colors">
                                <i class="fa-solid fa-bell mr-3 w-5 text-center"></i>
                                <span>Notification Settings</span>
                            </a>
                            <a href="#" class="flex items-center text-gray-400 hover:text-yellow-500 transition-colors">
                                <i class="fa-solid fa-shield-alt mr-3 w-5 text-center"></i>
                                <span>Privacy Settings</span>
                            </a>
                            <a href="#" class="flex items-center text-gray-400 hover:text-yellow-500 transition-colors">
                                <i class="fa-solid fa-trash mr-3 w-5 text-center"></i>
                                <span>Delete Account</span>
                            </a>
                            <div class="pt-3 border-t border-zinc-800 mt-3">
                                <a href="logout.php"
                                    class="flex items-center text-red-500 hover:text-red-400 transition-colors">
                                    <i class="fa-solid fa-sign-out-alt mr-3 w-5 text-center"></i>
                                    <span>Logout</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Profile Details -->
                <div class="md:col-span-2">
                    <div class="profile-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 p-6">
                        <h3 class="text-lg font-semibold mb-4">Personal Information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- First Name -->
                            <div>
                                <label class="block text-gray-400 text-sm mb-1">First Name</label>
                                <div class="bg-zinc-800 px-4 py-3 rounded-lg text-white">
                                    <?php echo $userdata['first_name']; ?>
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label class="block text-gray-400 text-sm mb-1">Last Name</label>
                                <div class="bg-zinc-800 px-4 py-3 rounded-lg text-white">
                                    <?php echo $userdata['last_name']; ?>
                                </div>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-gray-400 text-sm mb-1">Email</label>
                                <div class="bg-zinc-800 px-4 py-3 rounded-lg text-white"><?php echo $userdata['email']; ?>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="block text-gray-400 text-sm mb-1">Phone</label>
                                <div class="bg-zinc-800 px-4 py-3 rounded-lg text-white"><?php echo $userdata['phone']; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="profile-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 p-6 mt-6">
                        <h3 class="text-lg font-semibold mb-4">Address</h3>

                        <div class="bg-zinc-800 p-4 rounded-lg text-white">
                            <p><?php echo $userdata['address']; ?></p>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <div class="profile-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 p-6 mt-6">
                        <h3 class="text-lg font-semibold mb-4">Account Information</h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center pb-3 border-b border-zinc-800">
                                <span class="text-gray-400">Account Type</span>
                                <span
                                    class="bg-yellow-500/10 text-yellow-500 px-3 py-1 rounded-full text-sm font-medium">Premium</span>
                            </div>

                            <div class="flex justify-between items-center pb-3 border-b border-zinc-800">
                                <span class="text-gray-400">Role</span>
                                <span class="text-white">Customer</span>
                            </div>

                            <div class="flex justify-between items-center pb-3 border-b border-zinc-800">
                                <span class="text-gray-400">Orders Placed</span>
                                <span class="text-white"><?php echo $totalOrders; ?></span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Loyalty Points</span>
                                <span class="text-yellow-500 font-semibold">1,250 pts</span>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="profile-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 p-6 mt-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Recent Activity</h3>
                            <a href="orders_user.php" class="text-yellow-500 text-sm hover:underline">View All</a>
                        </div>

                        <div class="space-y-4">
                            <?php

                            foreach ($usersAllOrders as $orders) {
                                ?>
                                <div class="flex items-start">
                                    <div class="bg-zinc-800 p-2 rounded-full mr-3">
                                        <i class="fa-solid fa-shopping-bag text-yellow-500"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-white">You placed an order <span
                                                class="text-yellow-500"><?php echo $orders['order_id'] ?></span></p>
                                        <p class="text-gray-400 text-sm">
                                            <?php
                                            $date = new DateTime($orders['order_date']);
                                            echo $date->format('M d, Y \a\t g:i A');
                                            ?>
                                    </div>
                                </div>
                                <?php
                            }

                            ?>


                            <div class="flex items-start">
                                <div class="bg-zinc-800 p-2 rounded-full mr-3">
                                    <i class="fa-solid fa-star text-yellow-500"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-white">You reviewed <span class="text-yellow-500">Domino's Special
                                            Pizza</span></p>
                                    <p class="text-gray-400 text-sm">May 13, 2023 at 2:15 PM</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="bg-zinc-800 p-2 rounded-full mr-3">
                                    <i class="fa-solid fa-heart text-yellow-500"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-white">You added <span class="text-yellow-500">Classic Burger
                                            Combo</span> to favorites</p>
                                    <p class="text-gray-400 text-sm">May 10, 2023 at 1:45 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php
    } else {
        ?>
        <!-- Profile Header -->
        <div class="flex flex-col items-center gap-6 my-10">
            <!-- Profile Image -->
            <img src="../AdminPanel/uploads/dp.png" alt="Profile Picture"
                class="w-32 h-32 rounded-full border-2 border-white object-cover shadow-md">

            <!-- Profile Details -->
            <div class="text-center md:text-left">
                <h1 class="text-2xl font-bold text-center text-yellow-500">Guest</h1>
                <p class="text-sm text-gray-500">Member since Today</p>
            </div>
            <a href="login.php" class="bg-yellow-500 rounded-full cursor-pointer text-black px-3 py-2">
                Login
            </a>
        </div>
        <?php
    }
    ?>



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
                        <input type="email" placeholder="Your Email"
                            class="px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-l-md text-white">
                        <button class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-r-md">
                            Send
                        </button>
                    </div>
                </div>
            </div>

            <div class="border-t border-zinc-800 pt-8 text-center text-gray-500 text-sm">
                <p>&copy;
                    <script>
                        document.write(new Date().getFullYear())
                    </script> Village Chef. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</body>

</html>