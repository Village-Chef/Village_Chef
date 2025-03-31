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

        .order-card {
            transition: all 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(249, 180, 42, 0.1);
        }

        .status-delivered {
            background-color: rgba(34, 197, 94, 0.1);
            color: rgb(34, 197, 94);
        }

        .status-pending {
            background-color: rgba(249, 180, 42, 0.1);
            color: rgb(249, 180, 42);
        }

        .status-cancelled {
            background-color: rgba(239, 68, 68, 0.1);
            color: rgb(239, 68, 68);
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
    $usersAllOrders ="";
    if(isset(($_SESSION['user']['user_id']))){
        $uid = $_SESSION['user']['user_id'];
        $cartItems = $obj->getCartItems($uid);
        $currentUser = $obj->getUserById($uid);
        $userdata = $obj->getUserById($uid);
        // $getitems=$obj->getOrderItemsByOrderId();
        $usersAllOrders = $obj->getOrdersByUserId($uid);
    }
   

    $filter = "all";
    if (isset($_GET['filter'])) {
        $filter = $_GET['filter'];
    }

    ?>


    <!-- Account Navigation -->
    <div class="pt-20">
        <div class="bg-zinc-900 py-4 border-b border-zinc-800">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex overflow-x-auto no-scrollbar space-x-6 py-2">
                    <a href="account_user.php" class="text-gray-400 hover:text-yellow-500 whitespace-nowrap transition-colors">
                        <i class="fa-solid fa-user-circle mr-2"></i>Profile
                    </a>
                    <a href="#" class="text-yellow-500 whitespace-nowrap border-b-2 border-yellow-500 pb-2">
                        <i class="fa-solid fa-clock-rotate-left mr-2"></i>Past Orders
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


        <!-- Orders List -->
        <div class="space-y-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold">Past Orders</h1>
                    <p class="text-gray-400 mt-1">View and manage your order history</p>
                </div>

                <!-- Filter Dropdown -->
                <div class="mt-4 md:mt-0 flex items-center space-x-4">
                    <button class="flex gap-3 cursor-pointer w-fit rounded-full text-md px-4 py-2 items-center bg-zinc-900 text-yellow-500" type="button" data-dropdown-toggle="filter-dropdown">
                        Filter Orders
                        <svg class="h-5 w-5 stroke-[2px] cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Dropdown menu -->
                    <div class="hidden text-base z-50 list-none bg-zinc-900 divide-y divide-gray-100 rounded shadow my-4" id="filter-dropdown">
                        <ul class="py-1" aria-labelledby="dropdown">
                            <li>
                                <a href="?filter=all" class="text-sm hover:bg-zinc-800 bg-zinc-900 text-white block px-4 py-2">All Orders</a>
                            </li>
                            <li>
                                <a href="?filter=delivered" class="text-sm hover:bg-zinc-800 bg-zinc-900 text-white block px-4 py-2">Delivered</a>
                            </li>
                            <li>
                                <a href="?filter=pending" class="text-sm hover:bg-zinc-800 bg-zinc-900 text-white block px-4 py-2">Processing</a>
                            </li>
                            <li>
                                <a href="?filter=cancelled" class="text-sm hover:bg-zinc-800 bg-zinc-900 text-white block px-4 py-2">Cancelled</a>
                            </li>
                        </ul>
                    </div>

                    <!-- <button class="flex gap-3 cursor-pointer w-fit rounded-full text-md px-4 py-2 items-center bg-zinc-900 text-yellow-500" type="button" data-dropdown-toggle="sort-dropdown">
                    Sort By
                    <svg class="h-5 w-5 stroke-[2px] cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button> -->

                    <!-- Dropdown menu -->
                    <!-- <div class="hidden text-base z-50 list-none bg-zinc-900 divide-y divide-gray-100 rounded shadow my-4" id="sort-dropdown">
                    <ul class="py-1" aria-labelledby="dropdown">
                        <li>
                            <a href="?sort=newest" class="text-sm hover:bg-zinc-800 bg-zinc-900 text-white block px-4 py-2">Newest First</a>
                        </li>
                        <li>
                            <a href="?sort=oldest" class="text-sm hover:bg-zinc-800 bg-zinc-900 text-white block px-4 py-2">Oldest First</a>
                        </li>
                        <li>
                            <a href="?sort=highest" class="text-sm hover:bg-zinc-800 bg-zinc-900 text-white block px-4 py-2">Highest Amount</a>
                        </li>
                        <li>
                            <a href="?sort=lowest" class="text-sm hover:bg-zinc-800 bg-zinc-900 text-white block px-4 py-2">Lowest Amount</a>
                        </li>
                    </ul>
                </div> -->
                </div>
            </div>
            <?php
            if ($usersAllOrders == null) {
            ?>

                <!-- No Orders Content -->
                <div class="bg-zinc-900 rounded-xl border border-zinc-800 p-8 md:p-16">
                    <div class="flex flex-col items-center justify-center text-center">
                        <div class="w-32 h-32 md:w-40 md:h-40 mb-6 empty-orders-animation">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-zinc-700">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                <line x1="8" y1="21" x2="16" y2="21"></line>
                                <line x1="12" y1="17" x2="12" y2="21"></line>
                                <path d="M6 8h.01M6 12h.01M6 16h.01M10 8h8M10 12h8M10 16h8"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl md:text-2xl font-bold mb-3">No orders found</h2>
                        <p class="text-gray-400 max-w-md mb-8">You haven't placed any orders yet. Browse our menu to discover delicious meals and place your first order!</p>

                        <div class="space-y-4 md:space-y-0 md:space-x-4 flex flex-col md:flex-row">
                            <a href="menu.php" class="bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-3 rounded-lg transition-colors flex items-center justify-center">
                                <i class="fa-solid fa-utensils mr-2"></i> Browse Menu
                            </a>
                            <a href="cart.php" class="bg-zinc-800 hover:bg-zinc-700 text-white px-6 py-3 rounded-lg transition-colors flex items-center justify-center">
                                <i class="fa-solid fa-shopping-cart mr-2"></i> Go to Cart
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Special Offers -->
                <div class="mt-12">
                    <h2 class="text-xl font-bold mb-6">Special Offers</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Offer 1 -->
                        <div class="bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 group hover:border-yellow-500/30 transition-all duration-300">
                            <div class="relative">
                                <div class="h-48 overflow-hidden">
                                    <img src="Assets/pizza2.png" alt="Special Offer" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                </div>
                                <div class="absolute top-4 right-4 bg-yellow-500 text-black px-3 py-1 rounded-full text-sm font-bold">
                                    20% OFF
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-lg font-semibold mb-2">First Order Special</h3>
                                <p class="text-gray-400 mb-4">Get 20% off on your first order with us. Use code WELCOME20 at checkout.</p>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-sm text-gray-400">Valid until:</span>
                                        <span class="text-white ml-2">June 30, 2023</span>
                                    </div>
                                    <a href="menu.php" class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg text-sm transition-colors">
                                        Order Now
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- Offer 2 -->
                        <div class="bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 group hover:border-yellow-500/30 transition-all duration-300">
                            <div class="relative">
                                <div class="h-48 overflow-hidden">
                                    <img src="Assets/burger.png" alt="Special Offer" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                </div>
                                <div class="absolute top-4 right-4 bg-yellow-500 text-black px-3 py-1 rounded-full text-sm font-bold">
                                    FREE
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-lg font-semibold mb-2">Free Delivery Weekend</h3>
                                <p class="text-gray-400 mb-4">Enjoy free delivery on all orders over ₹500 this weekend. No code needed.</p>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-sm text-gray-400">Valid until:</span>
                                        <span class="text-white ml-2">Sunday, May 28</span>
                                    </div>
                                    <a href="menu.php" class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg text-sm transition-colors">
                                        Order Now
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- Offer 2 -->
                        <div class="bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 group hover:border-yellow-500/30 transition-all duration-300">
                            <div class="relative">
                                <div class="h-48 overflow-hidden">
                                    <img src="Assets/icecream.png" alt="Special Offer" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                </div>
                                <div class="absolute top-4 right-4 bg-yellow-500 text-black px-3 py-1 rounded-full text-sm font-bold">
                                    FREE
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-lg font-semibold mb-2">Free Delivery Weekend</h3>
                                <p class="text-gray-400 mb-4">Enjoy free delivery on all orders over ₹500 this weekend. No code needed.</p>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-sm text-gray-400">Valid until:</span>
                                        <span class="text-white ml-2">Sunday, May 28</span>
                                    </div>
                                    <a href="menu.php" class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg text-sm transition-colors">
                                        Order Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                foreach ($usersAllOrders as $orders) {
                    $image_urls = explode(',', $orders['image_urls']);
                    $quan_urls = explode(',', $orders['quantities']);


                    if ($filter === "all" || $orders['status'] == $filter) {
                ?>

                        <div class="order-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 hover:border-yellow-500/30">
                            <div class="p-6">
                                <div class="flex flex-col md:flex-row justify-between mb-4">
                                    <div>
                                        <div class="flex items-center">
                                            <h3 class="text-lg font-semibold">Order <?php echo $orders['order_id']; ?></h3>
                                            <?php $normalizedStatus = $orders['status'] === 'out_for_delivery' ? 'pending' : $orders['status']; ?>
                                            <span class="ml-3 px-3 py-1 text-xs font-medium rounded-full <?php echo $normalizedStatus ? 'status-' . strtolower($normalizedStatus) : ''; ?>">
                                                <?php echo ucfirst(str_replace('_', ' ', $orders['status'])); ?>
                                            </span>
                                        </div>
                                        <p class="text-gray-400 text-sm mt-1">Placed on
                                            <?php
                                            $date = new DateTime($orders['order_date']);
                                            echo $date->format('F j, Y \a\t g:i A');
                                            ?>
                                    </div>
                                    <div class="mt-4 md:mt-0">
                                        <p class="text-sm text-gray-400">Total Amount</p>
                                        <p class="text-xl font-bold text-yellow-500">₹<?php echo $orders['total_amount'] ?></p>
                                    </div>
                                </div>

                                <div class="border-t border-zinc-800 pt-4">
                                    <div class="flex flex-wrap gap-4 items-center">
                                        <div class="flex-1 min-w-[200px]">
                                            <div class="flex items-center">
                                                <div class="h-16 w-16 rounded-lg overflow-hidden bg-zinc-800 mr-4">
                                                    <?php
                                                    foreach ($image_urls as $image_url) {
                                                    ?>
                                                        <img src="../AdminPanel/<?php echo $image_url; ?>" alt="Domino's Special Pizza" class="h-full w-full object-cover" />
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div>
                                                    <h4 class="font-medium"><?php echo $orders['restaurant_name'] ?></h4>
                                                    <p class="text-gray-400 text-sm">
                                                        <?php
                                                        // foreach ($quan_urls as $quantity) {
                                                        //     $quan = 0;
                                                        //     echo $quan += $quantity;
                                                        // }
                                                        $totalQuan = 0;
                                                        foreach ($quan_urls as $value) {
                                                            $totalQuan += $value;
                                                        }
                                                        echo $totalQuan;
                                                        ?>

                                                        items</p>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-wrap gap-3">
                                            <button class="bg-zinc-800 hover:bg-zinc-700 text-white px-4 py-2 rounded-lg text-sm transition-colors flex items-center">
                                                <i class="fa-solid fa-receipt mr-2"></i> View Details
                                            </button>
                                            <!-- <button class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg text-sm transition-colors flex items-center">
                                            <i class="fa-solid fa-redo mr-2"></i> Reorder
                                        </button> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
            <?php
                    }
                }
            }
            // print_r($usersAllOrders);

            ?>
            <!-- <div class="order-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 hover:border-yellow-500/30">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between mb-4">
                        <div>
                            <div class="flex items-center">
                                <h3 class="text-lg font-semibold">Order #VCF8721</h3>
                                <span class="ml-3 px-3 py-1 text-xs font-medium rounded-full status-delivered">
                                    Delivered
                                </span>
                            </div>
                            <p class="text-gray-400 text-sm mt-1">Placed on May 12, 2023 at 7:30 PM</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <p class="text-sm text-gray-400">Total Amount</p>
                            <p class="text-xl font-bold text-yellow-500">₹1,249</p>
                        </div>
                    </div>

                    <div class="border-t border-zinc-800 pt-4">
                        <div class="flex flex-wrap gap-4 items-center">
                            <div class="flex-1 min-w-[200px]">
                                <div class="flex items-center">
                                    <div class="h-16 w-16 rounded-lg overflow-hidden bg-zinc-800 mr-4">
                                        <img src="Assets/pizza2.png" alt="Domino's Special Pizza" class="h-full w-full object-cover" />
                                    </div>
                                    <div>
                                        <h4 class="font-medium">Domino's Special Pizza</h4>
                                        <p class="text-gray-400 text-sm">2 items</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <button class="bg-zinc-800 hover:bg-zinc-700 text-white px-4 py-2 rounded-lg text-sm transition-colors flex items-center">
                                    <i class="fa-solid fa-receipt mr-2"></i> View Details
                                </button>
                                <button class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg text-sm transition-colors flex items-center">
                                    <i class="fa-solid fa-redo mr-2"></i> Reorder
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="order-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 hover:border-yellow-500/30">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between mb-4">
                        <div>
                            <div class="flex items-center">
                                <h3 class="text-lg font-semibold">Order #VCF8654</h3>
                                <span class="ml-3 px-3 py-1 text-xs font-medium rounded-full status-processing">
                                    Processing
                                </span>
                            </div>
                            <p class="text-gray-400 text-sm mt-1">Placed on May 10, 2023 at 1:15 PM</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <p class="text-sm text-gray-400">Total Amount</p>
                            <p class="text-xl font-bold text-yellow-500">₹849</p>
                        </div>
                    </div>

                    <div class="border-t border-zinc-800 pt-4">
                        <div class="flex flex-wrap gap-4 items-center">
                            <div class="flex-1 min-w-[200px]">
                                <div class="flex items-center">
                                    <div class="h-16 w-16 rounded-lg overflow-hidden bg-zinc-800 mr-4">
                                        <img src="Assets/burger.png" alt="Classic Burger" class="h-full w-full object-cover" />
                                    </div>
                                    <div>
                                        <h4 class="font-medium">Classic Burger Combo</h4>
                                        <p class="text-gray-400 text-sm">3 items</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <button class="bg-zinc-800 hover:bg-zinc-700 text-white px-4 py-2 rounded-lg text-sm transition-colors flex items-center">
                                    <i class="fa-solid fa-receipt mr-2"></i> View Details
                                </button>
                                <button class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg text-sm transition-colors flex items-center">
                                    <i class="fa-solid fa-redo mr-2"></i> Reorder
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="order-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 hover:border-yellow-500/30">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between mb-4">
                        <div>
                            <div class="flex items-center">
                                <h3 class="text-lg font-semibold">Order #VCF8432</h3>
                                <span class="ml-3 px-3 py-1 text-xs font-medium rounded-full status-cancelled">
                                    Cancelled
                                </span>
                            </div>
                            <p class="text-gray-400 text-sm mt-1">Placed on May 5, 2023 at 8:45 PM</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <p class="text-sm text-gray-400">Total Amount</p>
                            <p class="text-xl font-bold text-yellow-500">₹1,599</p>
                        </div>
                    </div>

                    <div class="border-t border-zinc-800 pt-4">
                        <div class="flex flex-wrap gap-4 items-center">
                            <div class="flex-1 min-w-[200px]">
                                <div class="flex items-center">
                                    <div class="h-16 w-16 rounded-lg overflow-hidden bg-zinc-800 mr-4">
                                        <img src="Assets/pizza2.png" alt="Family Feast" class="h-full w-full object-cover" />
                                    </div>
                                    <div>
                                        <h4 class="font-medium">Family Feast Combo</h4>
                                        <p class="text-gray-400 text-sm">5 items</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <button class="bg-zinc-800 hover:bg-zinc-700 text-white px-4 py-2 rounded-lg text-sm transition-colors flex items-center">
                                    <i class="fa-solid fa-receipt mr-2"></i> View Details
                                </button>
                                <button class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg text-sm transition-colors flex items-center">
                                    <i class="fa-solid fa-redo mr-2"></i> Reorder
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="order-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 hover:border-yellow-500/30">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between mb-4">
                        <div>
                            <div class="flex items-center">
                                <h3 class="text-lg font-semibold">Order #VCF8321</h3>
                                <span class="ml-3 px-3 py-1 text-xs font-medium rounded-full status-delivered">
                                    Delivered
                                </span>
                            </div>
                            <p class="text-gray-400 text-sm mt-1">Placed on April 28, 2023 at 12:30 PM</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <p class="text-sm text-gray-400">Total Amount</p>
                            <p class="text-xl font-bold text-yellow-500">₹749</p>
                        </div>
                    </div>

                    <div class="border-t border-zinc-800 pt-4">
                        <div class="flex flex-wrap gap-4 items-center">
                            <div class="flex-1 min-w-[200px]">
                                <div class="flex items-center">
                                    <div class="h-16 w-16 rounded-lg overflow-hidden bg-zinc-800 mr-4">
                                        <img src="Assets/icecream.png" alt="Dessert Combo" class="h-full w-full object-cover" />
                                    </div>
                                    <div>
                                        <h4 class="font-medium">Dessert Special Combo</h4>
                                        <p class="text-gray-400 text-sm">4 items</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <button class="bg-zinc-800 hover:bg-zinc-700 text-white px-4 py-2 rounded-lg text-sm transition-colors flex items-center">
                                    <i class="fa-solid fa-receipt mr-2"></i> View Details
                                </button>
                                <button class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg text-sm transition-colors flex items-center">
                                    <i class="fa-solid fa-redo mr-2"></i> Reorder
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-10">
            <nav class="flex items-center space-x-2">
                <a href="#" class="px-3 py-2 rounded-md bg-zinc-800 text-gray-400 hover:bg-zinc-700 transition-colors">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                <a href="#" class="px-4 py-2 rounded-md bg-yellow-500 text-black font-medium">1</a>
                <a href="#" class="px-4 py-2 rounded-md bg-zinc-800 text-white hover:bg-zinc-700 transition-colors">2</a>
                <a href="#" class="px-4 py-2 rounded-md bg-zinc-800 text-white hover:bg-zinc-700 transition-colors">3</a>
                <span class="px-3 py-2 text-gray-400">...</span>
                <a href="#" class="px-4 py-2 rounded-md bg-zinc-800 text-white hover:bg-zinc-700 transition-colors">8</a>
                <a href="#" class="px-3 py-2 rounded-md bg-zinc-800 text-gray-400 hover:bg-zinc-700 transition-colors">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </nav>
        </div>
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
        // Mobile menu toggle functionality
        document.getElementById('mobile-menu-toggle').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('open');

            // Change icon between bars and times (x)
            const icon = this.querySelector('i');
            if (icon.classList.contains('fa-bars')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    </script>
</body>

</html>