<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}

require '../dbCon.php';
$obj = new Foodies();

$users = $obj->getAllUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management | Food Ordering System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-primary text-gray-100">
    <div class="flex h-screen overflow-hidden">
        <?php include 'sidebar.php'; ?>

        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <?php include 'header.php'; ?>

            <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
                <!-- Search and Add User -->
                <div class="flex flex-col md:flex-row justify-between mb-6 gap-4">
                    <div class="w-full md:w-1/3">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-700 rounded-xl bg-gray-800 placeholder-gray-400 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent"
                                placeholder="Search users...">
                        </div>
                    </div>
                    <button type="button"
                        class="inline-flex items-center px-4 py-2 border border-accent text-sm font-medium rounded-xl shadow-sm text-accent hover:bg-accent/10 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Add User
                    </button>
                </div>

                <!-- Users Table -->
                <div class="bg-gray-800 rounded-xl shadow-xl border border-gray-700 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-800">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">User</th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Email</th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Phone</th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Role</th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Status</th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Joined</th>
                                <th scope="col" class="px-6 py-4 text-right text-sm font-medium text-gray-300 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-gray-700/20 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full border-2 border-accent/30"
                                                src="<?php echo !empty($user['profile_pic']) ? $user['profile_pic'] : 'assets/dp.png'; ?>"
                                                alt="User avatar">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-white">
                                                <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
                                            </div>
                                            <div class="text-xs text-gray-400">@<?php echo strtolower($user['first_name']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300"><?php echo $user['email']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-300"><?php echo $user['phone']; ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-accent/20 text-accent">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <span class="w-2 h-2 rounded-full mr-2 <?php echo $user['status'] == 'active' ? 'bg-green-500' : 'bg-red-500'; ?>"></span>
                                        <span class="text-sm text-gray-300"><?php echo ucfirst($user['status']); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-3">
                                        <button class="text-accent hover:text-accent/80 transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-500 hover:text-red-400 transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-700 mt-4 rounded-xl">
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-400">
                                Showing <span class="font-medium text-white">1</span> to 
                                <span class="font-medium text-white">5</span> of 
                                <span class="font-medium text-white">24</span> results
                            </p>
                        </div>
                        <nav class="relative z-0 inline-flex rounded-xl shadow-sm -space-x-px">
                            <a href="#" class="px-3 py-2 rounded-l-xl border border-gray-700 bg-gray-800 text-gray-400 hover:bg-gray-700">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            <a href="#" aria-current="page" class="px-4 py-2 border border-accent bg-accent/20 text-accent">
                                1
                            </a>
                            <a href="#" class="px-4 py-2 border border-gray-700 text-gray-400 hover:bg-gray-700">
                                2
                            </a>
                            <a href="#" class="px-4 py-2 border border-gray-700 text-gray-400 hover:bg-gray-700">
                                3
                            </a>
                            <span class="px-4 py-2 border border-gray-700 text-gray-400">...</span>
                            <a href="#" class="px-4 py-2 border border-gray-700 text-gray-400 hover:bg-gray-700">
                                8
                            </a>
                            <a href="#" class="px-3 py-2 rounded-r-xl border border-gray-700 bg-gray-800 text-gray-400 hover:bg-gray-700">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </nav>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>