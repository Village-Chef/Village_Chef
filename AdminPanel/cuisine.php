<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}

require '../dbCon.php';
$obj = new Foodies();

$result = $obj->getAllCuisines();
// print_r($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Items Management | Food Ordering System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        function openDeleteModal(userId) {
            document.getElementById('delete_user_id').value = userId;
            document.getElementById('deleteModal').classList.remove('hidden');
        }
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
</head>

<body class="bg-primary text-gray-100">
    <div class="flex h-screen overflow-hidden">
        <?php include 'sidebar.php'; ?>

        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <?php include 'header.php'; ?>

            <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
                <!-- Search and Add Menu Item -->
                <div class="flex flex-col md:flex-row justify-between mb-6 gap-4">
                    <div class="w-full md:w-1/3">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text"
                                class="block w-full pl-10 pr-3 py-2 bg-gray-800 border border-gray-700 rounded-xl placeholder-gray-400 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent"
                                placeholder="Search menu items...">
                        </div>
                    </div>
                    <div>
                        <a href="menuItems.php"
                            class="inline-flex items-center px-4 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">
                            <i class="fas fa-backward mr-2"></i> Back
                        </a>
                        <a href="addCuisines.php"
                            class="inline-flex items-center px-4 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">
                            <i class="fas fa-plus mr-2"></i> Add Cuisine
                        </a>
                        <!-- <a href="addMenuItem.php"
                            class="inline-flex items-center px-4 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">
                            <i class="fas fa-plus mr-2"></i> Add Menu Item
                        </a> -->
                    </div>

                </div>

                <!-- Filter Options -->
                <!-- <div class="bg-gray-800 p-4 rounded-xl border border-gray-700 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Restaurant</label>
                            <select
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Category</label>
                            <select
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Availability</label>
                            <select
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Price Range</label>
                            <select
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                            </select>
                        </div>
                    </div>
                </div> -->

                <!-- Menu Items Table -->
                <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-800">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-300">Item</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-300">Description</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-300">Date Added</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-300">Last Updated</th>
                                    <th class="px-6 py-4 text-right text-sm font-medium text-gray-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                <!-- Menu Items -->

                                <?php

                                foreach ($result as $results) {
                                    ?>
                                    <tr class="hover:bg-gray-700/20 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-white">
                                                        <?php echo $results['cuisine_name'] ?>
                                                    </div>
                                                </div>
                                            </div>
                        </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-300"><?php echo $results['description'] ?></td>

                        <td class="px-6 py-4 text-sm text-gray-300">
                            <?php echo date('M d, Y', strtotime($results['created_at'])); ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-300">
                            <?php echo date('M d, Y', strtotime($results['updated_at'])); ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end space-x-3">
                                <a href="updateCuisine.php?id=<?php echo $results['cuisine_id'] ?>"
                                    class="text-accent hover:text-accent/80">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="openDeleteModal('<?php echo $results['cuisine_id']; ?>')"
                                    class="text-red-500 hover:text-red-400 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                        </tr>
                        <?php
                                }
                                ?>

                    </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-700">
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-400">
                                Showing <span class="font-medium text-white">1</span> to
                                <span class="font-medium text-white">5</span> of
                                <span class="font-medium text-white">42</span> results
                            </p>
                        </div>
                        <nav class="relative z-0 inline-flex rounded-xl shadow-sm -space-x-px">
                            <a href="#"
                                class="px-3 py-2 rounded-l-xl border border-gray-700 bg-gray-800 text-gray-400 hover:bg-gray-700">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            <a href="#" class="px-4 py-2 border border-accent bg-accent/20 text-accent">1</a>
                            <a href="#" class="px-4 py-2 border border-gray-700 text-gray-400 hover:bg-gray-700">2</a>
                            <a href="#" class="px-4 py-2 border border-gray-700 text-gray-400 hover:bg-gray-700">3</a>
                            <a href="#"
                                class="px-3 py-2 rounded-r-xl border border-gray-700 bg-gray-800 text-gray-400 hover:bg-gray-700">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </nav>
                    </div>
                </div>
        </div>

        <div id="deleteModal"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50">
            <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl w-full max-w-md mx-4">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-accent">Delete User</h1>
                    <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-accent transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form method="POST" action="deleteCuisine.php" class="space-y-6">
                    <input type="hidden" id="delete_user_id" name="cuisine_id">

                    <p class="text-gray-300">Are you sure you want to delete this Cuisine?</p>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeDeleteModal()"
                            class="px-6 py-2.5 border border-gray-600 rounded-xl text-gray-300 hover:bg-gray-700/30 hover:text-white transition-colors">
                            Cancel
                        </button>
                        <button type="submit" name="btnDelete"
                            class="px-6 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-500 font-medium transition-colors">
                            Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
        </main>
    </div>
    </div>
</body>

</html>