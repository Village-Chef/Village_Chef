<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

require '../dbCon.php';
$foodies = new Foodies();

try {
    date_default_timezone_set('Asia/Kolkata');
    $paymentSummary = $foodies->getPaymentSummary();
    $orders = $foodies->getAllOrders();
    $users = $foodies->getAllUsers();
    $restaurants = $foodies->getAllRestaurants();
    $menuItems = $foodies->getAllMenuItems();
    $payments = $foodies->getAllPayments();

    // Calculate stats
    $totalRevenue = $paymentSummary['total_successful_amount'] ?? 0;
    $totalOrders = count($orders);
    $totalUsers = count($users);
    $activeRestaurants = count(array_filter($restaurants, fn($r) => $r['status'] === 'open'));

    // Order status 
    $statusCounts = array_reduce($orders, function ($carry, $order) {
        $status = $order['status'];
        $carry[$status] = ($carry[$status] ?? 0) + 1;
        return $carry;
    }, []);



    // Recent activities
    $recentUsers = array_slice(array_reverse($users), 0, 8);
    $recentOrders = array_slice($orders, 5,5);
    // $recentOrdersTable = array_slice($orders, -5, 5);

    // Chart 1: Revenue Last 30 Days
    $revenueData = [];
    $thirtyDaysAgo = new DateTime('-30 days');
    foreach ($payments as $payment) {
        $paymentDate = new DateTime($payment['payment_date']);
        if ($paymentDate >= $thirtyDaysAgo) {
            $dateKey = $paymentDate->format('Y-m-d');
            $revenueData[$dateKey] = ($revenueData[$dateKey] ?? 0) + $payment['amount'];
        }
    }
    ksort($revenueData); // Sort the array by keys in ascending order

    // Chart 2: Order Status 
    $statusCounts = array_count_values(array_column($orders, 'status'));

    // Chart 3: Payment Method 
    $methodCounts = array_count_values(array_column($payments, 'payment_method'));

    // User registrations by date
    $userRegistrations = [];
    foreach ($users as $user) {
        $regDate = (new DateTime($user['created_at']))->format('Y-m-d');
        if (!isset($userRegistrations[$regDate])) {
            $userRegistrations[$regDate] = 0;
        }
        $userRegistrations[$regDate]++;
    }

    // Chart 5: Top Restaurants by Orders
    $restaurantOrders = [];
    foreach ($orders as $order) {
        $restaurantId = $order['restaurant_id'];
        if (!isset($restaurantOrders[$restaurantId])) {
            $restaurantOrders[$restaurantId] = [
                'name' => $order['restaurant_name'],
                'count' => 0
            ];
        }
        $restaurantOrders[$restaurantId]['count']++;
    }
    usort($restaurantOrders, fn($a, $b) => $b['count'] - $a['count']);
    $topRestaurants = array_slice($restaurantOrders, 0, 5);

    // Top menu items
    $menuOrderCounts = [];
    foreach ($orders as $order) {
        if (isset($order['items']) && is_array($order['items'])) {
            foreach ($order['items'] as $item) {
                $itemId = $item['item_id'];
                $menuOrderCounts[$itemId] = ($menuOrderCounts[$itemId] ?? 0) + $item['quantity'];
            }
        }
    }

    // Match counts with menu items and sort
    $topMenuItems = [];
    foreach ($menuItems as $item) {
        $itemId = $item['item_id'];
        if (isset($menuOrderCounts[$itemId])) {
            $topMenuItems[] = [
                'item_name' => $item['item_name'],
                'restaurant_name' => $item['restaurant_name'],
                'image_url' => $item['image_url'],
                'order_count' => $menuOrderCounts[$itemId]
            ];
        }
    }

    // sort by order count descending top 5
    usort($topMenuItems, fn($a, $b) => $b['order_count'] - $a['order_count']);
    $topMenuItems = array_slice($topMenuItems, 0, 5);
    
    // Get today's stats for comparison
    $todayDate = date('Y-m-d');
    $todayRevenue = 0;
    $todayOrders = 0;
    $todayUsers = 0;
    $todayRestaurants = 0;
    $topCustomers = $foodies->getTopCustomers(2);
    foreach ($payments as $payment) {
        $paymentDate = date('Y-m-d', strtotime($payment['payment_date']));
        if ($paymentDate === $todayDate) {
            $todayRevenue += $payment['amount'];
        }
    }

    foreach ($orders as $order) {
        $orderDate = date('Y-m-d', strtotime($order['order_date']));
        if ($orderDate === $todayDate) {
            $todayOrders++;
        }
    }

    foreach ($users as $user) {
        $userDate = date('Y-m-d', strtotime($user['created_at']));
        if ($userDate === $todayDate) {
            $todayUsers++;
        }
    }

    foreach ($restaurants as $restaurant) {
        $restaurantDate = date('Y-m-d', strtotime($restaurant['created_at']));
        if ($restaurantDate === $todayDate) {
            $todayRestaurants++;
        }
    }

} catch (Exception $e) {
    die("Error loading dashboard data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Food Ordering System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#000000',
                        accent: '#eab308',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        'glow': '0 0 20px rgba(234, 179, 8, 0.2)',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1f2937;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #eab308;
        }

        /* Smooth transitions */
        .transition-all {
            transition: all 0.3s ease;
        }

        /* Card hover effects */
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 0 10px rgba(234, 179, 8, 0.2);
        }

        /* Chart animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chart-container {
            animation: fadeIn 0.6s ease-out forwards;
        }

        /* Gradient backgrounds */
        .bg-gradient-dark {
            background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
        }

        .bg-gradient-accent {
            background: linear-gradient(135deg, rgba(234, 179, 8, 0.2) 0%, rgba(234, 179, 8, 0.05) 100%);
        }
    </style>
