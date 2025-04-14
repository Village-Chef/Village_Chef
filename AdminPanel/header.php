<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$admin = isset($_SESSION['admin']) ? $_SESSION['admin'] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Header</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        function openLoginHeaderModal() {
            document.getElementById('logOut').classList.remove('hidden');
        }
        function closeLoginHeaderModal() {
            document.getElementById('logOut').classList.add('hidden');
        }
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
</head>

<body>
    <div class="relative z-10 flex-shrink-0 py-10 flex h-16 bg-primary shadow-xl border-b border-gray-800">

        <div class="flex-1 px-4 flex justify-between items-center ">
            <!-- Welcome message -->
            <div class="flex items-center space-x-4">
                <div class="hidden md:block">
                    <h1 class="text-3xl font-semibold text-accent ">
                        <?php if ($admin): ?>
                            Welcome back, <?php echo htmlspecialchars($admin['first_name']); ?>!
                        <?php endif; ?>
                    </h1>
                    <p class="text-sm text-gray-400">Admin</p>
                </div>
            </div>

            <!-- Right section -->
            <div class="flex items-center space-x-6">
                <!-- Notification bell -->
                <a href="notifications.php" class="text-gray-400 hover:text-accent cursor-pointer transition-colors relative">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                </a>

                <!-- Profile dropdown -->
                <div class="relative" id="profileDropdown">
                    <button onclick="toggleDropdown()" class="flex items-center space-x-2 group focus:outline-none">
                        <div class="relative">
                            <img class="h-10 w-10 rounded-full border-2 border-accent/30 group-hover:border-accent transition-colors"
                                src="<?php echo $admin['profile_pic']; ?>" alt="Profile">
                            <div
                                class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-primary">
                            </div>
                        </div>
                        <div class="text-left hidden md:block">
                            <p class="text-sm font-medium text-white">
                                <?php echo htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']); ?>
                            </p>
                            <p class="text-xs text-gray-400">Administrator</p>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 group-hover:text-accent transition-colors"></i>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="dropdownMenu"
                        class="hidden absolute right-0 mt-2 w-48 bg-gray-800 rounded-lg shadow-xl py-2 border border-gray-700">
                        <a href="profileUpdate.php"
                            class="px-4 py-2 flex items-center text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                            <i class="fas fa-user mr-3 text-accent"></i>
                            Profile
                        </a>
                        <a href="#"
                            class="px-4 py-2 flex items-center text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                            <i class="fas fa-cog mr-3 text-accent"></i>
                            Settings
                        </a>
                        <a onclick="openLoginHeaderModal()"
                            class="px-4 py-2 flex items-center text-gray-300 hover:bg-red-600 cursor-pointer hover:text-white transition-colors">
                            <i class="fas fa-sign-out-alt mr-3"></i>
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
                <button onclick="closeLoginHeaderModal()" class="text-gray-400 hover:text-accent transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form method="POST" action="logout.php" class="space-y-6">
                <p class="text-gray-300">Are you sure you want to Logout?</p>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeLoginHeaderModal()"
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
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('hidden');
        }

        document.addEventListener('click', function (event) {
            const dropdownContainer = document.getElementById('profileDropdown');
            const dropdownMenu = document.getElementById('dropdownMenu');

            if (!dropdownContainer.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });
    </script>
</body>

</html>