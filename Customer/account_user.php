<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<body class="min-h-screen bg-black text-white">

    <?php

    require "navbar.php";
    $ActivePage = "Account";

    if (isset(($_SESSION['user']['user_id']))) {
        $uid = $_SESSION['user']['user_id'];
        $cartItems = $obj->getCartItems($uid);
        $currentUser = $obj->getUserById($uid);
        $userdata = $obj->getUserById($uid);
        $usersAllOrders = $obj->getOrdersByUserId($uid);
        $totalOrders = 0;
        foreach ($usersAllOrders as $usersorders) {
            $totalOrders += 1;
        }
    }

    // $getitems=$obj->getOrderItemsByOrderId();




    ?>
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


        /* From Uiverse.io by pathikcomp */
        .main>.inp {
            display: none;
        }

        .main {
            position: fixed !important;
            font-weight: 800;
            color: white;
            background-color: #eab308;
            padding: 3px 15px;
            border-radius: 10px;

            display: flex;
            align-items: center;
            height: 2.5rem;
            width: 8rem;
            position: relative;
            cursor: pointer;
            justify-content: space-between;
        }

        .arrow {
            height: 34%;
            aspect-ratio: 1;
            margin-block: auto;
            position: relative;
            display: flex;
            justify-content: center;
            transition: all 0.3s;
        }

        .arrow::after,
        .arrow::before {
            content: "";
            position: absolute;
            background-color: white;
            height: 100%;
            width: 2.5px;
            border-radius: 500px;
            transform-origin: bottom;
        }

        .arrow::after {
            transform: rotate(35deg) translateX(-0.5px);
        }

        .arrow::before {
            transform: rotate(-35deg) translateX(0.5px);
        }

        .main>.inp:checked+.arrow {
            transform: rotateX(180deg);
        }

        .menu-container {
            background-color: white;
            color: #eab308;
            border-radius: 10px;
            position: absolute;
            width: 100%;
            left: 0;
            bottom: 120%;
            overflow: hidden;
            clip-path: inset(0% 0% 0% 0% round 10px);
            transition: all 0.4s;
        }

        .menu-list {
            --delay: 0.4s;
            --trdelay: 0.15s;
            padding: 8px 10px;
            border-radius: inherit;
            transition: background-color 0.2s 0s;
            position: relative;
            transform: translateY(30px);
            opacity: 0;
        }

        .menu-list::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            height: 1px;
            background-color: rgba(0, 0, 0, 0.3);
            width: 95%;
        }

        .menu-list:hover {
            background-color: rgb(223, 223, 223);
        }

        .inp:checked~.menu-container {
            clip-path: inset(10% 50% 90% 50% round 10px);
        }

        .inp:not(:checked)~.menu-container .menu-list {
            transform: translateY(0);
            opacity: 1;
        }

        .inp:not(:checked)~.menu-container .menu-list:nth-child(1) {
            transition:
                transform 0.4s var(--delay),
                opacity 0.4s var(--delay);
        }

        .inp:not(:checked)~.menu-container .menu-list:nth-child(2) {
            transition:
                transform 0.4s calc(var(--delay) + (var(--trdelay) * 1)),
                opacity 0.4s calc(var(--delay) + (var(--trdelay) * 1));
        }

        .inp:not(:checked)~.menu-container .menu-list:nth-child(3) {
            transition:
                transform 0.4s calc(var(--delay) + (var(--trdelay) * 2)),
                opacity 0.4s calc(var(--delay) + (var(--trdelay) * 2));
        }

        .inp:not(:checked)~.menu-container .menu-list:nth-child(4) {
            transition:
                transform 0.4s calc(var(--delay) + (var(--trdelay) * 3)),
                opacity 0.4s calc(var(--delay) + (var(--trdelay) * 3));
        }

        .bar-inp {
            -webkit-appearance: none;
            display: none;
            visibility: hidden;
        }

        .bar {
            display: flex;
            height: 50%;
            width: 20px;
            flex-direction: column;
            gap: 3px;
        }

        .bar-list {
            --transform: -25%;
            display: block;
            width: 100%;
            height: 3px;
            border-radius: 50px;
            background-color: white;
            transition: all 0.4s;
            position: relative;
        }

        .inp:not(:checked)~.bar>.top {
            transform-origin: top right;
            transform: translateY(var(--transform)) rotate(-45deg);
        }

        .inp:not(:checked)~.bar>.middle {
            transform: translateX(-50%);
            opacity: 0;
        }

        .inp:not(:checked)~.bar>.bottom {
            transform-origin: bottom right;
            transform: translateY(calc(var(--transform) * -1)) rotate(45deg);
        }
    </style>

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
                    <!-- <a href="#" class="text-gray-400 hover:text-yellow-500 whitespace-nowrap transition-colors">
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
                    </a> -->
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
                                    if (!empty($userdata['updated_at'])) {
                                        $date = new DateTime($userdata['updated_at']);
                                        echo $date->format('M d, Y'); // Outputs: Mar 22, 2023
                                    } else {
                                        echo "Not Updated"; // Fallback message
                                    }
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
                                <a onclick="logout()"
                                    class="flex items-center cursor-pointer text-red-500 hover:text-red-400 transition-colors">
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

    <label class="main fixed bottom-0 right-0 m-4">
        Menu
        <input class="inp" checked="" type="checkbox" />
        <div class="bar">
            <span class="top bar-list"></span>
            <span class="middle bar-list"></span>
            <span class="bottom bar-list"></span>
        </div>
        <section class="menu-container">
            <div class="menu-list"><a href="menu.php">Menu</a></div>
            <div class="menu-list"><a href="cart.php">Cart</a></div>
            <div class="menu-list"><a href="orders_user.php">Past Orders</a></div>
            <div class="menu-list"><a href="account_user.php">Account</a></div>
        </section>
    </label>

    <script>
        function logout() {
            Swal.fire({
                title: "Are you sure?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, logout!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "logout.php";
                    Swal.fire("Logged out!", "You have been logged out.", "success");
                }
            });
        }
    </script>



    <!-- Footer -->
    <?php require("footer.php") ?>
</body>

</html>