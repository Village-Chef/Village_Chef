<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
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
</head>

<body class="bg-primary text-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <!-- Top Navigation -->
            <?php include 'header.php'; ?>

            <!-- Main Content Area -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none p-6 space-y-8">
                <!-- Quick Stats Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                    <!-- Stats Card -->
                    <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-400 mb-2">Total Revenue</p>
                                <p class="text-3xl font-bold text-accent">$89,432</p>
                            </div>
                            <div class="bg-accent/20 p-4 rounded-xl">
                                <i class="fas fa-chart-line text-2xl text-accent"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-green-400 text-sm">
                            <i class="fas fa-arrow-up mr-2"></i>
                            <span>12.5% increase</span>
                        </div>
                    </div>

                    <!-- Repeat similar cards for Orders, Users, Restaurants -->
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Revenue Chart -->
                        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold">Revenue Overview</h2>
                                <div class="bg-accent/20 text-accent px-4 py-2 rounded-lg">
                                    Last 30 Days
                                </div>
                            </div>
                            <!-- Chart placeholder -->
                            <div class="h-64 bg-gray-700/30 rounded-xl animate-pulse"></div>
                        </div>

                        <!-- Order Status Distribution -->
                        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
                            <h2 class="text-xl font-bold mb-6">Order Status</h2>
                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                            <span>Completed</span>
                                        </div>
                                        <span class="font-bold">65%</span>
                                    </div>
                                    <div class="h-2 bg-gray-700 rounded-full">
                                        <div class="h-2 bg-green-500 rounded-full w-3/4"></div>
                                    </div>
                                </div>
                                <!-- Add other statuses -->
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-8">
                        <!-- Recent Activities -->
                        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
                            <h2 class="text-xl font-bold mb-6">Recent Activities</h2>
                            <div class="space-y-6">
                                <div class="flex items-start">
                                    <div class="bg-accent/20 p-3 rounded-lg mr-4">
                                        <i class="fas fa-user-plus text-accent"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium">New customer registration</p>
                                        <p class="text-sm text-gray-400">John Doe signed up</p>
                                        <p class="text-xs text-gray-500 mt-1">2 hours ago</p>
                                    </div>
                                </div>
                                <!-- Add more activities -->
                            </div>
                        </div>

                        <!-- Top Products -->
                        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
                            <h2 class="text-xl font-bold mb-6">Popular Menu Items</h2>
                            <div class="space-y-6">
                                <div class="flex items-center">
                                    <img src="burger.jpg" class="w-12 h-12 rounded-lg object-cover mr-4">
                                    <div class="flex-1">
                                        <p class="font-medium">Classic Burger</p>
                                        <p class="text-sm text-gray-400">Burger King</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-accent font-bold">$12.99</p>
                                        <p class="text-sm text-gray-400">128 orders</p>
                                    </div>
                                </div>
                                <!-- Add more menu items -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Table -->
                <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-700">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-bold">Recent Orders</h2>
                            <a href="#" class="bg-accent/20 text-accent px-4 py-2 rounded-lg hover:bg-accent/30 transition">
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
                                <tr class="hover:bg-gray-700/10 transition">
                                    <td class="px-6 py-4 text-accent font-medium">#ORD-1289</td>
                                    <td class="px-6 py-4">John Doe</td>
                                    <td class="px-6 py-4">Burger King</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-900/30 text-green-400">
                                            Delivered
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">$42.50</td>
                                </tr>
                                <!-- Add more rows -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>