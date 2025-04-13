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

        // Function to toggle sidebar between expanded and collapsed states
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const sidebarContent = document.getElementById('sidebarContent');
            const mainContent = document.getElementById('mainContent');
            const textElements = document.querySelectorAll('.nav-text');
            const logoText = document.getElementById('logoText');
            const profileInfo = document.getElementById('profileInfo');

            if (sidebar.classList.contains('w-64')) {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');
                sidebarContent.classList.add('items-center');

                logoText.classList.add('hidden');
                profileInfo.classList.add('hidden');
                textElements.forEach(el => el.classList.add('hidden'));
            } else {
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-64');
                sidebarContent.classList.remove('items-center');
                logoText.classList.remove('hidden');
                profileInfo.classList.remove('hidden');
                textElements.forEach(el => el.classList.remove('hidden'));
            }
        }

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
            } else {
                sidebar.classList.add('hidden');
            }
        }

        function openLoginSideModal() {
            document.getElementById('logOut').classList.remove('hidden');
        }

        function closeLoginSideModal() {
            document.getElementById('logOut').classList.add('hidden');
        }

        // Function to handle Google sign-in
        function handleGoogleSignIn() {
            // This would typically integrate with Google OAuth
            alert('Google Sign-In functionality would be implemented here');
        }

    </script>
    <style>
        /* Add to your <style> section */
        /* .sidebar-collapsed .nav-text {
            display: none !important;
        } */
    </style>
    <!-- Heroicons (for icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile Toggle Button -->
        <button id="mobileSidebarToggle" onclick="toggleMobileSidebar()"
            class="fixed top-4 left-4 z-50  md:hidden bg-primary text-white p-2 rounded-lg shadow-lg">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Sidebar -->
        <div id="sidebar"
            class="fixed md:relative hidden md:block h-full z-40 transition-all duration-300 ease-in-out w-64">
            <div id="sidebarContent" class="flex flex-col h-full bg-primary pt-6 pb-4 overflow-y-auto">
                <!-- Logo and Toggle Section -->

                <div class="px-8 mb-4 hidden md:block">
                    <!-- Toggle Button -->
                    <button onclick="toggleSidebar()"
                        class="text-gray-300 hover:text-accent transition-colors w-fit text-2xl">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>

                <!-- <div class="flex items-center justify-center "> -->

                    <div class="flex items-center md:mt-0 mt-10 justify-between px-6  pb-6">
                        <div class="flex items-center">
                            <div class="mr-2">
                                <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="text-black w-6 h-6" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path
                                            d="M17 21a1 1 0 0 0 1-1v-5.35c0-.457.316-.844.727-1.041a4 4 0 0 0-2.134-7.589a5 5 0 0 0-9.186 0a4 4 0 0 0-2.134 7.588c.411.198.727.585.727 1.041V20a1 1 0 0 0 1 1Z" />
                                        <path d="M6 17h12" />
                                    </svg>
                                </div>
                            </div>
                            <div id="logoText" class="flex flex-col">
                                <span class="text-yellow-500 font-bold italic text-xl leading-none">Village</span>
                                <span class="font-bold text-xl leading-none text-white">CHEF</span>
                            </div>
                        </div>
    
                    </div>
                <!-- </div> -->



                <!-- Navigation Menu -->
                <nav class="flex-1 px-4 space-y-2 justify-between flex flex-col">
                    <!-- Dashboard Link -->
                    <div class="flex-1 space-y-2">
                        <!-- Dashboard Link -->
                        <a href="dashboard.php"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl <?php echo ($current_page === 'dashboard.php') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> transition-all duration-200">
                            <i
                                class="fas fa-home text-lg <?php echo ($current_page === 'dashboard.php') ? 'text-accent' : 'text-gray-400'; ?>"></i>
                            <span class="nav-text ml-3">Dashboard</span>
                            <?php if ($current_page === 'dashboard.php'): ?>
                                <div class="nav-text ml-auto">
                                    <i class="fas fa-chevron-right  nav-text ml-auto text-accent text-xs"></i>
                                </div>
                            <?php else: ?>
                                <div class="nav-text ml-auto">
                                    <i
                                        class="fas fa-chevron-right nav-text ml-auto text-gray-400 text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                </div>
                            <?php endif; ?>
                        </a>

                        <!-- Users Link -->
                        <a href="users.php"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl <?php echo ($current_page === 'users.php') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> transition-all duration-200">
                            <i
                                class="fas fa-users text-lg <?php echo ($current_page === 'users.php') ? 'text-accent' : 'text-gray-400'; ?>"></i>
                            <span class="nav-text ml-3">Users</span>
                            <?php if ($current_page === 'users.php'): ?>
                                <div class="nav-text ml-auto">
                                    <i class="fas fa-chevron-right nav-text ml-auto text-accent text-xs"></i>
                                </div>
                            <?php else: ?>
                                <div class="nav-text ml-auto">
                                    <span class="nav-text ml-auto bg-accent/20 text-accent px-2 py-1 text-xs rounded-md">24
                                        New</span>
                                </div>
                            <?php endif; ?>
                        </a>

                        <!-- Restaurants Link -->
                        <a href="restaurants.php"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl <?php echo ($current_page === 'restaurants.php') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> transition-all duration-200">
                            <i
                                class="fas fa-store text-lg <?php echo ($current_page === 'restaurants.php') ? 'text-accent' : 'text-gray-400'; ?>"></i>
                            <span class="nav-text ml-3">Restaurants</span>
                            <?php if ($current_page === 'restaurants.php'): ?>
                                <div class="nav-text ml-auto">
                                    <i class="fas fa-chevron-right nav-text ml-auto text-accent text-xs"></i>
                                </div>
                            <?php else: ?>
                                <div class="nav-text ml-auto">
                                    <i
                                        class="fas fa-chevron-right nav-text ml-auto text-gray-400 text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                </div>
                            <?php endif; ?>
                        </a>

                        <!-- Menu Items Link -->
                        <a href="menuItems.php"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl <?php echo ($current_page === 'menuItems.php') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> transition-all duration-200">
                            <i
                                class="fas fa-utensils text-lg <?php echo ($current_page === 'menuItems.php') ? 'text-accent' : 'text-gray-400' ?>"></i>
                            <span class="nav-text ml-3">Menu Items</span>
                            <?php if ($current_page === 'menuItems.php'): ?>
                                <div class="nav-text ml-auto">
                                    <i class="fas fa-chevron-right nav-text ml-auto text-accent text-xs"></i>
                                </div>
                            <?php else: ?>
                                <div class="nav-text ml-auto">
                                    <i
                                        class="fas fa-chevron-right nav-text ml-auto text-gray-400 text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                </div>
                            <?php endif; ?>
                        </a>

                        <!-- Orders Link -->
                        <a href="orders.php"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl <?php echo ($current_page === 'orders.php') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> transition-all duration-200">
                            <i
                                class="fas fa-shopping-cart text-lg <?php echo ($current_page === 'orders.php') ? 'text-accent' : 'text-gray-400' ?>"></i>
                            <span class="nav-text ml-3">Orders</span>
                            <?php if ($current_page === 'orders.php'): ?>
                                <div class="nav-text ml-auto">
                                    <i class="fas fa-chevron-right nav-text ml-auto text-accent text-xs"></i>
                                </div>
                            <?php else: ?>
                                <div class="nav-text ml-auto">
                                    <i
                                        class="fas fa-chevron-right nav-text ml-auto text-gray-400 text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                </div>
                            <?php endif; ?>
                        </a>

                        <!-- Payments Link -->
                        <a href="payments.php"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl <?php echo ($current_page === 'payments.php') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> transition-all duration-200">
                            <i
                                class="fas fa-credit-card text-lg <?php echo ($current_page === 'payments.php') ? 'text-accent' : 'text-gray-400' ?>"></i>
                            <span class="nav-text ml-3">Payments</span>
                            <?php if ($current_page === 'payments.php'): ?>
                                <div class="nav-text ml-auto">
                                    <i class="fas fa-chevron-right nav-text ml-auto text-accent text-xs"></i>
                                </div>
                            <?php else: ?>
                                <div class="nav-text ml-auto">
                                    <i
                                        class="fas fa-chevron-right nav-text ml-auto text-gray-400 text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                </div>
                            <?php endif; ?>
                        </a>

                        <!-- Reviews Link -->
                        <a href="reviews.php"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl <?php echo ($current_page === 'reviews.php') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> transition-all duration-200">
                            <i
                                class="fas fa-star text-lg <?php echo ($current_page === 'reviews.php') ? 'text-accent' : 'text-gray-400' ?>"></i>
                            <span class="nav-text ml-3">Reviews</span>
                            <?php if ($current_page === 'reviews.php'): ?>
                                <div class="nav-text ml-auto">
                                    <i class="fas fa-chevron-right nav-text ml-auto text-accent text-xs"></i>
                                </div>
                            <?php else: ?>
                                <div class="nav-text ml-auto">
                                    <i
                                        class="fas fa-chevron-right nav-text ml-auto text-gray-400 text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                </div>
                            <?php endif; ?>
                        </a>
                    </div>

                    <!-- Profile & Logout Section -->
                    <div class="mt-auto pt-6 border-t border-gray-800">
                        <!-- Admin Profile -->
                        <div class="flex items-center mb-6">
                            <img class="h-10 w-10 rounded-full border-2 border-accent/30"
                                src="<?php echo isset($admin['profile_pic']) ? $admin['profile_pic'] : 'https://via.placeholder.com/40'; ?>"
                                alt="Admin profile">
                            <div id="profileInfo" class="ml-3">
                                <p class="text-sm font-medium text-white">
                                    <?php echo isset($admin['first_name']) ? htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']) : 'Admin User'; ?>
                                </p>
                                <p class="text-xs text-gray-400">Administrator</p>
                            </div>
                        </div>

                        <!-- Logout Button -->
                        <a onclick="openLoginSideModal()"
                            class="flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-300 hover:bg-red-600/20 hover:text-red-400 transition-all cursor-pointer duration-200">
                            <i class="fas fa-sign-out-alt text-gray-400 group-hover:text-red-400"></i>
                            <span class="nav-text ml-3">Logout</span>
                        </a>
                    </div>
                </nav>
            </div>
        </div>


    </div>

    <!-- Logout Modal -->
    <div id="logOut" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50">
        <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-accent">Logout</h1>
                <button onclick="closeLoginSideModal()" class="text-gray-400 hover:text-accent transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form method="POST" action="logout.php" class="space-y-6">
                <p class="text-gray-300">Are you sure you want to Logout?</p>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeLoginSideModal()"
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

    <script>
        // Initialize sidebar based on screen size
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');

            // Show sidebar on desktop by default
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('hidden');
            }

            // Add event listener for window resize
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('hidden');
                } else {
                    sidebar.classList.add('hidden');
                }
            });
        });
    </script>
</body>

</html>