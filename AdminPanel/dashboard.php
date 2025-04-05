<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

require '../dbCon.php';
$foodies = new Foodies();

try {
    $paymentSummary = $foodies->getPaymentSummary();
    $orders = $foodies->getAllOrders();
    $users = $foodies->getAllUsers();
    $restaurants = $foodies->getAllRestaurants();
    $menuItems = $foodies->getAllMenuItems();

    // Calculate stats
    $totalRevenue = $paymentSummary['total_amount'] ?? 0;
    $totalOrders = count($orders);
    $totalUsers = count($users);
    $activeRestaurants = count(array_filter($restaurants, fn($r) => $r['status'] === 'open'));

    // Order status distribution
    $statusCounts = array_reduce($orders, function ($carry, $order) {
        $status = $order['status'];
        $carry[$status] = ($carry[$status] ?? 0) + 1;
        return $carry;
    }, []);

    // Recent activities (last 5 users and orders)
    $recentUsers = array_slice(array_reverse($users), 0, 5);
    $recentOrders = array_slice(array_reverse($orders), 0, 5);

    // Recent orders for table
    $recentOrdersTable = array_slice($orders, -5, 5);
    // Existing data fetches
    $paymentSummary = $foodies->getPaymentSummary();
    $orders = $foodies->getAllOrders();
    $users = $foodies->getAllUsers();
    $restaurants = $foodies->getAllRestaurants();
    $menuItems = $foodies->getAllMenuItems();
    $payments = $foodies->getAllPayments();

    // Chart 1: Revenue Trend Data (Last 30 Days)
    $revenueData = [];
    $thirtyDaysAgo = new DateTime('-30 days');
    foreach ($payments as $payment) {
        $paymentDate = new DateTime($payment['payment_date']);
        if ($paymentDate >= $thirtyDaysAgo) {
            $dateKey = $paymentDate->format('Y-m-d');
            $revenueData[$dateKey] = ($revenueData[$dateKey] ?? 0) + $payment['amount'];
        }
    }

    // Chart 2: Order Status Distribution
    $statusCounts = array_count_values(array_column($orders, 'status'));

    // Chart 3: Payment Method Distribution
    $methodCounts = array_count_values(array_column($payments, 'payment_method'));

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

    // Sort by order count (descending) and take top 5
    usort($topMenuItems, fn($a, $b) => $b['order_count'] - $a['order_count']);
    $topMenuItems = array_slice($topMenuItems, 0, 5);

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
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-primary text-gray-100">
    <div class="flex h-screen overflow-hidden">
        <?php include 'sidebar.php'; ?>

        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <?php include 'header.php'; ?>

            <main class="flex-1 relative overflow-y-auto focus:outline-none p-6 space-y-8">
                <!-- Quick Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                    <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-400 mb-2">Total Revenue</p>
                                <p class="text-3xl font-bold text-accent">₹<?= number_format($totalRevenue, 2) ?></p>
                            </div>
                            <div class="bg-accent/20 p-4 rounded-xl">
                                <i class="fas fa-chart-line text-2xl text-accent"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-400 mb-2">Total Orders</p>
                                <p class="text-3xl font-bold text-accent"><?= $totalOrders ?></p>
                            </div>
                            <div class="bg-accent/20 p-4 rounded-xl">
                                <i class="fas fa-shopping-cart text-2xl text-accent"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-400 mb-2">Registered Users</p>
                                <p class="text-3xl font-bold text-accent"><?= $totalUsers ?></p>
                            </div>
                            <div class="bg-accent/20 p-4 rounded-xl">
                                <i class="fas fa-users text-2xl text-accent"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-400 mb-2">Active Restaurants</p>
                                <p class="text-3xl font-bold text-accent"><?= $activeRestaurants ?></p>
                            </div>
                            <div class="bg-accent/20 p-4 rounded-xl">
                                <i class="fas fa-store text-2xl text-accent"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Revenue Trend Chart -->
                    <div class="lg:col-span-2 bg-gray-800 p-6 rounded-2xl border border-gray-700">
                        <h2 class="text-xl font-bold mb-6">Revenue Trend</h2>
                        <canvas id="revenueChart"></canvas>
                    </div>

                    <!-- Payment Methods Chart -->
                    <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
                        <h2 class="text-xl font-bold mb-6">Payment Methods</h2>
                        <canvas id="paymentChart"></canvas>
                    </div>


                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Order Status Chart -->
                    <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
                        <h2 class="text-xl font-bold mb-6">Order Status</h2>
                        <div class="h-64 justify-center items-center flex mx-auto">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>

                    <!-- User Registrations Chart -->
                    <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
                        <h2 class="text-xl font-bold mb-6">User Registrations</h2>
                        <div class="h-64 justify-center items-center flex mx-auto">
                            <canvas id="usersChart"></canvas>
                        </div>
                    </div>

                    <!-- Top Restaurants Chart -->
                    <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
                        <h2 class="text-xl font-bold mb-6">Top Restaurants</h2>
                        <div class="h-64 justify-center items-center flex mx-auto">
                            <canvas id="restaurantsChart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Order Status Distribution -->
                        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
                            <h2 class="text-xl font-bold mb-6">Order Status</h2>
                            <div class="grid grid-cols-2 gap-6">
                                <?php foreach ($statusCounts as $status => $count):
                                    $percentage = $totalOrders > 0 ? ($count / $totalOrders) * 100 : 0;
                                    $color = match ($status) {
                                        'delivered' => 'bg-green-500',
                                        'pending' => 'bg-yellow-500',
                                        'cancelled' => 'bg-red-500',
                                        default => 'bg-gray-500'
                                    };
                                    ?>
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <span class="w-2 h-2 <?= $color ?> rounded-full mr-2"></span>
                                                <span><?= ucfirst($status) ?></span>
                                            </div>
                                            <span class="font-bold"><?= round($percentage) ?>%</span>
                                        </div>
                                        <div class="h-2 bg-gray-700 rounded-full">
                                            <div class="h-2 <?= $color ?> rounded-full" style="width: <?= $percentage ?>%">
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- New Top Selling Menu Items Section -->
                        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
                            <h2 class="text-xl font-bold mb-6">Top Selling Menu Items</h2>
                            <div class="space-y-6">
                                <?php foreach ($topMenuItems as $item): ?>
                                    <div class="flex items-center">
                                        <img src="<?= $item['image_url'] ?>" class="w-12 h-12 rounded-lg object-cover mr-4">
                                        <div class="flex-1">
                                            <p class="font-medium"><?= $item['item_name'] ?></p>
                                            <p class="text-sm text-gray-400"><?= $item['restaurant_name'] ?></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-accent font-bold"><?= $item['order_count'] ?> orders</p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-8">
                        <!-- Recent Activities -->
                        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
                            <h2 class="text-xl font-bold mb-6">Recent Activities</h2>
                            <div class="space-y-6">
                                <?php foreach ($recentUsers as $user): ?>
                                    <div class="flex items-start">
                                        <div class="bg-accent/20 p-3 rounded-lg mr-4">
                                            <i class="fas fa-user-plus text-accent"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium">New customer registration</p>
                                            <p class="text-sm text-gray-400"><?= $user['first_name'] ?>
                                                <?= $user['last_name'] ?>
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <?= date('M j, Y g:i A', strtotime($user['created_at'])) ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Top Products -->
                        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
                            <h2 class="text-xl font-bold mb-6">Popular Menu Items</h2>
                            <div class="space-y-6">
                                <?php foreach ($menuItems as $item): ?>
                                    <div class="flex items-center">
                                        <img src="<?= $item['image_url'] ?>" class="w-12 h-12 rounded-lg object-cover mr-4">
                                        <div class="flex-1">
                                            <p class="font-medium"><?= $item['item_name'] ?></p>
                                            <p class="text-sm text-gray-400"><?= $item['restaurant_name'] ?></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-accent font-bold">₹<?= $item['price'] ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Table -->
                <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-700">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-bold">Recent Orders</h2>
                            <a href="orders.php"
                                class="bg-accent/20 text-accent px-4 py-2 rounded-lg hover:bg-accent/30 transition">
                                View All Orders
                            </a>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-700/30">
                                <tr>
                                    <th class="px-6 py-4 text-left">Order ID</th>
                                    <th class="px-6 py-4 text-left">Customer</th>
                                    <th class="px-6 py-4 text-left">Restaurant</th>
                                    <th class="px-6 py-4 text-left">Status</th>
                                    <th class="px-6 py-4 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                <?php foreach ($recentOrdersTable as $order):
                                    $statusColor = match ($order['status']) {
                                        'delivered' => 'bg-green-900/30 text-green-400',
                                        'pending' => 'bg-yellow-900/30 text-yellow-400',
                                        'cancelled' => 'bg-red-900/30 text-red-400',
                                        default => 'bg-gray-700/30 text-gray-400'
                                    };
                                    ?>
                                    <tr class="hover:bg-gray-700/10 transition">
                                        <td class="px-6 py-4 text-accent font-medium"><?= $order['order_id'] ?></td>
                                        <td class="px-6 py-4"><?= $order['first_name'] ?>     <?= $order['last_name'] ?></td>
                                        <td class="px-6 py-4"><?= $order['restaurant_name'] ?></td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full <?= $statusColor ?>">
                                                <?= ucfirst($order['status']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">₹<?= number_format($order['total_amount'], 2) ?>
                                        </td>
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
            // Revenue Trend Line Chart
            new Chart(document.getElementById('revenueChart'), {
                type: 'line',
                data: {
                    labels: <?= json_encode(array_keys($revenueData)) ?>,
                    datasets: [{
                        label: 'Daily Revenue',
                        data: <?= json_encode(array_values($revenueData)) ?>,
                        borderColor: '#eab308',
                        backgroundColor: 'rgba(234, 179, 8, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { color: '#F3F4F6' },
                            grid: { color: '#374151' }
                        },
                        x: {
                            ticks: { color: '#F3F4F6' },
                            grid: { color: '#374151' }
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
                        borderColor: '#1F2937'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#F3F4F6' }
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
                            '#eab308', '#10B981', '#3B82F6', '#8B5CF6', '#EC4899'
                        ],
                        borderColor: '#1F2937',
                        borderWidth: 3,
                        spacing: 4
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '70%', // Makes it a thinner ring for a modern look
                    radius: '95%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#F3F4F6',
                                font: { size: 13 },
                                boxWidth: 15,
                                padding: 20
                            }
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
                        backgroundColor: (ctx) => {
                            const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 0, 256);
                            gradient.addColorStop(0, 'rgba(234, 179, 8, 0.4)');
                            gradient.addColorStop(1, 'rgba(234, 179, 8, 0)');
                            return gradient;
                        },
                        fill: true,
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: '#eab308'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Allows the chart to fill the container height
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: '#374151' },
                            ticks: { color: '#F3F4F6' }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#374151' },
                            ticks: { color: '#F3F4F6' }
                        }
                    }
                }
            });

            // Top Restaurants Horizontal Bar Chart (with Gradient Bars)
            new Chart(document.getElementById('restaurantsChart'), {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_column($topRestaurants, 'name')) ?>,
                    datasets: [{
                        data: <?= json_encode(array_column($topRestaurants, 'count')) ?>,
                        backgroundColor: [
                            '#eab308dd', // Varying opacity for visual distinction
                            '#eab308bb',
                            '#eab30899',
                            '#eab30877',
                            '#eab30855'
                        ],
                        borderColor: '#1F2937',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    indexAxis: 'y', // Horizontal bar chart
                    responsive: true,
                    maintainAspectRatio: false, // Allows the chart to fill the container height
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => `${ctx.raw} orders`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: '#374151', drawTicks: false },
                            ticks: { color: '#F3F4F6' }
                        },
                        y: {
                            grid: { display: false },
                            ticks: {
                                color: '#F3F4F6',
                                mirror: true,
                                z: 1,
                                font: { weight: '500' }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>