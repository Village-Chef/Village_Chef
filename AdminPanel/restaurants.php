<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Management | Food Ordering System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-primary text-gray-100">
    <div class="flex h-screen overflow-hidden">
        <?php include 'sidebar.php'; ?>

        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <?php include 'header.php'; ?>

            <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
                <!-- Search and Add Restaurant -->
                <div class="flex flex-col md:flex-row justify-between mb-6 gap-4">
                    <div class="w-full md:w-1/3">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-700 rounded-xl bg-gray-800 placeholder-gray-400 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent"
                                placeholder="Search restaurants...">
                        </div>
                    </div>
                    <button type="button"
                        class="inline-flex items-center px-4 py-2 border border-accent text-sm font-medium rounded-xl shadow-sm text-accent hover:bg-accent/10 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Add Restaurant
                    </button>
                </div>

                <!-- Restaurants Table -->
                <div class="bg-gray-800 rounded-xl shadow-xl border border-gray-700 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-800">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Restaurant</th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Owner</th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Contact</th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Status</th>
                                <th scope="col" class="px-6 py-4 text-right text-sm font-medium text-gray-300 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            <!-- Restaurant Row -->
                            <tr class="hover:bg-gray-700/20 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <img class="h-12 w-12 rounded-full border-2 border-accent/30 object-cover"
                                                src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd"
                                                alt="Burger King">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-white">Burger King</div>
                                            <div class="text-xs text-gray-400">Fast Food</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-300">Michael Johnson</div>
                                    <div class="text-xs text-gray-500">michael@example.com</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-300">(555) 987-6543</div>
                                    <div class="text-xs text-gray-500">New York, NY</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <span class="w-2 h-2 rounded-full mr-2 bg-green-500"></span>
                                        <span class="text-sm text-gray-300">Active</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end space-x-3">
                                        <button class="text-accent hover:text-accent/80 transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-500 hover:text-red-400 transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button class="text-blue-400 hover:text-blue-300 transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Add more restaurant rows with similar structure -->
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
                                <span class="font-medium text-white">12</span> results
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
                            <a href="#" class="px-3 py-2 rounded-r-xl border border-gray-700 bg-gray-800 text-gray-400 hover:bg-gray-700">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Add Restaurant Modal -->
                <div class="fixed inset-0 bg-black/50 hidden" id="add-restaurant-modal">
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="bg-gray-800 rounded-2xl border border-gray-700 w-full max-w-lg p-6">
                            <h3 class="text-xl font-bold text-white mb-6">Add New Restaurant</h3>
                            <form class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Restaurant Name</label>
                                    <input type="text" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-accent focus:ring-1 focus:ring-accent">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-2">Restaurant Type</label>
                                        <select class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-accent focus:ring-1 focus:ring-accent">
                                            <option>Fast Food</option>
                                            <option>Italian</option>
                                            <option>Mexican</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                                        <select class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-accent focus:ring-1 focus:ring-accent">
                                            <option>Active</option>
                                            <option>Inactive</option>
                                            <option>Pending</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="flex justify-end space-x-3 mt-6">
                                    <button type="button" class="px-4 py-2 text-gray-300 hover:text-white">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-accent text-black rounded-lg hover:bg-accent/90 transition-colors">
                                        Save Restaurant
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>