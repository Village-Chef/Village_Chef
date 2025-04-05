<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}

require '../dbCon.php';
$obj = new Foodies();

// Initialize filters from GET parameters
$filters = [
    'search' => $_GET['search'] ?? ''
];

// Fetch filtered cuisines
$result = $obj->getFilteredCuisines($filters);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuisine Management | Food Ordering System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        function openDeleteModal(cuisineId) {
            document.getElementById('delete_cuisine_id').value = cuisineId;
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
                <!-- Search and Add Cuisine -->
                <form action="cuisine.php" method="GET">
                    <div class="flex flex-col md:flex-row justify-between mb-6 gap-4 items-center">
                        <div class="flex flex-1 gap-4">
                            <div class="relative w-full md:w-1/3">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" name="search" value="<?= htmlspecialchars($filters['search']) ?>"
                                    class="block w-full pl-10 pr-3 py-2 bg-gray-800 border border-gray-700 rounded-xl placeholder-gray-400 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent"
                                    placeholder="Search cuisines...">
                            </div>
                            <button type="submit"
                                class="px-4 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">
                                Apply Filter
                            </button>
                            <a href="cuisines.php"
                                class="px-4 py-2 border border-gray-600 rounded-xl text-gray-300 hover:bg-gray-700/30 transition-colors">
                                Reset
                            </a>
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
                        </div>
                    </div>
                </form>

                <!-- Cuisines Table -->
                <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-800">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-300">Cuisine</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-300">Description</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-300">Date Added</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-300">Last Updated</th>
                                    <th class="px-6 py-4 text-right text-sm font-medium text-gray-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                <?php if (empty($result)): ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-400">
                                            No records found.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($result as $cuisine): ?>
                                        <tr class="hover:bg-gray-700/20 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-white">
                                                            <?php echo htmlspecialchars($cuisine['cuisine_name']); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-300">
                                                <?php echo htmlspecialchars($cuisine['description']); ?>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-300">
                                                <?php echo date('M d, Y', strtotime($cuisine['created_at'])); ?>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-300">
                                                <?php echo date('M d, Y', strtotime($cuisine['updated_at'])); ?>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex justify-end space-x-3">
                                                    <a href="updateCuisine.php?id=<?php echo $cuisine['cuisine_id']; ?>"
                                                        class="text-accent hover:text-accent/80">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button onclick="openDeleteModal('<?php echo $cuisine['cuisine_id']; ?>')"
                                                        class="text-red-500 hover:text-red-400 transition-colors">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Delete Modal -->
                <div id="deleteModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50">
                    <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl w-full max-w-md mx-4">
                        <div class="flex justify-between items-center mb-6">
                            <h1 class="text-2xl font-bold text-accent">Delete Cuisine</h1>
                            <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-accent transition-colors">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>

                        <form method="POST" action="deleteCuisine.php" class="space-y-6">
                            <input type="hidden" id="delete_cuisine_id" name="cuisine_id">
                            <p class="text-gray-300">Are you sure you want to delete this cuisine?</p>
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