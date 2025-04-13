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

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#000000',
                    accent: '#eab308',
                }
            }
        }
    }

    function closeModal() {
        document.body.style.overflow = "";
        document.getElementById('paymentDetailModal').classList.add('hidden');
        const url = new URL(window.location.href);
        url.searchParams.delete('payment_id');
        window.history.replaceState({}, '', url); // Use replaceState instead of pushState
    }

    function openPaymentModal(paymentId, orderid) {
        document.body.style.overflow = "hidden";
        // window.location.href = "&payment_id=" + paymentId;
        window.location.href = "view_order_details.php?order_id=" + orderid + "&payment_id=" + paymentId;
    }
</script>

<!-- Load Lucide -->
<script src="https://unpkg.com/lucide@latest"></script>

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
        $orderByIdMain = $obj->getOrderDetailsById($orderId);
    }


    if (isset($_GET['rating']) && isset($_GET['feedback'])) {
        $rating = $_GET['rating'];
        $feedback = $_GET['feedback'];

        $obj->addReview($orderByIdMain['user_id'], $orderByIdMain['restaurant_id'], $orderByIdMain['order_id'], $rating, $feedback);
        $unicodedOrderId = urlencode($orderId);
        echo "<script> window.location.href='view_order_details.php?order_id=$unicodedOrderId'; </script>";
    }

    ?>

    <style>
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

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #2d3748;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #eab308;
            /* yellow-500 */
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #d97706;
            /* Slightly darker yellow */
        }
    </style>

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
    // print_r($orderByIdMain);
    $items = explode(",", $orderByIdMain['items']);
    $quantities = explode(",", $orderByIdMain['quantities']);
    $prices = explode(",", $orderByIdMain['prices']);
    $imageUrls = explode(",", $orderByIdMain['image_urls']);
    if ($orderByIdMain) {
        // print_r($orderByIdMain);
    ?>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <div>
                    <div class="flex items-center">
                        <a href="orders_user.php" class="text-gray-400 hover:text-yellow-500 text-2xl mx-2">
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                        <h1 class="text-2xl md:text-3xl font-bold">Order
                            <?php echo htmlspecialchars($orderByIdMain['order_id']); ?>
                        </h1>
                        <!-- <span
                            class="ml-3 px-3 py-1 text-xs font-medium rounded-full status-<?php echo htmlspecialchars($orderById['order_status']); ?>">
                            <?php echo htmlspecialchars(ucfirst($orderByIdMain['order_status'])); ?>
                        </span> -->
                    </div>
                    <p class="text-gray-400 mt-1">Placed on
                        <?php echo date('F j, Y \a\t g:i A', strtotime($orderByIdMain['order_date'])); ?>
                    </p>
                </div>
                <div class="mt-4 md:mt-0 flex flex-wrap gap-3 <?php echo $orderByIdMain['order_status'] == "delivered" ? '' : 'hidden' ?>">
                    <button
                        onclick="openPaymentModal('<?= htmlspecialchars($orderByIdMain['payment_id'], ENT_QUOTES, 'UTF-8') ?>', '<?= htmlspecialchars(urlencode($orderByIdMain['order_id']), ENT_QUOTES, 'UTF-8') ?>')"
                        class="bg-zinc-800 hover:bg-zinc-700 text-white px-4 py-2 rounded-lg text-sm transition-colors flex items-center">
                        <i class="fa-solid fa-download mr-2"></i> Download Invoice
                    </button>
                    <!-- <button
                        class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg text-sm transition-colors flex items-center">
                        <i class="fa-solid fa-redo mr-2"></i> Reorder
                    </button> -->
                </div>
            </div>


            <!-- Order Status - preparing -->
            <div class="order-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 p-6 mb-8">
                <h2 class="text-lg font-semibold mb-6">Order Status</h2>

                <!-- Progress Tracker -->
                <div class=" <?php echo ($orderByIdMain['order_status'] === "cancelled" ? 'hidden' : 'flex') ?> items-center justify-between mb-8">
                    <!-- Step 1: Order Placed (Completed) -->
                    <div class="flex flex-col items-center progress-step completed">
                        <div
                            class="step-number w-10 h-10 rounded-full flex items-center justify-center mb-2 bg-green-500 text-white">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <span class="step-label text-sm font-medium text-green-500">Order Placed</span>
                        <span class="text-xs text-gray-400 mt-1"><?php
                                                                    $date = new DateTime($orderByIdMain['order_date']);
                                                                    echo $date->format('F j, Y \a\t g:i A');
                                                                    ?></span>
                    </div>

                    <!-- Line 1 (Completed) -->
                    <div
                        class="progress-line  <?php echo ($orderByIdMain['order_status'] === "preparing" ? 'active' : 'completed') ?> flex-1 mx-2">
                    </div>

                    <!-- Step 2: preparing (Active) -->
                    <div
                        class="flex flex-col items-center progress-step  <?php echo ($orderByIdMain['order_status'] === "preparing" ? 'active' : 'completed') ?>">
                        <div
                            class="step-number w-10 h-10 rounded-full flex items-center justify-center mb-2 bg-yellow-500 <?php echo ($orderByIdMain['order_status'] === "preparing" ? 'active' : 'completed') ?>  text-black">
                            <i class="fa-solid fa-utensils"></i>
                        </div>
                        <span class="step-label text-sm font-medium text-yellow-500">preparing</span>
                        <span
                            class="text-xs <?php echo ($orderByIdMain['order_status'] === "preparing" ? 'text-yellow-500' : 'text-green-400') ?> mt-1">In
                            progress</span>
                    </div>

                    <!-- Line 2 (preparing) -->
                    <div
                        class="progress-line <?php echo ($orderByIdMain['order_status'] === "delivered" ||  $orderByIdMain['order_status'] === "out_for_delivery" ? 'completed' : 'preparing') ?> flex-1 mx-2">
                    </div>

                    <!-- Step 3: On the Way (preparing) -->
                    <div
                        class="flex flex-col items-center progress-step <?php echo ($orderByIdMain['order_status'] === "delivered" ? 'completed' : '') ?>">
                        <div class="step-number w-10 h-10 rounded-full flex items-center justify-center mb-2 bg-zinc-800 ">
                            <i class="fa-solid fa-motorcycle"></i>
                        </div>
                        <span
                            class="step-label text-sm font-medium <?php echo ($orderByIdMain['order_status'] === "delivered" ? 'text-green-500' : 'text-gray-400') ?>">On
                            the Way</span>
                        <span
                            class="text-xs <?php echo ($orderByIdMain['order_status'] === "delivered" ? 'text-green-500' : 'text-gray-400') ?> mt-1">Out
                            for delivery</span>
                    </div>

                    <!-- Line 3 (preparing) -->
                    <div
                        class="progress-line <?php echo ($orderByIdMain['order_status'] === "delivered" ? 'completed' : 'preparing') ?> flex-1 mx-2">
                    </div>

                    <!-- Step 4: Delivered (preparing) -->
                    <div
                        class="flex flex-col items-center progress-step <?php echo ($orderByIdMain['order_status'] === "delivered" ? 'completed' : '') ?>">
                        <div
                            class="step-number w-10 h-10 rounded-full flex items-center justify-center mb-2 bg-zinc-800 text-gray-400">
                            <i class="fa-solid fa-box"></i>
                        </div>
                        <span
                            class="step-label text-sm font-medium <?php echo ($orderByIdMain['order_status'] === "delivered" ? 'text-green-500' : 'text-gray-400') ?> ">Delivered</span>
                        <span
                            class="text-xs <?php echo ($orderByIdMain['order_status'] === "delivered" ? 'text-green-500' : 'text-gray-400') ?> mt-1">Your
                            order Delivered</span>
                    </div>
                </div>

                <div class="bg-zinc-800 rounded-lg p-4 <?php echo ($orderByIdMain['order_status'] === "preparing" ? '' : 'hidden')  ?>">
                    <div class="flex items-start">
                        <i class="fa-solid fa-info-circle text-yellow-500 mt-1 mr-3"></i>
                        <div>
                            <p class="text-white">Your order is being prepared</p>
                            <p class="text-gray-400 text-sm">Our chefs are working on your delicious meal. Estimated
                                delivery time: 8:30 PM.</p>
                        </div>
                    </div>
                </div>
                <div class="bg-zinc-800 rounded-lg p-4 <?php echo ($orderByIdMain['order_status'] === "cancelled" ? '' : 'hidden')  ?>">
                    <div class="flex items-start">
                        <i class="fa-solid fa-ban text-red-500 mt-1 mr-3"></i>
                        <div>
                            <p class="text-white">Your order was Cancelled</p>
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
                            <?php
                            // Split items, quantities, and prices into arrays
                            $items = explode(',', $orderByIdMain['items']);
                            $quantities = explode(',', $orderByIdMain['quantities']);
                            $prices = explode(',', $orderByIdMain['prices']);
                            $image_urls = explode(',', $orderByIdMain['image_urls']);
                            $minCount = min(count($items), count($quantities), count($prices), count($image_urls));
                            // Loop through items and display them
                            for ($i = 0; $i < $minCount; $i++) {
                            ?>
                                <div class="flex flex-col sm:flex-row border-b border-zinc-800 pb-4">
                                    <div class="flex-shrink-0 mb-4 sm:mb-0 sm:mr-4">
                                        <div class="h-20 w-20 rounded-lg overflow-hidden bg-zinc-800">
                                            <img src="../AdminPanel/<?php echo htmlspecialchars(trim($image_urls[$i])); ?>"
                                                alt="<?php echo htmlspecialchars(trim($items[$i])); ?>"
                                                class="h-full w-full object-cover" />
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                            <div>
                                                <h3 class="font-medium"><?php echo htmlspecialchars(trim($items[$i])); ?></h3>
                                            </div>
                                            <div class="mt-2 sm:mt-0 text-right">
                                                <p class="text-yellow-500 font-semibold">
                                                    ₹<?php echo htmlspecialchars(trim($prices[$i])); ?></p>
                                                <p class="text-gray-400 text-sm">Qty:
                                                    <?php echo htmlspecialchars(trim($quantities[$i])); ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-3 flex justify-between items-center">
                                            <div class="flex items-center">
                                                <!-- Add any tags or labels here -->
                                            </div>
                                            <!-- <button class="text-yellow-500 hover:text-yellow-400 text-sm">
                                                <i class="fa-solid fa-redo mr-1"></i> Reorder
                                            </button> -->
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
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
                                        <?php echo htmlspecialchars($orderById['customer_first_name'] . ' ' . $orderById['customer_last_name']); ?>
                                    </p>
                                    <p class="text-gray-400"><?php echo htmlspecialchars($orderById['delivery_address']); ?>
                                    </p>
                                    <p class="text-gray-400">Contact: +1 (555) 123-4567</p>
                                    <!-- Add actual contact if available -->
                                </div>
                            </div>

                            <div>
                                <h3 class="text-sm text-gray-400 mb-1">Delivery Instructions</h3>
                                <div class="bg-zinc-800 p-4 rounded-lg h-full">
                                    <p class="text-white">Please leave at the door. Ring the doorbell when delivered.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h3 class="text-sm text-gray-400 mb-1">Delivery Person</h3>
                            <div class="bg-zinc-800 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                                        <img src="https://randomuser.me/api/portraits/men/25.jpg" alt="Delivery Person"
                                            class="w-full h-full object-cover" />
                                    </div>
                                    <div>
                                        <p class="text-white font-medium">John D.</p>
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
                        </div>
                    </div>
                </div>

                <!-- Right Column - Payment Summary -->
                <div class="md:col-span-1">
                    <div class="order-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 p-6 top-6">
                        <h2 class="text-lg font-semibold mb-4">Payment Summary</h2>

                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Subtotal</span>
                                <span class="text-white">₹<?php echo htmlspecialchars($orderById['total_amount']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Payment Method</span>
                                <span
                                    class="text-white"><?php echo htmlspecialchars(ucfirst($orderById['payment_method'])); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Payment Status</span>
                                <span
                                    class="text-white"><?php echo htmlspecialchars(ucfirst($orderById['payment_status'])); ?></span>
                            </div>
                        </div>

                        <div class="border-t border-zinc-800 pt-4 mb-6">
                            <div class="flex justify-between font-bold">
                                <span>Total</span>
                                <span
                                    class="text-yellow-500">₹<?php echo htmlspecialchars($orderById['total_amount']); ?></span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <!-- <button
                                class="w-full bg-yellow-500 hover:bg-yellow-600 text-black py-3 rounded-lg transition-colors flex items-center justify-center">
                                <i class="fa-solid fa-redo mr-2"></i> Reorder
                            </button> -->
                            <button
                                class="w-full bg-zinc-800 hover:bg-zinc-700 text-white py-3 rounded-lg transition-colors flex items-center justify-center">
                                <i class="fa-solid fa-headset mr-2"></i> Need Help?
                            </button>
                        </div>
                    </div>



                    <!-- Rate Your Order -->


                    <?php
                    if ($orderByIdMain['review_id']) {
                    ?>
                        <div class="order-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 p-6 mt-6">
                            <h2 class="text-lg font-semibold mb-4"><?php echo $orderByIdMain['review_id']?'Thank You for Your Rating':'Rate Your Order' ?></h2>
                            <div class="flex gap-2 ">
                                <i data-lucide="star" class="w-6 h-6 <?php echo $orderByIdMain['rating'] >= 1 ? 'text-yellow-500 fill-yellow-500' : ''; ?>"></i>
                                <i data-lucide="star" class="w-6 h-6 <?php echo $orderByIdMain['rating'] >= 2 ? 'text-yellow-500 fill-yellow-500' : ''; ?>"></i>
                                <i data-lucide="star" class="w-6 h-6 <?php echo $orderByIdMain['rating'] >= 3 ? 'text-yellow-500 fill-yellow-500' : ''; ?>"></i>
                                <i data-lucide="star" class="w-6 h-6 <?php echo $orderByIdMain['rating'] >= 4 ? 'text-yellow-500 fill-yellow-500' : ''; ?>"></i>
                                <i data-lucide="star" class="w-6 h-6 <?php echo $orderByIdMain['rating'] >= 5 ? 'text-yellow-500 fill-yellow-500' : ''; ?>"></i>
                            </div>
                            <div
                                name="feedback"
                                placeholder="Share your feedback about the food and service..."
                                class="w-full mt-4 bg-zinc-800 border border-zinc-700 text-white rounded-lg focus:ring-yellow-500 focus:border-yellow-500 p-3 h-24 resize-none">
                                <?php echo $orderByIdMain['review_text']; ?>
                            </div>

                        </div>

                        <?php
                    }


                    if ($orderByIdMain['order_status'] == 'delivered') {
                        if ($orderByIdMain['review_id'] == null) {
                        ?>
                            <div class="order-card bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 p-6 mt-6">
                                <h2 class="text-lg font-semibold mb-4">Rate Your Order</h2>
                                <form method="get">
                                    <div class="max-w-md mx-auto p-4  shadow-md rounded-2xl">
                                        <form action="<?php echo $_SERVER['PHP_SELF'] . '?order_id=' . $order_id . '&rating='; ?>" method="POST" onsubmit="appendRatingToAction(this)">

                                            <div id="star-rating" class="flex justify-center space-x-2 mb-4">
                                                <!-- Stars -->
                                                <input type="hidden" name="order_id" value="<?php echo $orderId  ?>" />
                                                <button type="button" data-value="1" class="star text-3xl text-gray-300 hover:text-yellow-400 transition">
                                                    <i data-lucide="star" class="w-6 h-6"></i>
                                                </button>
                                                <button type="button" data-value="2" class="star text-3xl text-gray-300 hover:text-yellow-400 transition">
                                                    <i data-lucide="star" class="w-6 h-6"></i>
                                                </button>
                                                <button type="button" data-value="3" class="star text-3xl text-gray-300 hover:text-yellow-400 transition">
                                                    <i data-lucide="star" class="w-6 h-6"></i>
                                                </button>
                                                <button type="button" data-value="4" class="star text-3xl text-gray-300 hover:text-yellow-400 transition">
                                                    <i data-lucide="star" class="w-6 h-6"></i>
                                                </button>
                                                <button type="button" data-value="5" class="star text-3xl text-gray-300 hover:text-yellow-400 transition">
                                                    <i data-lucide="star" class="w-6 h-6"></i>
                                                </button>

                                            </div>

                                            <!-- Hidden input to submit selected rating -->
                                            <input type="hidden" name="rating" id="rating-value">

                                            <textarea
                                                required
                                                name="feedback"
                                                placeholder="Share your feedback about the food and service..."
                                                class="w-full bg-zinc-800 border border-zinc-700 text-white rounded-lg focus:ring-yellow-500 focus:border-yellow-500 p-3 h-24 resize-none"></textarea>

                                            <input
                                                type="submit"
                                                value="Submit Review"
                                                class="w-full bg-yellow-500 hover:bg-yellow-600 text-black py-2 rounded-lg transition-colors mt-3" />
                                        </form>
                                    </div>


                                </form>
                            </div>
                    <?php
                        }
                    }
                    ?>

                </div>
            </div>
        </main>

    <?php
    }
    ?>

    <?php
    $paymentDetails = null;
    if (isset($_GET['payment_id'])) {
        try {
            $paymentDetails = $obj->getPaymentDetails($_GET['payment_id']);
            if ($paymentDetails) {
                $orderItems = $obj->getOrderItems($paymentDetails['order_id']);

                // print_r($orderItems);

                $grand_total = $paymentDetails['amount'];

                $platform_fee = 6;
                $delivery_charge = 22;
                $fixed_charges = $platform_fee + $delivery_charge;

                $actual_total = ($grand_total - $fixed_charges) / 1.09;
                $tax = $actual_total * 0.09;
            }
        } catch (Exception $e) {
            echo "<script>console.error('Error fetching payment details: " . $e->getMessage() . "');</script>";
            $paymentDetails = null;
        }
    }
    ?>

    <div id="paymentDetailModal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center <?= isset($_GET['payment_id']) && $paymentDetails ? '' : 'hidden' ?> z-50">
        <?php if ($paymentDetails): ?>
            <div
                class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto custom-scrollbar">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-accent">Payment Details</h1>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-accent transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="space-y-5">
                    <!-- Payment Overview -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-700/30 p-4 rounded-xl">
                        <div>
                            <p class="text-sm text-gray-400">Payment ID</p>
                            <p class="text-white font-medium">#PAY-<?= htmlspecialchars($paymentDetails['payment_id']) ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Order ID</p>
                            <p class="text-white font-medium"><?= htmlspecialchars($paymentDetails['order_id']) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Date & Time</p>
                            <p class="text-white font-medium">
                                <?= date('M d, Y h:i A', strtotime($paymentDetails['payment_date'])) ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Payment Method</p>
                            <p class="text-white font-medium">
                                <?= ucfirst(str_replace('-', ' ', $paymentDetails['payment_method'])) ?>
                            </p>
                        </div>
                    </div>

                    <!-- Customer Details -->
                    <div class="bg-gray-700/30 p-4 rounded-xl">
                        <h3 class="text-lg font-semibold text-accent mb-3">Customer Information</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-400">Name</p>
                                <p class="text-white">
                                    <?= htmlspecialchars($paymentDetails['first_name'] . ' ' . $paymentDetails['last_name']) ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm overflow-hidden text-gray-400">Email</p>
                                <p class="text-white"><?= htmlspecialchars($paymentDetails['email']) ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Phone</p>
                                <p class="text-white"><?= htmlspecialchars($paymentDetails['phone'] ?? '-') ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Address</p>
                                <p class="text-white"><?= htmlspecialchars($paymentDetails['delivery_address'] ?? '-') ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Breakdown -->
                    <div class="bg-gray-700/30 p-4 rounded-xl">
                        <h3 class="text-lg font-semibold text-accent mb-3">Payment Breakdown</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between border-t border-gray-600 pt-2">
                                <span class="text-gray-400">Actual Total</span>
                                <span class="text-white">₹<?= number_format($actual_total, 2); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Platform Fee</span>
                                <span class="text-white">₹<?= number_format($platform_fee, 2); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Delivery Charge</span>
                                <span class="text-white">₹<?= number_format($delivery_charge, 2); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Tax (0.09%)</span>
                                <span class="text-white">₹<?= number_format($tax, 2); ?></span>
                            </div>
                            <div class="flex justify-between border-t border-gray-600 pt-2">
                                <span class="text-gray-400 font-medium">Grand Total</span>
                                <span class="text-accent font-medium">₹<?= number_format($grand_total, 2); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Status & Actions -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-gray-400">Payment Status:</span>
                            <span
                                class="px-2.5 py-1 rounded-full text-xs <?=
                                                                        $paymentDetails['payment_status'] === 'successful' ? 'bg-green-900/30 text-green-400' : ($paymentDetails['payment_status'] === 'failed' ? 'bg-red-900/30 text-red-400' :
                                                                            'bg-yellow-900/30 text-yellow-400') ?>">
                                <?= ucfirst($paymentDetails['payment_status']) ?>
                            </span>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button onclick="closeModal()"
                                class="px-6 py-2 border border-gray-600 text-gray-300 rounded-xl hover:bg-gray-700/30 transition-colors">
                                Close
                            </button>
                            <a href="print_receipt.php?payment_id=<?= htmlspecialchars($paymentDetails['payment_id'], ENT_QUOTES, 'UTF-8') ?>"
                                class="px-6 py-2 bg-yellow-500 text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">
                                Print Receipt
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl w-full max-w-2xl mx-4">
                <h1 class="text-xl font-bold text-red-500">Payment details not found.</h1>
                <button onclick="closeModal()"
                    class="mt-4 px-6 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">
                    Close
                </button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <?php require("footer.php") ?>
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

    <script>
        lucide.createIcons();

        const stars = document.querySelectorAll('.star');
        const ratingValue = document.getElementById('rating-value');

        stars.forEach((star, index) => {
            star.addEventListener('click', () => {
                const value = parseInt(star.getAttribute('data-value'));
                ratingValue.value = value;

                stars.forEach((s, i) => {
                    const icon = s.querySelector('svg');
                    if (i < value) {
                        s.classList.add('text-yellow-500');
                        s.classList.remove('text-gray-300');
                        icon.classList.add('fill-yellow-500');
                        icon.setAttribute("fill", "currentColor");
                    } else {
                        s.classList.remove('text-yellow-500');
                        s.classList.add('text-gray-300');
                        icon.classList.remove('fill-yellow-500');
                        icon.setAttribute("fill", "none");
                    }
                });
            });
        });
    </script>
</body>

</html>