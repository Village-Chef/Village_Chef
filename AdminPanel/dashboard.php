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
    <!-- Tailwind CSS CDN -->
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
    <!-- Heroicons (for icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64">
                <!-- Sidebar component -->
                <div class="flex flex-col flex-grow bg-primary pt-5 pb-4 overflow-y-auto">
                    <div class="flex items-center flex-shrink-0 px-4">
                        <h1 class="text-xl font-bold text-white">Food Admin</h1>
                    </div>
                    <div class="mt-5 flex-1 flex flex-col">
                        <nav class="flex-1 px-2 space-y-1">
                            <!-- Dashboard Link -->
                            <a href="dashboard.html"
                                class="bg-gray-900 text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-home mr-3 text-accent"></i>
                                Dashboard
                            </a>
                            <!-- Users Link -->
                            <a href="users.html"
                                class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-users mr-3 text-gray-400 group-hover:text-accent"></i>
                                Users
                            </a>
                            <!-- Restaurants Link -->
                            <a href="restaurants.html"
                                class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-store mr-3 text-gray-400 group-hover:text-accent"></i>
                                Restaurants
                            </a>
                            <!-- Menu Items Link -->
                            <a href="menu-items.html"
                                class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-utensils mr-3 text-gray-400 group-hover:text-accent"></i>
                                Menu Items
                            </a>
                            <!-- Orders Link -->
                            <a href="orders.html"
                                class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-shopping-cart mr-3 text-gray-400 group-hover:text-accent"></i>
                                Orders
                            </a>
                            <!-- Payments Link -->
                            <a href="payments.html"
                                class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-credit-card mr-3 text-gray-400 group-hover:text-accent"></i>
                                Payments
                            </a>
                            <!-- Reviews Link -->
                            <a href="reviews.html"
                                class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-star mr-3 text-gray-400 group-hover:text-accent"></i>
                                Reviews
                            </a>
                        </nav>
                    </div>
                    <!-- Logout Button -->
                    <div class="px-2 mt-6 mb-4">
                        <a href="logout.php"
                            class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-sign-out-alt mr-3 text-gray-400 group-hover:text-accent"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <!-- Top Navigation -->
            <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow">
                <button
                    class="md:hidden px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:bg-gray-100 focus:text-gray-600">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="flex-1 px-4 flex justify-between">
                    <div class="flex-1 flex">
                        <h1 class="text-2xl font-semibold text-gray-900 my-auto">Dashboard</h1>
                    </div>
                    <div class="ml-4 flex items-center md:ml-6">
                        <div class="ml-3 relative">
                            <div>
                                <button
                                    class="max-w-xs flex items-center text-sm rounded-full text-white focus:outline-none focus:shadow-solid">
                                    <img class="h-8 w-8 rounded-full"
                                        src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                        alt="">
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <!-- Total Users Card -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-accent/10 rounded-md p-3">
                                    <i class="fas fa-users text-accent text-xl"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">
                                            Total Users
                                        </dt>
                                        <dd>
                                            <div class="text-lg font-medium text-gray-900">
                                                1,482
                                            </div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-5 py-3">
                            <div class="text-sm">
                                <a href="users.html" class="font-medium text-accent hover:text-accent/80">
                                    View all
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Total Orders Card -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-accent/10 rounded-md p-3">
                                    <i class="fas fa-shopping-cart text-accent text-xl"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">
                                            Total Orders
                                        </dt>
                                        <dd>
                                            <div class="text-lg font-medium text-gray-900">
                                                12,345
                                            </div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-5 py-3">
                            <div class="text-sm">
                                <a href="orders.html" class="font-medium text-accent hover:text-accent/80">
                                    View all
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Total Restaurants Card -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-accent/10 rounded-md p-3">
                                    <i class="fas fa-store text-accent text-xl"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">
                                            Total Restaurants
                                        </dt>
                                        <dd>
                                            <div class="text-lg font-medium text-gray-900">
                                                248
                                            </div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-5 py-3">
                            <div class="text-sm">
                                <a href="restaurants.html" class="font-medium text-accent hover:text-accent/80">
                                    View all
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Total Revenue Card -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-accent/10 rounded-md p-3">
                                    <i class="fas fa-dollar-sign text-accent text-xl"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">
                                            Total Revenue
                                        </dt>
                                        <dd>
                                            <div class="text-lg font-medium text-gray-900">
                                                $89,432.78
                                            </div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-5 py-3">
                            <div class="text-sm">
                                <a href="payments.html" class="font-medium text-accent hover:text-accent/80">
                                    View details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Section -->
                <div class="bg-white shadow overflow-hidden sm:rounded-md mb-8">
                    <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Recent Orders
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            A list of the most recent orders.
                        </p>
                    </div>
                    <ul class="divide-y divide-gray-200">
                        <li>
                            <a href="orders.html" class="block hover:bg-gray-50">
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-accent truncate">
                                            Order #12345
                                        </p>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <p
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Delivered
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-user flex-shrink-0 mr-1.5 text-gray-400"></i>
                                                John Doe
                                            </p>
                                            <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                <i class="fas fa-store flex-shrink-0 mr-1.5 text-gray-400"></i>
                                                Burger King
                                            </p>
                                        </div>
                                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                            <i class="fas fa-calendar flex-shrink-0 mr-1.5 text-gray-400"></i>
                                            <p>
                                                March 20, 2023
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="orders.html" class="block hover:bg-gray-50">
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-accent truncate">
                                            Order #12344
                                        </p>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <p
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                In Progress
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-user flex-shrink-0 mr-1.5 text-gray-400"></i>
                                                Jane Smith
                                            </p>
                                            <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                <i class="fas fa-store flex-shrink-0 mr-1.5 text-gray-400"></i>
                                                Pizza Hut
                                            </p>
                                        </div>
                                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                            <i class="fas fa-calendar flex-shrink-0 mr-1.5 text-gray-400"></i>
                                            <p>
                                                March 20, 2023
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="orders.html" class="block hover:bg-gray-50">
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-accent truncate">
                                            Order #12343
                                        </p>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <p
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Cancelled
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-user flex-shrink-0 mr-1.5 text-gray-400"></i>
                                                Robert Johnson
                                            </p>
                                            <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                <i class="fas fa-store flex-shrink-0 mr-1.5 text-gray-400"></i>
                                                KFC
                                            </p>
                                        </div>
                                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                            <i class="fas fa-calendar flex-shrink-0 mr-1.5 text-gray-400"></i>
                                            <p>
                                                March 19, 2023
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Top Restaurants Section -->
                <div class="bg-white shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Top Restaurants
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Restaurants with the highest number of orders.
                        </p>
                    </div>
                    <ul class="divide-y divide-gray-200">
                        <li>
                            <a href="restaurants.html" class="block hover:bg-gray-50">
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-accent truncate">
                                            Burger King
                                        </p>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <p
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-shopping-cart flex-shrink-0 mr-1.5 text-gray-400"></i>
                                                1,245 Orders
                                            </p>
                                            <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                <i class="fas fa-star flex-shrink-0 mr-1.5 text-gray-400"></i>
                                                4.8 Rating
                                            </p>
                                        </div>
                                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                            <i class="fas fa-dollar-sign flex-shrink-0 mr-1.5 text-gray-400"></i>
                                            <p>
                                                $24,500 Revenue
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="restaurants.html" class="block hover:bg-gray-50">
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-accent truncate">
                                            Pizza Hut
                                        </p>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <p
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-shopping-cart flex-shrink-0 mr-1.5 text-gray-400"></i>
                                                1,180 Orders
                                            </p>
                                            <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                <i class="fas fa-star flex-shrink-0 mr-1.5 text-gray-400"></i>
                                                4.6 Rating
                                            </p>
                                        </div>
                                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                            <i class="fas fa-dollar-sign flex-shrink-0 mr-1.5 text-gray-400"></i>
                                            <p>
                                                $22,340 Revenue
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="restaurants.html" class="block hover:bg-gray-50">
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-accent truncate">
                                            KFC
                                        </p>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <p
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-shopping-cart flex-shrink-0 mr-1.5 text-gray-400"></i>
                                                1,050 Orders
                                            </p>
                                            <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                <i class="fas fa-star flex-shrink-0 mr-1.5 text-gray-400"></i>
                                                4.5 Rating
                                            </p>
                                        </div>
                                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                            <i class="fas fa-dollar-sign flex-shrink-0 mr-1.5 text-gray-400"></i>
                                            <p>
                                                $19,870 Revenue
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </main>
        </div>
    </div>
</body>

</html>