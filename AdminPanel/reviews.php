<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reviews Management | Food Ordering System</title>
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
    <?php include 'sidebar.php'; ?>
    
    <div class="flex flex-col w-0 flex-1 overflow-hidden">
        <?php include 'header.php'; ?>

        <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
            <!-- Search and Export -->
            <div class="flex flex-col md:flex-row justify-between mb-6 gap-4">
                <div class="w-full md:w-1/3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-700 rounded-xl bg-gray-800 placeholder-gray-400 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent" 
                               placeholder="Search reviews...">
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-700 rounded-xl text-gray-300 bg-gray-800 hover:bg-gray-700/30 font-medium transition-colors">
                        <i class="fas fa-file-export mr-2"></i> Export
                    </button>
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">
                        <i class="fas fa-plus mr-2"></i> Add Review
                    </button>
                </div>
            </div>

            <!-- Filter Options -->
            <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-xl mb-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="w-full md:w-1/4">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Rating</label>
                        <select class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-xl text-gray-300 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/50">
                            <option value="">All Ratings</option>
                            <option value="5">5 Stars</option>
                            <!-- Other options -->
                        </select>
                    </div>
                    <!-- Repeat similar structure for other filters -->
                    <div class="w-full md:w-1/4">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Restaurant</label>
                        <select class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-xl text-gray-300 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/50">
                            <option value="">All Restaurant</option>
                            <option value="published">Restaurant</option>
                            <!-- Other options -->
                        </select>
                    </div>
                    <div class="w-full md:w-1/4">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                        <select class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-xl text-gray-300 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/50">
                            <option value="">All Statuses</option>
                            <option value="published">Published</option>
                            <!-- Other options -->
                        </select>
                    </div>
                    <div class="w-full md:w-1/4">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Date Range</label>
                        <input type="date" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-xl text-gray-300 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/50"/>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" class="px-4 py-2 border border-gray-600 rounded-xl text-gray-300 hover:bg-gray-700/30 transition-colors">
                        Reset
                    </button>
                    <button type="button" class="px-4 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">
                        Apply Filters
                    </button>
                </div>
            </div>

            <!-- Reviews Table -->
            <div class="bg-gray-800 rounded-xl shadow-xl border border-gray-700 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-800">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Customer</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Restaurant</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Rating</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Review</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Date</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Status</th>
                            <th scope="col" class="px-6 py-4 text-right text-sm font-medium text-gray-300 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                        <!-- Review Row -->
                        <tr class="hover:bg-gray-700/20 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full border-2 border-accent/30" 
                                             src="https://images.unsplash.com/photo-1494790108377-be9c29b29330" 
                                             alt="User avatar">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-white">Jane Cooper</div>
                                        <div class="text-xs text-gray-400">jane.cooper@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-300">Burger King</td>
                            <td class="px-6 py-4">
                                <div class="text-accent">
                                    <!-- Stars -->
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-300 max-w-xs truncate">
                                The Whopper was amazing! Fast delivery and the food was still hot...
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-300">Mar 20, 2023</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-500/20 text-green-500">
                                    Published
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-3">
                                    <button class="text-accent hover:text-accent/80">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-accent hover:text-accent/80">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-500 hover:text-red-400">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- Repeat other rows with similar structure -->
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
                            <span class="font-medium text-white">28</span> results
                        </p>
                    </div>
                    <nav class="relative z-0 inline-flex rounded-xl shadow-sm -space-x-px">
                        <a href="#" class="px-3 py-2 rounded-l-xl border border-gray-700 bg-gray-800 text-gray-400 hover:bg-gray-700">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <a href="#" aria-current="page" 
                           class="px-4 py-2 border border-accent bg-accent/20 text-accent">1</a>
                        <!-- Other pagination items -->
                        <a href="#" class="px-3 py-2 rounded-r-xl border border-gray-700 bg-gray-800 text-gray-400 hover:bg-gray-700">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Review Details Modal -->
            <div id="review-details-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50">
                <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl w-full max-w-2xl">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-accent">Review Details</h1>
                        <button class="text-gray-400 hover:text-accent">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <!-- Modal content remains similar but with dark theme classes -->
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>