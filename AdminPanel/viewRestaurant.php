<?php
session_start();
require '../dbCon.php';
$obj = new Foodies();

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$restaurantId = $_GET['id'] ?? null;
if (!$restaurantId) {
    header('Location: restaurants.php');
    exit();
}

$restaurant = $obj->getRestaurantById($restaurantId);
if (!$restaurant) {
    header('Location: restaurants.php');
    exit();
}

// Fetch reviews (assuming getRestaurantReviews exists; if not, we'll use getFilteredReviews)
$reviews = $obj->getFilteredReviews(['restaurant' => $restaurantId]);
$reviewCount = count($reviews);

$avgRating = 0;
if ($reviewCount > 0) {
    $totalRating = 0;
    foreach ($reviews as $review) {
        $totalRating += $review['rating'];
    }
    $avgRating = round($totalRating / $reviewCount, 1);
}

// Fetch menu items for this restaurant
$menuFilters = ['restaurant' => $restaurantId];
$menuItems = $obj->getFilteredMenuItems($menuFilters);
// Limit to 3 items for highlights
$menuHighlights = array_slice($menuItems, 0, 3);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Details | Food Ordering System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #1f2937;
            color: #d1d5db;
            font-family: 'Arial', sans-serif;
        }

        .text-accent {
            color: #eab308;
        }

        .bg-accent {
            background-color: #eab308;
        }

        .border-accent {
            border-color: #eab308;
        }

        .hover\:bg-accent\/10:hover {
            background-color: rgba(234, 179, 8, 0.1);
        }

        .menu-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>

