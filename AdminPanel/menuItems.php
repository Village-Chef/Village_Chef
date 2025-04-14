<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}

$msg = '';

if (isset($_SESSION['success'])) {
    $msg = $_SESSION['success'];
    $icon = 'success';
    unset($_SESSION['success']);
} elseif (isset($_SESSION['error'])) {
    $msg = $_SESSION['error'];
    $icon = 'error';
    unset($_SESSION['error']);
} else {
    $msg = '';
    $icon = '';
}

require '../dbCon.php';
$obj = new Foodies();

$filters = [
    'search' => $_GET['search'] ?? '',
    'restaurant' => $_GET['restaurant'] ?? '',
    'cuisine' => $_GET['cuisine'] ?? '',
    'availability' => $_GET['availability'] ?? '',
    'price_range' => $_GET['price_range'] ?? ''
];

$restaurants = $obj->getAllRestaurants();
$cuisines = $obj->getAllCuisines();

$result = $obj->getFilteredMenuItems($filters);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Items Management | Food Ordering System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                <form action="menuItems.php" method="GET">
                    <div class="flex flex-col md:flex-row justify-between mb-6 gap-4">
                        <div class="w-full md:w-1/3">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" name="search" value="<?= htmlspecialchars($filters['search']) ?>"
                                    class="block w-full pl-10 pr-3 py-2 bg-gray-800 border border-gray-700 rounded-xl placeholder-gray-400 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent"
                                    placeholder="Search menu items...">
                            </div>
                        </div>
                        <div>
                            <a href="cuisine.php"
                                class="inline-flex items-center px-4 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">
                                <i class="fas fa-list mr-2"></i> Cuisines
                            </a>
                            <a href="addMenuItem.php"
                                class="inline-flex items-center px-4 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">
                                <i class="fas fa-plus mr-2"></i> Add Menu Item
                            </a>
                        </div>
                    </div>
                    <?php if (!empty($msg)): ?>
                        <script>
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: '<?php echo $icon; ?>',
                                title: '<?php echo $msg; ?>',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        </script>
                    <?php endif; ?>
                    <!-- Filter Options -->
                    <div class="bg-gray-800 p-4 rounded-xl border border-gray-700 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Restaurant</label>
                                <select name="restaurant"
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                                    <option value="">All Restaurants</option>
                                    <?php foreach ($restaurants as $restaurant): ?>
                                        <option value="<?= $restaurant['restaurant_id'] ?>"
                                            <?= $filters['restaurant'] == $restaurant['restaurant_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($restaurant['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Category</label>
                                <select name="cuisine"
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                                    <option value="">All Categories</option>
                                    <?php foreach ($cuisines as $cuisine): ?>
                                        <option value="<?= $cuisine['cuisine_id'] ?>"
                                            <?= $filters['cuisine'] == $cuisine['cuisine_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cuisine['cuisine_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Availability</label>
                                <select name="availability"
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                                    <option value="">All</option>
                                    <option value="1" <?= $filters['availability'] === '1' ? 'selected' : '' ?>>Available
                                    </option>
                                    <option value="0" <?= $filters['availability'] === '0' ? 'selected' : '' ?>>Not
                                        Available</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Price Range</label>
                                <select name="price_range"
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                                    <option value="">All Prices</option>
                                    <option value="0-500" <?= $filters['price_range'] === '0-500' ? 'selected' : '' ?>>₹0 -
                                        ₹500</option>
                                    <option value="501-1000" <?= $filters['price_range'] === '501-1000' ? 'selected' : '' ?>>₹501 - ₹1000</option>
                                    <option value="1001-2000" <?= $filters['price_range'] === '1001-2000' ? 'selected' : '' ?>>₹1001 - ₹2000</option>
                                    <option value="2001+" <?= $filters['price_range'] === '2001+' ? 'selected' : '' ?>>
                                        ₹2001+</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end gap-3">
                            <a href="menuItems.php"
                                class="px-4 py-2 border border-gray-600 rounded-xl text-gray-300 hover:bg-gray-700/30 transition-colors">
                                Reset
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Menu Items Table -->
                <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-800">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-300">Item</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-300">Restaurant</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-300">Price</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-300">Tags</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-300">Availability</th>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-300">Date Added</th>
                                    <th class="px-6 py-4 text-right text-sm font-medium text-gray-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                <?php if (empty($result)): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-400">
                                            No records found.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($result as $results): ?>
                                        <tr class="hover:bg-gray-700/20 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <img class="h-10 w-10 rounded-lg object-cover border border-accent/30"
                                                        src="<?= htmlspecialchars($results['image_url']) ?>" alt="Menu Item">
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-white">
                                                            <?= htmlspecialchars($results['item_name']) ?>
                                                        </div>
                                                        <div class="text-xs text-gray-400">
                                                            <?= htmlspecialchars($results['cuisine_name']) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-300">
                                                <?= htmlspecialchars($results['restaurant_name']) ?>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-accent font-medium">₹
                                                <?= number_format($results['price'], 2) ?>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-300">
                                                <?php
                                                $tags = json_decode($results['tags'], true);
                                                if (is_array($tags)) {
                                                    foreach ($tags as $tag) {
                                                        echo '<span class="px-2 py-1 rounded-full text-xs bg-accent/30 text-accent">' . htmlspecialchars($tag) . '</span> ';
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="px-2.5 py-1 rounded-full text-xs <?= $results['is_available'] ? 'bg-green-900/30 text-green-400' : 'bg-red-900/30 text-red-400' ?>">
                                                    <?= $results['is_available'] ? 'Available' : 'Not Available' ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-300">
                                                <?= date('M d, Y', strtotime($results['created_at'])) ?>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex justify-end space-x-3">
                                                    <a href="viewMenuItems.php?id=<?= $results['item_id'] ?>"
                                                        class="text-blue-500 hover:text-accent/80">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="updateMenuItem.php?id=<?= $results['item_id'] ?>"
                                                        class="text-accent hover:text-accent/80">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button onclick="openDeleteModal('<?= $results['item_id'] ?>')"
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
                <div id="deleteModal"
                    class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50">
                    <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl w-full max-w-md mx-4">
                        <div class="flex justify-between items-center mb-6">
                            <h1 class="text-2xl font-bold text-accent">Delete Menu Item</h1>
                            <button onclick="closeDeleteModal()"
                                class="text-gray-400 hover:text-accent transition-colors">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <form method="POST" action="deleteMenuItem.php" class="space-y-6">
                            <input type="hidden" id="delete_user_id" name="item_id">
                            <p class="text-gray-300">Are you sure you want to delete this Menu Item?</p>
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