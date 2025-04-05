<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$admin = isset($_SESSION['admin']) ? $_SESSION['admin'] : null;
$current_page = basename($_SERVER['PHP_SELF']);
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

        function openModal() {
            document.getElementById('logOut').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('logOut').classList.add('hidden');
        }
    </script>
    <!-- Heroicons (for icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 border-r border-gray-800">
                <!-- Sidebar component -->
                <div class="flex flex-col flex-grow bg-primary pt-6 pb-4 overflow-y-auto">
                    <!-- Logo Section -->
                    <div class="flex items-center justify-center pb-6">
                        <div class="mr-2">
                            <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="text-black w-6 h-6" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chef-hat">
                                    <path
                                        d="M17 21a1 1 0 0 0 1-1v-5.35c0-.457.316-.844.727-1.041a4 4 0 0 0-2.134-7.589 5 5 0 0 0-9.186 0 4 4 0 0 0-2.134 7.588c.411.198.727.585.727 1.041V20a1 1 0 0 0 1 1Z" />
                                    <path d="M6 17h12" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-yellow-500 font-bold italic text-xl leading-none">Village</span>
                            <span class="font-bold text-xl leading-none">CHEF</span>
                        </div>
                    </div>

                    <!-- Navigation Menu -->
                    <nav class="flex-1 px-4 space-y-2">
                        <!-- Dashboard Link -->
                        <nav class="flex-1 px-4 space-y-2">
                            <!-- Dashboard Link -->
                            <a href="dashboard.php"
                                class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl <?php echo ($current_page === 'dashboard.php') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> transition-all duration-200">
                                <i
                                    class="fas fa-home mr-3 text-lg <?php echo ($current_page === 'dashboard.php') ? 'text-accent' : 'text-gray-400'; ?>"></i>
                                Dashboard
                                <?php if ($current_page === 'dashboard.php'): ?>
                                    <i class="fas fa-chevron-right ml-auto text-accent text-xs"></i>
                                <?php else: ?>
                                    <i
                                        class="fas fa-chevron-right ml-auto text-gray-400 text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                <?php endif; ?>
                            </a>

                            <!-- Users Link -->
                            <a href="users.php"
                                class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl <?php echo ($current_page === 'users.php') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> transition-all duration-200">
                                <i
                                    class="fas fa-users mr-3 text-lg <?php echo ($current_page === 'users.php') ? 'text-accent' : 'text-gray-400'; ?>"></i>
                                Users
                                <?php if ($current_page === 'users.php'): ?>
                                    <i class="fas fa-chevron-right ml-auto text-accent text-xs"></i>
                                <?php else: ?>
                                    <span class="ml-auto bg-accent/20 text-accent px-2 py-1 text-xs rounded-md">24
                                        New</span>
                                <?php endif; ?>
                            </a>

                            <!-- Restaurants Link -->
                            <a href="restaurants.php"
                                class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl <?php echo ($current_page === 'restaurants.php') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> transition-all duration-200">
                                <i
                                    class="fas fa-store mr-3 text-lg <?php echo ($current_page === 'restaurants.php') ? 'text-accent' : 'text-gray-400'; ?>"></i>
                                Restaurants
                                <?php if ($current_page === 'restaurants.php'): ?>
                                    <i class="fas fa-chevron-right ml-auto text-accent text-xs"></i>
                                <?php else: ?>
                                    <i
                                        class="fas fa-chevron-right ml-auto text-gray-400 text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                <?php endif; ?>
                            </a>

                            <!-- Menu Items Link -->
                            <a href="menuItems.php"
                                class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl <?php echo ($current_page === 'menuItems.php') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> transition-all duration-200">
                                <i
                                    class="fas fa-utensils mr-3 <?php echo ($current_page === 'menuItems.php') ? 'text-accent' : 'text-gray-400' ?>  group-hover:text-accent text-lg"></i>
                                Menu Items
                                <?php if ($current_page === 'menuItems.php'): ?>
                                    <i class="fas fa-chevron-right ml-auto text-accent text-xs"></i>
                                <?php else: ?>
                                    <i
                                        class="fas fa-chevron-right ml-auto text-gray-400 text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                <?php endif; ?>
                            </a>

                            <!-- Orders Link -->
                            <a href="orders.php"
                                class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl <?php echo ($current_page === 'orders.php') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> transition-all duration-200">
                                <i
                                    class="fas fa-shopping-cart mr-3 <?php echo ($current_page === 'orders.php') ? 'text-accent' : 'text-gray-400' ?> group-hover:text-accent text-lg"></i>
                                Orders
                                <?php if ($current_page === 'orders.php'): ?>
                                    <i class="fas fa-chevron-right ml-auto text-accent text-xs"></i>
                                <?php else: ?>
                                    <i
                                        class="fas fa-chevron-right ml-auto text-gray-400 text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                <?php endif; ?>
                            </a>

                            <!-- Payments Link -->
                            <a href="payments.php"
                                class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl <?php echo ($current_page === 'payments.php') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> transition-all duration-200">
                                <i
                                    class="fas fa-credit-card mr-3 <?php echo ($current_page === 'payments.php') ? 'text-accent' : 'text-gray-400' ?> group-hover:text-accent text-lg"></i>
                                Payments
                                <?php if ($current_page === 'payments.php'): ?>
                                    <i class="fas fa-chevron-right ml-auto text-accent text-xs"></i>
                                <?php else: ?>
                                    <i
                                        class="fas fa-chevron-right ml-auto text-gray-400 text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                <?php endif; ?>
                            </a>

                            <!-- Reviews Link -->
                            <a href="reviews.php"
                                class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl <?php echo ($current_page === 'reviews.php') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> transition-all duration-200">
                                <i
                                    class="fas fa-star mr-3 <?php echo ($current_page === 'reviews.php') ? 'text-accent' : 'text-gray-400' ?> group-hover:text-accent text-lg"></i>
                                Reviews
                                <?php if ($current_page === 'reviews.php'): ?>
                                    <i class="fas fa-chevron-right ml-auto text-accent text-xs"></i>
                                <?php else: ?>
                                    <i
                                        class="fas fa-chevron-right ml-auto text-gray-400 text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                <?php endif; ?>
                            </a>
                        </nav>

                        <!-- Profile & Logout Section -->
                        <div class="mt-auto px-4 pt-6 border-t border-gray-800">
                            <!-- Admin Profile -->
                            <div class="flex items-center mb-6">
                                <img class="h-10 w-10 rounded-full border-2 border-accent/30"
                                    src="<?php echo $admin['profile_pic']; ?>" alt="Admin profile">
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-white">
                                        <?php echo htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']); ?>
                                    </p>
                                    <p class="text-xs text-gray-400">Administrator</p>
                                </div>
                            </div>

                            <!-- Logout Button -->
                            <a onclick="openModal()"
                                class="flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-300 hover:bg-red-600/20 hover:text-red-400 transition-all cursor-pointer duration-200">
                                <i class="fas fa-sign-out-alt mr-3 text-gray-400 group-hover:text-red-400"></i>
                                Logout
                            </a>
                        </div>
                </div>
            </div>
        </div>

    </div>

    <div id="logOut" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50">
        <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-accent">Logout</h1>
                <button onclick="closeModal()" class="text-gray-400 hover:text-accent transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form method="POST" action="logout.php" class="space-y-6">
                <p class="text-gray-300">Are you sure you want to Logout?</p>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()"
                        class="px-6 py-2.5 border border-gray-600 rounded-xl text-gray-300 hover:bg-gray-700/30 hover:text-white transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-500 font-medium transition-colors">
                        Logout
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>