<body class="bg-primary text-gray-100">
    <div class="flex h-screen overflow-hidden">
        <?php include 'sidebar.php'; ?>

        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <?php include 'header.php'; ?>

            <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
                <div class="container mx-auto">
                    <!-- Restaurant Image and Name -->
                    <div class="mb-8">
                        <img src="../AdminPanel/<?php echo $restaurant['restaurant_pic'] ?? 'assets/default-restaurant.png'; ?>"
                            alt="<?php echo htmlspecialchars($restaurant['name']); ?>"
                            class="w-full h-64 object-cover rounded-xl shadow-xl border border-gray-700">
                        <h1 class="text-4xl font-bold text-accent mt-4"><?php echo htmlspecialchars($restaurant['name']); ?></h1>
                    </div>

                    <!-- Details -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-accent mr-3 text-xl"></i>
                            <p class="text-gray-300">
                                <?php echo htmlspecialchars($restaurant['address']); ?>, 
                                <?php echo htmlspecialchars($restaurant['city']); ?>,
                                <?php echo htmlspecialchars($restaurant['state']); ?>, 
                                <?php echo htmlspecialchars($restaurant['zip_code']); ?>
                            </p>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone text-accent mr-3 text-xl"></i>
                            <a href="tel:<?php echo htmlspecialchars($restaurant['phone']); ?>"
                                class="text-gray-300 hover:text-accent transition-colors">
                                <?php echo htmlspecialchars($restaurant['phone']); ?>
                            </a>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock text-accent mr-3 text-xl"></i>
                            <p class="text-gray-300"><?php echo ucfirst($restaurant['status']); ?></p>
                        </div>
                    </div>

                    <hr class="my-8 border-gray-700">

                    <!-- Menu Highlights -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-accent mb-6">Menu Highlights</h2>
                        <?php if (empty($menuHighlights)): ?>
                            <div class="bg-gray-800 rounded-xl p-6 text-center border border-gray-700">
                                <i class="fas fa-utensils text-gray-400 text-3xl mb-2"></i>
                                <p class="text-gray-400">No menu items available for this restaurant yet.</p>
                            </div>
                        <?php else: ?>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <?php foreach ($menuHighlights as $item): ?>
                                    <div class="menu-card bg-gray-800 rounded-xl overflow-hidden border border-gray-700 shadow-lg">
                                        <div class="relative">
                                            <img src="../AdminPanel/<?php echo htmlspecialchars($item['image_url'] ?? 'assets/default-menu-item.png'); ?>"
                                                alt="<?php echo htmlspecialchars($item['item_name']); ?>"
                                                class="w-full h-48 object-cover">
                                            <span class="absolute top-2 right-2 bg-accent text-black text-xs font-semibold px-2 py-1 rounded-full">
                                                ₹<?php echo number_format($item['price'], 2); ?>
                                            </span>
                                        </div>
                                        <div class="p-4">
                                            <h3 class="text-lg font-semibold text-white"><?php echo htmlspecialchars($item['item_name']); ?></h3>
                                            <p class="text-sm text-gray-400 mt-1 line-clamp-2"><?php echo htmlspecialchars($item['description']); ?></p>
                                            <div class="mt-2 flex items-center">
                                                <span class="text-xs text-gray-500 mr-2">Cuisine:</span>
                                                <span class="text-xs text-accent"><?php echo htmlspecialchars($item['cuisine_name']); ?></span>
                                            </div>
                                            <div class="mt-2">
                                                <?php if ($item['is_available']): ?>
                                                    <span class="bg-green-500/20 text-green-500 text-xs px-2 py-1 rounded-full">Available</span>
                                                <?php else: ?>
                                                    <span class="bg-red-500/20 text-red-500 text-xs px-2 py-1 rounded-full">Unavailable</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <hr class="my-8 border-gray-700">

                    <!-- Reviews Section -->
                    <div id="reviews-section" class="mt-16 pt-8 border-t border-zinc-800">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Customer Reviews</h2>
                        </div>

                        <!-- Review Stats -->
                        <div class="flex flex-col md:flex-row gap-6 mb-8">
                            <div class="bg-zinc-900 rounded-xl p-6 flex-1">
                                <div class="flex items-center mb-4">
                                    <span class="text-4xl font-bold mr-2"><?php echo $avgRating; ?></span>
                                    <div class="flex flex-col">
                                        <div class="flex">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <?php if ($i <= $avgRating): ?>
                                                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.799-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                <?php else: ?>
                                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.799-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="text-sm text-gray-400"><?php echo $reviewCount; ?> reviews</span>
                                    </div>
                                </div>

                                <!-- Rating Breakdown -->
                                <?php
                                $ratingCounts = [0, 0, 0, 0, 0]; // 5, 4, 3, 2, 1 stars
                                foreach ($reviews as $review) {
                                    $ratingCounts[$review['rating'] - 1]++;
                                }
                                ?>

                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <?php
                                    $percentage = $reviewCount > 0 ? ($ratingCounts[$i - 1] / $reviewCount) * 100 : 0;
                                    ?>
                                    <div class="flex items-center mt-2">
                                        <span class="text-sm text-gray-400 w-8"><?php echo $i; ?> ★</span>
                                        <div class="w-full bg-zinc-800 rounded-full h-2 mx-2">
                                            <div class="bg-yellow-500 h-2 rounded-full" style="width: <?php echo $percentage; ?>%"></div>
                                        </div>
                                        <span class="text-sm text-gray-400 w-8"><?php echo $ratingCounts[$i - 1]; ?></span>
                                    </div>
                                <?php endfor; ?>
                            </div>

                            <div class="bg-zinc-900 rounded-xl p-6 flex-1">
                                <h3 class="font-semibold mb-3">What customers are saying</h3>
                                <!-- Static tags (could be made dynamic with review text analysis) -->
                                <?php
                                $tags = [
                                    'Delicious food' => 85,
                                    'Great service' => 72,
                                    'Value for money' => 65,
                                    'Fast delivery' => 58,
                                    'Good portions' => 45
                                ];
                                ?>
                                <!-- Uncomment if you want tags -->
                                <!-- <div class="flex flex-wrap gap-2 mb-4">
                                    <?php foreach ($tags as $tag => $count): ?>
                                        <span class="bg-zinc-800 text-gray-300 text-xs px-3 py-1 rounded-full">
                                            <?php echo $tag; ?> (<?php echo $count; ?>)
                                        </span>
                                    <?php endforeach; ?>
                                </div> -->

                                <div class="mt-4">
                                    <h4 class="text-sm font-medium mb-2">Popular dishes mentioned</h4>
                                    <div class="flex flex-wrap gap-2">
                                        <?php
                                        // Simple example: Top 3 menu items by name (could be enhanced with order data)
                                        foreach ($menuHighlights as $item): ?>
                                            <span class="bg-yellow-500/10 text-yellow-500 text-xs px-3 py-1 rounded-full">
                                                <?php echo htmlspecialchars($item['item_name']); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Review List -->
                        <div class="space-y-6">
                            <?php if (count($reviews) > 0): ?>
                                <?php foreach ($reviews as $review): ?>
                                    <?php if ($review['status'] == 'published'): ?>
                                        <div class="bg-zinc-900 rounded-xl p-6">
                                            <div class="flex justify-between items-start">
                                                <div class="flex items-start">
                                                    <img src="../AdminPanel/<?php echo $review['profile_pic'] ?? 'assets/default-avatar.png'; ?>"
                                                        alt="User" class="w-10 h-10 rounded-full mr-3 object-cover">
                                                    <div>
                                                        <h4 class="font-medium"><?php echo htmlspecialchars($review['first_name'] . ' ' . $review['last_name']); ?></h4>
                                                        <div class="flex items-center mt-1">
                                                            <div class="flex mr-2">
                                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                    <?php if ($i <= $review['rating']): ?>
                                                                        <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3 .921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784 .57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.799-2.034c-.784-.57-.38-1.81 .588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                        </svg>
                                                                    <?php else: ?>
                                                                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3 .921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784 .57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.799-2.034c-.784-.57-.38-1.81 .588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                        </svg>
                                                                    <?php endif; ?>
                                                                <?php endfor; ?>
                                                            </div>
                                                            <span class="text-xs text-gray-400">
                                                                <?php echo date('M d, Y', strtotime($review['created_at'])); ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mt-3 text-gray-300"><?php echo htmlspecialchars($review['review_text']); ?></p>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="bg-zinc-900 rounded-xl p-8 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                    <h3 class="text-xl font-bold mb-2">No reviews yet</h3>
                                    <p class="text-gray-400 mb-6">Be the first to review this restaurant!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="flex justify-center space-x-6 mt-8">
                        <a href="restaurants.php"
                            class="px-6 py-3 bg-accent text-black font-semibold rounded-xl hover:bg-accent/80 transition-colors duration-300">
                            Back
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>