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
        box-shadow: 0 10px 25px -5px rgba(249, 180, 42, 0.1);
    }

    .status-delivered {
        background-color: rgba(34, 197, 94, 0.1);
        color: rgb(34, 197, 94);
    }

    .status-processing {
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

    .progress-step.active .step-number {
        background-color: rgb(249, 180, 42);
        color: black;
    }

    .progress-step.completed .step-number {
        background-color: rgb(34, 197, 94);
        color: white;
    }

    .progress-step.active .step-label {
        color: rgb(249, 180, 42);
    }

    .progress-step.completed .step-label {
        color: rgb(34, 197, 94);
    }

    .progress-line {
        height: 2px;
        background-color: #3f3f46;
    }

    .progress-line.active {
        background-color: rgb(249, 180, 42);
    }

    .progress-line.completed {
        background-color: rgb(34, 197, 94);
    }
</style>

<body class="min-h-screen bg-black text-white">
    <!-- Header -->
    <?php require("navbar.php");
    if (isset(($_GET['order_id']))) {
        $orderId = $_GET['order_id'];
    }
    $usersAllOrders = "";
    if (isset(($_SESSION['user']['user_id']))) {
        $uid = $_SESSION['user']['user_id'];
        $cartItems = $obj->getCartItems($uid);
        $currentUser = $obj->getUserById($uid);
        $userdata = $obj->getUserById($uid);
        // $getitems=$obj->getOrderItemsByOrderId();
        $usersAllOrders = $obj->getOrdersByUserId($uid);
        $orderById = $obj->getOrderById($orderId);
    }

    ?>

    <!-- Account Navigation -->
    <div class="pt-20">
        <div class="bg-zinc-900 py-4 border-b border-zinc-800">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex overflow-x-auto no-scrollbar space-x-6 py-2">
                    <a href="account_user.php"
                        class="text-gray-400 hover:text-yellow-500 whitespace-nowrap transition-colors">
                        <i class="fa-solid fa-user-circle mr-2"></i>Profile
                    </a>
                    <a href="#" class="text-yellow-500 whitespace-nowrap border-b-2 border-yellow-500 pb-2">
                        <i class="fa-solid fa-clock-rotate-left mr-2"></i>Past Orders
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
    print_r($orderById);
    foreach ($usersAllOrders as $order) {
        if ($order['order_id'] == $orderId) {
            $image_urls = explode(',', $order['image_urls']);
            $quan_urls = explode(',', $order['quantities']);
            $items = explode(',', $order['items']);
            ?>

            <!-- Main Content -->
            <main class="max-w-7xl mx-auto px-4 py-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                    <div>
                        <div class="flex items-center">
                            <a href="past-orders.php" class="text-gray-400 hover:text-yellow-500 mr-2">
                                <i class="fa-solid fa-arrow-left"></i>
                            </a>
                            <h1 class="text-2xl md:text-3xl font-bold">Order <?php echo $order['order_id']; ?>
                            </h1>
                            <span class="ml-3 px-3 py-1 text-xs font-medium rounded-full status-delivered">
                               <?php echo $order['payment_status']; ?>
                            </span>
                        </div>
                        <p class="text-gray-400 mt-1">Placed on
                            <?php
                            $date = new DateTime($orderById['order_date']);
                            echo $date->format('F j, Y \a\t g:i A');
                            ?>
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                        <button
                            onclick="openPaymentModal('<?= htmlspecialchars($orders['payment_id'], ENT_QUOTES, 'UTF-8') ?>')"
                            class="bg-zinc-800 hover:bg-zinc-700 text-white px-4 py-2 rounded-lg text-sm transition-colors flex items-center">
                            <i class="fa-solid fa-download mr-2"></i> Download Invoice
                        </button>
                        <button
                            class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg text-sm transition-colors flex items-center">
                            <i class="fa-solid fa-redo mr-2"></i> Reorder
                        </button>
                    </div>
                </div>

                <!-- Order Status -->
                <div class="order-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 p-6 mb-8">
                    <h2 class="text-lg font-semibold mb-6">Order Status</h2>

                    <!-- Progress Tracker -->
                    <div class="flex items-center justify-between mb-8">
                        <!-- Step 1: Order Placed -->
                        <div class="flex flex-col items-center progress-step completed">
                            <div class="step-number w-10 h-10 rounded-full flex items-center justify-center mb-2">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <span class="step-label text-sm font-medium">Order Placed</span>
                            <span class="text-xs text-gray-400 mt-1">
                                <?php
                                $date = new DateTime($orderById['order_date']);
                                echo $date->format('F j, Y \a\t g:i A');
                                ?>
                            </span>
                        </div>

                        <!-- Line 1 -->
                        <div class="progress-line completed flex-1 mx-2"></div>

                        <!-- Step 2: Preparing -->
                        <div class="flex flex-col items-center progress-step completed">
                            <div class="step-number w-10 h-10 rounded-full flex items-center justify-center mb-2">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <span class="step-label text-sm font-medium">Preparing</span>
                            <!-- <span class="text-xs text-gray-400 mt-1">7:45 PM</span> -->
                        </div>

                        <!-- Line 2 -->
                        <div class="progress-line completed flex-1 mx-2"></div>

                        <!-- Step 3: On the Way -->
                        <div class="flex flex-col items-center progress-step completed">
                            <div class="step-number w-10 h-10 rounded-full flex items-center justify-center mb-2">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <span class="step-label text-sm font-medium">On the Way</span>
                            <!-- <span class="text-xs text-gray-400 mt-1">8:10 PM</span> -->
                        </div>

                        <!-- Line 3 -->
                        <div class="progress-line completed flex-1 mx-2"></div>

                        <!-- Step 4: Delivered -->
                        <div class="flex flex-col items-center progress-step completed">
                            <div class="step-number w-10 h-10 rounded-full flex items-center justify-center mb-2">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <span class="step-label text-sm font-medium">Delivered</span>
                            <!-- <span class="text-xs text-gray-400 mt-1">8:35 PM</span> -->
                        </div>
                    </div>

                    <div class="bg-zinc-800 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fa-solid fa-info-circle text-yellow-500 mt-1 mr-3"></i>
                            <div>
                                <p class="text-white">Your order was delivered by Our Rider</p>
                                <p class="text-gray-400 text-sm">Thank you for ordering with Village Chef! We hope you enjoyed
                                    your meal.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Left Column - Order Items -->
                    <div class="md:col-span-2">
                        <div class="order-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 p-6">
                            <h2 class="text-lg font-semibold mb-4">Order Items</h2>

                            <div class="space-y-6">
                                <!-- Item 1 -->
                                    <div class="flex flex-col sm:flex-row border-b border-zinc-800 pb-4">
                                        <div class="flex-shrink-0 mb-4 sm:mb-0 sm:mr-4">
                                            <div class="h-20 w-20 rounded-lg overflow-hidden bg-zinc-800">
                                                <img src="Assets/pizza2.png" alt="Domino's Special Pizza"
                                                    class="h-full w-full object-cover" />
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex flex-col sm:flex-row sm:justify-between">
                                                <div>
                                                    <h3 class="font-medium">Domino's Special Pizza</h3>
                                                    <p class="text-gray-400 text-sm">Large, Extra Cheese, Spicy</p>
                                                </div>
                                                <div class="mt-2 sm:mt-0 text-right">
                                                    <p class="text-yellow-500 font-semibold">₹599</p>
                                                    <p class="text-gray-400 text-sm">Qty: 1</p>
                                                </div>
                                            </div>
                                            <div class="mt-3 flex justify-between items-center">
                                                <div class="flex items-center">
                                                    <span
                                                        class="bg-zinc-800 text-gray-300 text-xs px-2 py-1 rounded-md flex items-center mr-2">
                                                        <i class="fa-solid fa-fire text-yellow-500 mr-1"></i> Spicy
                                                    </span>
                                                    <span
                                                        class="bg-zinc-800 text-gray-300 text-xs px-2 py-1 rounded-md flex items-center">
                                                        <i class="fa-solid fa-leaf text-green-500 mr-1"></i> Veg
                                                    </span>
                                                </div>
                                                <button class="text-yellow-500 hover:text-yellow-400 text-sm">
                                                    <i class="fa-solid fa-redo mr-1"></i> Reorder
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                <!-- Item 2 -->
                                <div class="flex flex-col sm:flex-row border-b border-zinc-800 pb-4">
                                    <div class="flex-shrink-0 mb-4 sm:mb-0 sm:mr-4">
                                        <div class="h-20 w-20 rounded-lg overflow-hidden bg-zinc-800">
                                            <img src="Assets/pasta.png" alt="Garlic Bread" class="h-full w-full object-cover" />
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                            <div>
                                                <h3 class="font-medium">Garlic Bread</h3>
                                                <p class="text-gray-400 text-sm">With Cheese Dip</p>
                                            </div>
                                            <div class="mt-2 sm:mt-0 text-right">
                                                <p class="text-yellow-500 font-semibold">₹199</p>
                                                <p class="text-gray-400 text-sm">Qty: 1</p>
                                            </div>
                                        </div>
                                        <div class="mt-3 flex justify-between items-center">
                                            <div class="flex items-center">
                                                <span
                                                    class="bg-zinc-800 text-gray-300 text-xs px-2 py-1 rounded-md flex items-center">
                                                    <i class="fa-solid fa-leaf text-green-500 mr-1"></i> Veg
                                                </span>
                                            </div>
                                            <button class="text-yellow-500 hover:text-yellow-400 text-sm">
                                                <i class="fa-solid fa-redo mr-1"></i> Reorder
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Item 3 -->
                                <div class="flex flex-col sm:flex-row">
                                    <div class="flex-shrink-0 mb-4 sm:mb-0 sm:mr-4">
                                        <div class="h-20 w-20 rounded-lg overflow-hidden bg-zinc-800">
                                            <img src="Assets/icecream.png" alt="Chocolate Brownie"
                                                class="h-full w-full object-cover" />
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                            <div>
                                                <h3 class="font-medium">Chocolate Brownie</h3>
                                                <p class="text-gray-400 text-sm">With Ice Cream</p>
                                            </div>
                                            <div class="mt-2 sm:mt-0 text-right">
                                                <p class="text-yellow-500 font-semibold">₹249</p>
                                                <p class="text-gray-400 text-sm">Qty: 1</p>
                                            </div>
                                        </div>
                                        <div class="mt-3 flex justify-between items-center">
                                            <div class="flex items-center">
                                                <span
                                                    class="bg-zinc-800 text-gray-300 text-xs px-2 py-1 rounded-md flex items-center">
                                                    <i class="fa-solid fa-cookie text-yellow-500 mr-1"></i> Dessert
                                                </span>
                                            </div>
                                            <button class="text-yellow-500 hover:text-yellow-400 text-sm">
                                                <i class="fa-solid fa-redo mr-1"></i> Reorder
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Details -->
                        <div class="order-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 p-6 mt-6">
                            <h2 class="text-lg font-semibold mb-4">Delivery Details</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="text-sm text-gray-400 mb-1">Delivery Address</h3>
                                    <div class="bg-zinc-800 p-4 rounded-lg">
                                        <p class="text-white">
                                            <?php echo $orderById['customer_first_name'] . " " . $orderById['customer_last_name']; ?>
                                        </p>
                                        <p class="text-gray-400"><?php echo $orderById['delivery_address'] ?></p>
                                        <p class="text-gray-400">Foodville, NY 10001</p>
                                        <p class="text-gray-400">+1 (555) 123-4567</p>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-sm text-gray-400 mb-1">Delivery Instructions</h3>
                                    <div class="bg-zinc-800 p-4 rounded-lg h-full">
                                        <p class="text-white">Please leave at the door. Ring the doorbell when delivered.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="mt-6">
                        <h3 class="text-sm text-gray-400 mb-1">Delivery Person</h3>
                        <div class="bg-zinc-800 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                                    <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Delivery Person"
                                        class="w-full h-full object-cover" />
                                </div>
                                <div>
                                    <p class="text-white font-medium"><?php echo $orderById['customer_first_name'] . " " . $orderById['customer_last_name']; ?></p>
                                    <div class="flex items-center text-yellow-500">
                                        <i class="fa-solid fa-star text-xs"></i>
                                        <i class="fa-solid fa-star text-xs"></i>
                                        <i class="fa-solid fa-star text-xs"></i>
                                        <i class="fa-solid fa-star text-xs"></i>
                                        <i class="fa-solid fa-star-half-alt text-xs"></i>
                                        <span class="text-gray-400 text-xs ml-1">(4.5)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                        </div>
                    </div>

                    <!-- Right Column - Payment Summary -->
                    <div class="md:col-span-1">
                        <div class="order-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 p-6 sticky top-6">
                            <h2 class="text-lg font-semibold mb-4">Payment Summary</h2>

                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Subtotal</span>
                                    <span class="text-white">₹1,047.00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Delivery Fee</span>
                                    <span class="text-white">₹49.00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">GST (5%)</span>
                                    <span class="text-white">₹52.35</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Packaging Charge</span>
                                    <span class="text-white">₹20.00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Discount</span>
                                    <span class="text-green-500">-₹100.00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Promo (WELCOME20)</span>
                                    <span class="text-green-500">-₹20.00</span>
                                </div>
                            </div>

                            <div class="border-t border-zinc-800 pt-4 mb-6">
                                <div class="flex justify-between font-bold">
                                    <span>Total</span>
                                    <span class="text-yellow-500">₹1,249.00</span>
                                </div>
                            </div>

                            <div class="bg-zinc-800 p-4 rounded-lg mb-6">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-gray-400">Payment Method</span>
                                    <span class="text-white">Credit Card</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fa-brands fa-cc-visa text-blue-500 text-2xl mr-2"></i>
                                    <span class="text-gray-400">•••• •••• •••• 4567</span>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <button
                                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-black py-3 rounded-lg transition-colors flex items-center justify-center">
                                    <i class="fa-solid fa-redo mr-2"></i> Reorder
                                </button>
                                <button class="w-full bg-zinc-  Reorder
                        </button>
                        <button class=" w-full bg-zinc-800 hover:bg-zinc-700 text-white py-3 rounded-lg
                                    transition-colors flex items-center justify-center">
                                    <i class="fa-solid fa-headset mr-2"></i> Need Help?
                                </button>
                            </div>
                        </div>

                        <!-- Rate Your Order -->
                        <div class="order-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 p-6 mt-6">
                            <h2 class="text-lg font-semibold mb-4">Rate Your Order</h2>

                            <div class="flex justify-center mb-4">
                                <div class="flex space-x-2">
                                    <button class="text-yellow-500 text-2xl">
                                        <i class="fa-solid fa-star"></i>
                                    </button>
                                    <button class="text-yellow-500 text-2xl">
                                        <i class="fa-solid fa-star"></i>
                                    </button>
                                    <button class="text-yellow-500 text-2xl">
                                        <i class="fa-solid fa-star"></i>
                                    </button>
                                    <button class="text-yellow-500 text-2xl">
                                        <i class="fa-solid fa-star"></i>
                                    </button>
                                    <button class="text-zinc-600 text-2xl">
                                        <i class="fa-solid fa-star"></i>
                                    </button>
                                </div>
                            </div>

                            <textarea placeholder="Share your feedback about the food and service..."
                                class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg focus:ring-yellow-500 focus:border-yellow-500 p-3 h-24 resize-none"></textarea>

                            <button
                                class="w-full bg-yellow-500 hover:bg-yellow-600 text-black py-2 rounded-lg transition-colors mt-3">
                                Submit Review
                            </button>
                        </div>
                    </div>
                </div>
            </main>


            <?php
        }
    }
    ?>

    <!-- Footer -->
    <?php require("footer.php") ?>
    <script>
        // Mobile menu toggle functionality
        document.getElementById('mobile-menu-toggle').addEventListener('click', function () {
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