</head>

<body class="bg-primary text-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <?php include 'sidebar.php'; ?>

        <div id="mainContent" class="flex flex-col w-0 flex-1 overflow-hidden">
            <?php include 'header.php'; ?>

            <main class="flex-1 relative overflow-y-auto focus:outline-none p-6 space-y-8">


                <!-- Quick Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                    <a href="payments.php" class="block">
                        <div
                            class="stat-card bg-gradient-dark p-6 rounded-2xl border border-gray-700 shadow-xl transition-all duration-300 hover:shadow-glow">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-400 mb-1">Total Revenue</p>
                                    <p class="text-3xl font-bold text-white mb-1">
                                        ₹<?= number_format($totalRevenue, 2) ?></p>
                                    <div class="flex items-center text-sm">
                                        <?php if ($todayRevenue > 0): ?>
                                            <span class="text-green-400 flex items-center">
                                                <i class="fas fa-arrow-up mr-1"></i>
                                                ₹<?= number_format($todayRevenue, 2) ?> today
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-500">No revenue today</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="bg-gradient-accent p-4 rounded-xl">
                                    <i class="fas fa-chart-line text-2xl text-accent"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="orders.php" class="block">
                        <div
                            class="stat-card bg-gradient-dark p-6 rounded-2xl border border-gray-700 shadow-xl transition-all duration-300 hover:shadow-glow">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-400 mb-1">Total Orders</p>
                                    <p class="text-3xl font-bold text-white mb-1"><?= number_format($totalOrders) ?></p>
                                    <div class="flex items-center text-sm">
                                        <?php if ($todayOrders > 0): ?>
                                            <span class="text-green-400 flex items-center">
                                                <i class="fas fa-arrow-up mr-1"></i>
                                                <?= $todayOrders ?> new today
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-500">No orders today</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="bg-gradient-accent p-4 rounded-xl">
                                    <i class="fas fa-shopping-cart text-2xl text-accent"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="users.php" class="block">
                        <div
                            class="stat-card bg-gradient-dark p-6 rounded-2xl border border-gray-700 shadow-xl transition-all duration-300 hover:shadow-glow">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-400 mb-1">Registered Users</p>
                                    <p class="text-3xl font-bold text-white mb-1"><?= number_format($totalUsers) ?></p>
                                    <div class="flex items-center text-sm">
                                        <?php if ($todayUsers > 0): ?>
                                            <span class="text-green-400 flex items-center">
                                                <i class="fas fa-arrow-up mr-1"></i>
                                                <?= $todayUsers ?> new today
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-500">No new users today</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="bg-gradient-accent p-4 rounded-xl">
                                    <i class="fas fa-users text-2xl text-accent"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="restaurants.php" class="block">
                        <div
                            class="stat-card bg-gradient-dark p-6 rounded-2xl border border-gray-700 shadow-xl transition-all duration-300 hover:shadow-glow">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-400 mb-1">Active Restaurants</p>
                                    <p class="text-3xl font-bold text-white mb-1">
                                        <?= number_format($activeRestaurants) ?>
                                    </p>
                                    <div class="flex items-center text-sm">
                                        <?php if ($todayRestaurants > 0): ?>
                                            <span class="text-green-400 flex items-center">
                                                <i class="fas fa-arrow-up mr-1"></i>
                                                <?= $todayRestaurants ?> new today
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-500">No new restaurants today</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="bg-gradient-accent p-4 rounded-xl">
                                    <i class="fas fa-store text-2xl text-accent"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Charts Row 1 -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Revenue Trend Chart -->
                    <div class="lg:col-span-2 bg-gradient-dark p-6 rounded-2xl border border-gray-700 shadow-xl chart-container"
                        style="animation-delay: 0.1s">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-chart-line text-accent mr-2"></i>
                                Revenue Trend
                            </h2>
                            <!-- <div class="flex space-x-2">
                                <button
                                    class="px-3 py-1 bg-gray-700 rounded-lg text-sm text-gray-300 hover:bg-gray-600 transition-colors">Week</button>
                                <button
                                    class="px-3 py-1 bg-accent/20 rounded-lg text-sm text-accent hover:bg-accent/30 transition-colors">Month</button>
                                <button
                                    class="px-3 py-1 bg-gray-700 rounded-lg text-sm text-gray-300 hover:bg-gray-600 transition-colors">Year</button>
                            </div> -->
                        </div>
                        <div class="w-full h-[300px]">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>

                    <!-- Payment Methods Chart -->
                    <div class="bg-gradient-dark p-6 rounded-2xl border border-gray-700 shadow-xl chart-container"
                        style="animation-delay: 0.2s">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-credit-card text-accent mr-2"></i>
                                Payment Methods
                            </h2>
                            <!-- <button class="text-gray-400 hover:text-white transition-colors">
                                <i class="fas fa-ellipsis-v"></i>
                            </button> -->
                        </div>
                        <div class="flex items-center justify-center h-[300px]">
                            <canvas id="paymentChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Charts Row 2 -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Order Status Chart -->
                    <div class="bg-gradient-dark p-6 rounded-2xl border border-gray-700 shadow-xl chart-container"
                        style="animation-delay: 0.3s">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-tasks text-accent mr-2"></i>
                                Order Status
                            </h2>
                            <!-- <button class="text-gray-400 hover:text-white transition-colors">
                                <i class="fas fa-ellipsis-v"></i>
                            </button> -->
                        </div>
                        <div class="h-64 justify-center items-center flex mx-auto">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>

                    <!-- User Registrations Chart -->
                    <div class="bg-gradient-dark p-6 rounded-2xl border border-gray-700 shadow-xl chart-container"
                        style="animation-delay: 0.4s">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-user-plus text-accent mr-2"></i>
                                User Registrations
                            </h2>
                            <!-- <button class="text-gray-400 hover:text-white transition-colors">
                                <i class="fas fa-ellipsis-v"></i>
                            </button> -->
                        </div>
                        <div class="h-64 justify-center items-center flex mx-auto">
                            <canvas id="usersChart"></canvas>
                        </div>
                    </div>

                    <!-- Top Restaurants Chart -->
                    <div class="bg-gradient-dark p-6 rounded-2xl border border-gray-700 shadow-xl chart-container"
                        style="animation-delay: 0.5s">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-award text-accent mr-2"></i>
                                Top Restaurants
                            </h2>
                            <!-- <button class="text-gray-400 hover:text-white transition-colors">
                                <i class="fas fa-ellipsis-v"></i>
                            </button> -->
                        </div>
                        <div class="h-64 justify-center items-center flex mx-auto">
                            <canvas id="restaurantsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Order Status Distribution -->
                        <div class="bg-gradient-dark p-6 rounded-2xl border border-gray-700 shadow-xl chart-container"
                            style="animation-delay: 0.6s">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-white flex items-center">
                                    <i class="fas fa-chart-pie text-accent mr-2"></i>
                                    Order Status Distribution
                                </h2>
                                <!-- <button class="text-gray-400 hover:text-white transition-colors">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button> -->
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <?php foreach ($statusCounts as $status => $count):
                                    $percentage = $totalOrders > 0 ? ($count / $totalOrders) * 100 : 0;
                                    $color = match ($status) {
                                        'delivered' => 'bg-green-500',
                                        'pending' => 'bg-yellow-500',
                                        'cancelled' => 'bg-red-500',
                                        default => 'bg-gray-500'
                                    };
                                    $textColor = match ($status) {
                                        'delivered' => 'text-green-500',
                                        'pending' => 'text-yellow-500',
                                        'cancelled' => 'text-red-500',
                                        default => 'text-gray-500'
                                    };
                                    ?>
                                    <div class="space-y-4 bg-gray-800/50 p-4 rounded-xl">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <span class="w-3 h-3 <?= $color ?> rounded-full mr-2"></span>
                                                <span class="font-medium"><?= ucfirst($status) ?></span>
                                            </div>
                                            <span class="font-bold <?= $textColor ?>"><?= round($percentage) ?>%</span>
                                        </div>
                                        <div class="h-2 bg-gray-700 rounded-full overflow-hidden">
                                            <div class="h-2 <?= $color ?> rounded-full transition-all duration-1000"
                                                style="width: <?= $percentage ?>%">
                                            </div>
                                        </div>
                                        <div class="flex justify-between text-sm text-gray-400">
                                            <span><?= $count ?> orders</span>
                                            <span>Total: <?= $totalOrders ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Top Selling Menu Items Section -->
                        <div class="bg-gradient-dark p-6 rounded-2xl border border-gray-700 shadow-xl chart-container"
                            style="animation-delay: 0.7s">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-white flex items-center">
                                    <i class="fas fa-utensils text-accent mr-2"></i>
                                    Top Selling Menu Items
                                </h2>
                                <a href="menuItems.php"
                                    class="text-accent hover:text-accent/80 text-sm font-medium transition-colors">
                                    View All
                                </a>
                            </div>
                            <div class="space-y-6">
                                <?php foreach ($topMenuItems as $index => $item): ?>
                                    <div class="flex items-center p-3 rounded-xl hover:bg-gray-800/50 transition-colors">
                                        <div class="flex-shrink-0 relative">
                                            <img src="<?= $item['image_url'] ?>"
                                                class="w-16 h-16 rounded-xl object-cover border border-gray-700">
                                            <div
                                                class="absolute -top-2 -left-2 w-6 h-6 bg-accent rounded-full flex items-center justify-center text-xs font-bold text-black">
                                                <?= $index + 1 ?>
                                            </div>
                                        </div>
                                        <div class="flex-1 ml-4">
                                            <p class="font-medium text-white"><?= $item['item_name'] ?></p>
                                            <p class="text-sm text-gray-400 flex items-center">
                                                <i class="fas fa-store text-xs mr-1"></i>
                                                <?= $item['restaurant_name'] ?>
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-accent font-bold"><?= $item['order_count'] ?></p>
                                            <p class="text-xs text-gray-400">orders</p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-8">
                        <!-- Recent Activities -->
                        <div class="bg-gradient-dark p-6 rounded-2xl border border-gray-700 shadow-xl chart-container"
                            style="animation-delay: 0.8s">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-white flex items-center">
                                    <i class="fas fa-bell text-accent mr-2"></i>
                                    Recent Activities
                                </h2>
                                <a href="#"
                                    class="text-accent hover:text-accent/80 text-sm font-medium transition-colors">
                                    View All
                                </a>
                            </div>
                            <div class="space-y-6 relative">
                                <!-- Timeline line -->
                                <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-gray-700/70"></div>

                                <?php foreach ($recentUsers as $index => $user):
                                    // Only show first 5 users to avoid overcrowding
                                    if ($index >= 5)
                                        break;
                                    ?>
                                    <div class="flex items-start relative">
                                        <!-- Timeline dot -->
                                        <div
                                            class="absolute left-4 top-4 w-2.5 h-2.5 rounded-full bg-accent transform -translate-x-1/2">
                                        </div>

                                        <div class="bg-accent/20 p-3 rounded-xl ml-8 mr-4 flex-shrink-0">
                                            <i class="fas fa-user-plus text-accent"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-white">New customer registration</p>
                                            <p class="text-sm text-gray-400">
                                                <?= $user['first_name'] ?>     <?= $user['last_name'] ?>
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <?= date('M j, Y g:i A', strtotime($user['created_at'])) ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <?php if (count($recentUsers) > 5): ?>
                                    <div class="text-center pt-2">
                                        <a href="users.php"
                                            class="text-accent hover:text-accent/80 text-sm font-medium transition-colors">
                                            View <?= count($recentUsers) - 5 ?> more activities
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Quick Actions Card -->
                        <div class="bg-gradient-dark  p-6 rounded-2xl border border-gray-700 shadow-xl chart-container"
                            style="animation-delay: 0.9s">
                            <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                                <i class="fas fa-bolt text-accent mr-2"></i>
                                Quick Actions
                            </h2>
                            <div class="grid grid-cols-2 gap-4">
                                <a href="addMenuItem.php"
                                    class="bg-gray-800/50 hover:bg-gray-700/50 p-4 rounded-xl flex flex-col items-center justify-center text-center transition-colors">
                                    <div class="bg-accent/20 p-3 rounded-full mb-2">
                                        <i class="fas fa-utensils text-accent"></i>
                                    </div>
                                    <span class="text-sm font-medium">Add Menu Item</span>
                                </a>
                                <a href="addRestaurant.php"
                                    class="bg-gray-800/50 hover:bg-gray-700/50 p-4 rounded-xl flex flex-col items-center justify-center text-center transition-colors">
                                    <div class="bg-accent/20 p-3 rounded-full mb-2">
                                        <i class="fas fa-store text-accent"></i>
                                    </div>
                                    <span class="text-sm font-medium">Add Restaurant</span>
                                </a>
                                <a href="orders.php?status=pending"
                                    class="bg-gray-800/50 hover:bg-gray-700/50 p-4 rounded-xl flex flex-col items-center justify-center text-center transition-colors">
                                    <div class="bg-accent/20 p-3 rounded-full mb-2">
                                        <i class="fas fa-clock text-accent"></i>
                                    </div>
                                    <span class="text-sm font-medium">Pending Orders</span>
                                </a>
                                <a href="generate_payment_report.php"
                                    class="bg-gray-800/50 hover:bg-gray-700/50 p-4 rounded-xl flex flex-col items-center justify-center text-center transition-colors">
                                    <div class="bg-accent/20 p-3 rounded-full mb-2">
                                        <i class="fas fa-chart-bar text-accent"></i>
                                    </div>
                                    <span class="text-sm font-medium">View Reports</span>
                                </a>
                            </div>
                        </div>

                        <!-- System Status Card -->
                        <!-- Top Customers Card -->
                        <div class="bg-gradient-dark p-6 rounded-2xl border border-gray-700 shadow-xl chart-container"
                            style="animation-delay: 1s">
                            <h2 class="text-xl font-bold text-white mb-6 flex items-center">
                                <i class="fas fa-users text-accent mr-2"></i>
                                Top Customers by Orders
                            </h2>
                            <div class="space-y-4">
                                <?php if (empty($topCustomers)): ?>
                                    <p class="text-gray-400 text-center">No customer data available.</p>
                                <?php else: ?>
                                    <?php foreach ($topCustomers as $customer): ?>
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center mr-3">
                                                    <i class="fas fa-user text-gray-400"></i>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-white">
                                                        <?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']) ?>
                                                    </p>
                                                    <p class="text-sm text-gray-400"><?= htmlspecialchars($customer['email']) ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-accent font-bold"><?= $customer['order_count'] ?></p>
                                                <p class="text-xs text-gray-400">orders</p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Table -->
                <div class="bg-gradient-dark rounded-2xl border border-gray-700 shadow-xl overflow-hidden chart-container"
                    style="animation-delay: 1.1s">
                    <div class="p-6 border-b border-gray-700">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-shopping-bag text-accent mr-2"></i>
                                Recent Orders
                            </h2>
                            <a href="orders.php"
                                class="bg-accent/20 text-accent px-4 py-2 rounded-lg hover:bg-accent/30 transition-colors">
                                View All Orders
                            </a>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-800/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Order ID</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Customer</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Restaurant</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Status</th>
                                    <th class="px-6 py-4 text-right text-sm font-medium text-gray-400">Amount</th>
                                    <!-- <th class="px-6 py-4 text-right text-sm font-medium text-gray-400">Actions</th> -->
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700/50">
                                <?php foreach ($recentOrders as $order):
                                    $statusColor = match ($order['status']) {
                                        'delivered' => 'bg-green-900/30 text-green-400',
                                        'pending' => 'bg-yellow-900/30 text-yellow-400',
                                        'cancelled' => 'bg-red-900/30 text-red-400',
                                        default => 'bg-gray-700/30 text-gray-400'
                                    };
                                    ?>
                                    <tr class="hover:bg-gray-800/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <span class="text-accent font-medium"><?= $order['order_id'] ?></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center mr-3">
                                                    <i class="fas fa-user text-gray-400 text-xs"></i>
                                                </div>
                                                <span><?= $order['first_name'] ?>     <?= $order['last_name'] ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-store text-accent mr-2 text-xs"></i>
                                                <?= $order['restaurant_name'] ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?= $statusColor ?>">
                                                <?= ucfirst($order['status']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right font-medium">
                                            ₹<?= number_format($order['total_amount'], 2) ?>
                                        </td>
                                        <!-- <td class="px-6 py-4 text-right">
                                            <a href="viewOrder.php?id=<?= $order['order_id'] ?>"
                                                class="text-accent hover:text-accent/80 mr-3">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="updateOrder.php?id=<?= $order['order_id'] ?>"
                                                class="text-gray-400 hover:text-white">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td> -->
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Set global Chart.js options
            Chart.defaults.color = '#9CA3AF';
            Chart.defaults.borderColor = '#374151';
            Chart.defaults.font.family = "'Inter', sans-serif";

            // Revenue Trend Line Chart
            new Chart(document.getElementById('revenueChart'), {
                type: 'line',
                data: {
                    labels: <?= json_encode(array_keys($revenueData)) ?>,
                    datasets: [{
                        label: 'Daily Revenue',
                        data: <?= json_encode(array_values($revenueData)) ?>,
                        borderColor: '#eab308',
                        backgroundColor: function (context) {
                            const chart = context.chart;
                            const { ctx, chartArea } = chart;
                            if (!chartArea) {
                                return null;
                            }
                            const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                            gradient.addColorStop(0, 'rgba(234, 179, 8, 0)');
                            gradient.addColorStop(0.5, 'rgba(234, 179, 8, 0.1)');
                            gradient.addColorStop(1, 'rgba(234, 179, 8, 0.2)');
                            return gradient;
                        },
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#eab308',
                        pointBorderColor: '#000000',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            titleFont: { weight: 'bold' },
                            bodyFont: { size: 13 },
                            borderColor: '#4B5563',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: function (context) {
                                    return '₹' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(55, 65, 81, 0.3)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#9CA3AF',
                                padding: 10,
                                callback: function (value) {
                                    return '₹' + value.toLocaleString();
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                color: '#9CA3AF',
                                maxRotation: 0,
                                padding: 10
                            }
                        }
                    }
                }
            });

            // Payment Methods Doughnut Chart
            new Chart(document.getElementById('paymentChart'), {
                type: 'doughnut',
                data: {
                    labels: <?= json_encode(array_keys($methodCounts)) ?>,
                    datasets: [{
                        data: <?= json_encode(array_values($methodCounts)) ?>,
                        backgroundColor: [
                            '#eab308',
                            '#10B981',
                            '#3B82F6',
                            '#8B5CF6'
                        ],
                        borderColor: '#1F2937',
                        borderWidth: 5,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#F3F4F6',
                                padding: 20,
                                font: { size: 12 },
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            titleFont: { weight: 'bold' },
                            bodyFont: { size: 13 },
                            borderColor: '#4B5563',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false
                        }
                    }
                }
            });

            // Order Status Doughnut Chart
            new Chart(document.getElementById('statusChart'), {
                type: 'doughnut',
                data: {
                    labels: <?= json_encode(array_keys($statusCounts)) ?>,
                    datasets: [{
                        data: <?= json_encode(array_values($statusCounts)) ?>,
                        backgroundColor: [
                            '#10B981', // delivered - green
                            '#eab308', // pending - yellow
                            '#EF4444', // cancelled - red
                            '#8B5CF6', // other statuses - purple
                            '#EC4899'  // other statuses - pink
                        ],
                        borderColor: '#1F2937',
                        borderWidth: 5,
                        spacing: 4,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    radius: '95%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#F3F4F6',
                                font: { size: 12 },
                                boxWidth: 15,
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            titleFont: { weight: 'bold' },
                            bodyFont: { size: 13 },
                            borderColor: '#4B5563',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false
                        }
                    }
                }
            });

            // User Registrations Line Chart (Area Chart with Gradient)
            new Chart(document.getElementById('usersChart'), {
                type: 'line',
                data: {
                    labels: <?= json_encode(array_keys($userRegistrations)) ?>,
                    datasets: [{
                        label: 'Daily Signups',
                        data: <?= json_encode(array_values($userRegistrations)) ?>,
                        borderColor: '#eab308',
                        backgroundColor: function (context) {
                            const chart = context.chart;
                            const { ctx, chartArea } = chart;
                            if (!chartArea) {
                                return null;
                            }
                            const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                            gradient.addColorStop(0, 'rgba(234, 179, 8, 0)');
                            gradient.addColorStop(0.5, 'rgba(234, 179, 8, 0.1)');
                            gradient.addColorStop(1, 'rgba(234, 179, 8, 0.2)');
                            return gradient;
                        },
                        fill: true,
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: '#eab308',
                        pointBorderColor: '#000000',
                        pointBorderWidth: 2,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            titleFont: { weight: 'bold' },
                            bodyFont: { size: 13 },
                            borderColor: '#4B5563',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                color: '#9CA3AF',
                                maxRotation: 0,
                                padding: 10
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(55, 65, 81, 0.3)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#9CA3AF',
                                padding: 10,
                                precision: 0
                            }
                        }
                    }
                }
            });

            // Top Restaurants Horizontal Bar Chart 
            new Chart(document.getElementById('restaurantsChart'), {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_column($topRestaurants, 'name')) ?>,
                    datasets: [{
                        data: <?= json_encode(array_column($topRestaurants, 'count')) ?>,
                        backgroundColor: function (context) {
                            const chart = context.chart;
                            const { ctx, chartArea } = chart;
                            if (!chartArea) {
                                return null;
                            }
                            const gradient = ctx.createLinearGradient(chartArea.left, 0, chartArea.right, 0);
                            gradient.addColorStop(0, 'rgba(234, 179, 8, 0.8)');
                            gradient.addColorStop(1, 'rgba(234, 179, 8, 0.4)');
                            return gradient;
                        },
                        borderColor: '#1F2937',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                        hoverBackgroundColor: '#eab308'
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            titleFont: { weight: 'bold' },
                            bodyFont: { size: 13 },
                            borderColor: '#4B5563',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: (ctx) => `${ctx.raw} orders`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(55, 65, 81, 0.3)',
                                drawTicks: false,
                                drawBorder: false
                            },
                            ticks: {
                                color: '#9CA3AF',
                                padding: 10
                            }
                        },
                        y: {
                            grid: { display: false },
                            ticks: {
                                color: '#F3F4F6',
                                mirror: true,
                                z: 1,
                                font: { weight: '500' },
                                padding: 10
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>