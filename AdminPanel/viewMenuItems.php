<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}

// Check if menu item ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Menu item ID is required";
    header('location:menuItems.php');
    exit();
}

$item_id = $_GET['id'];

require '../dbCon.php';
$obj = new Foodies();

// Get menu item details
$menuItem = $obj->getMenuItemByIdForDisplay($item_id);

if (!$menuItem) {
    $_SESSION['error'] = "Menu item not found";
    header('location:menuItems.php');
    exit();
}

// Get related menu items from the same restaurant
$relatedItems = $obj->getRelatedMenuItems($menuItem['restaurant_id'], $item_id, 4);

// Get reviews for this menu item
$reviews = $obj->getMenuItemReviews($item_id);

// Calculate average rating
$avgRating = 0;
$totalReviews = count($reviews);
if ($totalReviews > 0) {
    $ratingSum = array_sum(array_column($reviews, 'rating'));
    $avgRating = $ratingSum / $totalReviews;
}

// Format tags
$tags = json_decode($menuItem['tags'], true) ?: [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($menuItem['item_name']) ?> | Food Ordering System</title>
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
                <!-- Breadcrumb Navigation -->
                <nav class="mb-6 flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="dashboard.php" class="text-gray-400 hover:text-accent">
                                <i class="fas fa-home mr-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-500 mx-2 text-xs"></i>
                                <a href="menuItems.php" class="text-gray-400 hover:text-accent">
                                    Menu Items
                                </a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-500 mx-2 text-xs"></i>
                                <span class="text-accent font-medium truncate max-w-xs">
                                    <?= htmlspecialchars($menuItem['item_name']) ?>
                                </span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center mb-6">
                    <a href="menuItems.php" class="inline-flex items-center px-4 py-2 bg-gray-800 text-gray-300 rounded-xl hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Menu Items
                    </a>
                    <div class="flex space-x-3">
                        <a href="updateMenuItem.php?id=<?= $menuItem['item_id'] ?>" class="inline-flex items-center px-4 py-2 bg-accent/20 text-accent rounded-xl hover:bg-accent/30 transition-colors">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Item
                        </a>
                        <button onclick="confirmDelete(<?= $menuItem['item_id'] ?>)" class="inline-flex items-center px-4 py-2 bg-red-500/20 text-red-500 rounded-xl hover:bg-red-500/30 transition-colors">
                            <i class="fas fa-trash mr-2"></i>
                            Delete
                        </button>
                    </div>
                </div>

                <!-- Menu Item Details -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column - Image and Basic Info -->
                    <div class="lg:col-span-1">
                        <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-xl overflow-hidden">
                            <div class="relative">
                                <img src="<?= htmlspecialchars($menuItem['image_url']) ?>" alt="<?= htmlspecialchars($menuItem['item_name']) ?>" class="w-full h-64 object-cover">
                                <div class="absolute top-4 right-4">
                                    <span class="px-3 py-1.5 rounded-full text-sm font-medium <?= $menuItem['is_available'] ? 'bg-green-900/70 text-green-400' : 'bg-red-900/70 text-red-400' ?>">
                                        <?= $menuItem['is_available'] ? 'Available' : 'Not Available' ?>
                                    </span>
                                </div>
                                <div class="absolute bottom-4 left-4 bg-black/70 backdrop-blur-sm px-3 py-1.5 rounded-xl">
                                    <div class="flex items-center">
                                        <i class="fas fa-store text-accent mr-2"></i>
                                        <span class="text-white font-medium"><?= htmlspecialchars($menuItem['restaurant_name']) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="flex justify-between items-start">
                                    <h1 class="text-2xl font-bold text-white"><?= htmlspecialchars($menuItem['item_name']) ?></h1>
                                    <div class="text-xl font-bold text-accent">₹<?= number_format($menuItem['price'], 2) ?></div>
                                </div>
                                <div class="mt-4 flex items-center">
                                    <div class="flex items-center mr-3">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= round($avgRating)): ?>
                                                <i class="fas fa-star text-accent"></i>
                                            <?php elseif ($i - 0.5 <= $avgRating): ?>
                                                <i class="fas fa-star-half-alt text-accent"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-accent"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="text-gray-400"><?= number_format($avgRating, 1) ?> (<?= $totalReviews ?> reviews)</span>
                                </div>
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <?php foreach ($tags as $tag): ?>
                                        <span class="px-3 py-1 rounded-full text-xs bg-accent/20 text-accent">
                                            <?= htmlspecialchars($tag) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                                <div class="mt-6 flex items-center">
                                    <div class="bg-gray-700/50 p-2 rounded-lg mr-3">
                                        <i class="fas fa-utensils text-accent"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-400">Category</div>
                                        <div class="font-medium"><?= htmlspecialchars($menuItem['cuisine_name']) ?></div>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center">
                                    <div class="bg-gray-700/50 p-2 rounded-lg mr-3">
                                        <i class="fas fa-calendar-alt text-accent"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-400">Added On</div>
                                        <div class="font-medium"><?= date('F j, Y', strtotime($menuItem['created_at'])) ?></div>
                                    </div>
                                </div>
                                <?php if (!empty($menuItem['updated_at'])): ?>
                                <div class="mt-4 flex items-center">
                                    <div class="bg-gray-700/50 p-2 rounded-lg mr-3">
                                        <i class="fas fa-clock text-accent"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-400">Last Updated</div>
                                        <div class="font-medium"><?= date('F j, Y', strtotime($menuItem['updated_at'])) ?></div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column - Description -->
                    <div class="lg:col-span-2 space-y-8">
                        <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-xl p-6">
                            <h2 class="text-xl font-bold mb-4 flex items-center">
                                <i class="fas fa-info-circle text-accent mr-2"></i>
                                Description
                            </h2>
                            <p class="text-gray-300 leading-relaxed">
                                <?= nl2br(htmlspecialchars($menuItem['description'])) ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Related Menu Items -->
                <?php if (!empty($relatedItems)): ?>
                <div class="mt-8">
                    <h2 class="text-xl font-bold mb-6">More Items from <?= htmlspecialchars($menuItem['restaurant_name']) ?></h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <?php foreach ($relatedItems as $item): ?>
                            <a href="viewMenuItem.php?id=<?= $item['item_id'] ?>" class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden hover:border-accent transition-colors">
                                <div class="relative">
                                    <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['item_name']) ?>" class="w-full h-40 object-cover">
                                    <?php if (!$item['is_available']): ?>
                                        <div class="absolute top-2 right-2">
                                            <span class="px-2 py-1 rounded-full text-xs bg-red-900/70 text-red-400">
                                                Not Available
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-bold text-white"><?= htmlspecialchars($item['item_name']) ?></h3>
                                    <p class="text-gray-400 text-sm"><?= htmlspecialchars($item['cuisine_name']) ?></p>
                                    <div class="mt-2 flex justify-between items-center">
                                        <span class="text-accent font-bold">₹<?= number_format($item['price'], 2) ?></span>
                                        <!-- Removed avg_rating as it’s not calculated here -->
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Reviews Section -->
                <div class="mt-8">
                    <h2 class="text-xl font-bold mb-6">Customer Reviews</h2>
                    <?php if (empty($reviews)): ?>
                        <div class="bg-gray-800 rounded-2xl border border-gray-700 p-8 text-center">
                            <div class="bg-gray-700/30 p-4 rounded-full inline-block mb-4">
                                <i class="fas fa-comment-slash text-3xl text-gray-500"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-300">No Reviews Yet</h3>
                            <p class="text-gray-400 mt-2">This menu item hasn't received any reviews yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-6">
                            <?php foreach ($reviews as $review): ?>
                                <div class="bg-gray-800 rounded-2xl border border-gray-700 p-6">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <?php if (!empty($review['profile_pic'])): ?>
                                                <img src="<?= htmlspecialchars($review['profile_pic']) ?>" alt="User" class="w-12 h-12 rounded-full object-cover">
                                            <?php else: ?>
                                                <div class="w-12 h-12 rounded-full bg-accent/20 flex items-center justify-center">
                                                    <i class="fas fa-user text-accent"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="font-medium"><?= htmlspecialchars($review['first_name'] . ' ' . $review['last_name']) ?></h4>
                                                    <div class="flex items-center mt-1">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <i class="fas fa-star <?= $i <= $review['rating'] ? 'text-accent' : 'text-gray-600' ?> text-sm mr-1"></i>
                                                        <?php endfor; ?>
                                                        <span class="text-gray-400 text-sm ml-2"><?= date('M j, Y', strtotime($review['created_at'])) ?></span>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <button class="text-gray-400 hover:text-accent transition-colors">
                                                        <i class="fas fa-flag"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <p class="mt-3 text-gray-300"><?= nl2br(htmlspecialchars($review['review_text'])) ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm items-center justify-center hidden z-50">
        <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-accent">Delete Menu Item</h1>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-accent transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form method="POST" action="deleteMenuItem.php" class="space-y-6">
                <input type="hidden" id="delete_item_id" name="item_id">
                <p class="text-gray-300">Are you sure you want to delete <span id="delete_item_name" class="font-medium text-accent"></span>? This action cannot be undone.</p>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-6 py-2.5 border border-gray-600 rounded-xl text-gray-300 hover:bg-gray-700/30 hover:text-white transition-colors">
                        Cancel
                    </button>
                    <button type="submit" name="btnDelete" class="px-6 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-500 font-medium transition-colors">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function confirmDelete(itemId) {
            document.getElementById('delete_item_id').value = itemId;
            document.getElementById('delete_item_name').textContent = '<?= addslashes($menuItem['item_name']) ?>';
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }
    </script>
</body>

</html>