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


$menuFilters = ['restaurant' => $restaurantId];
$menuItems = $obj->getFilteredMenuItems($menuFilters);

$menuHighlights = array_slice($menuItems, 0, 6);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($restaurant['name']); ?> | Food Ordering System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: black;
            color: #f3f4f6;
            font-family: 'Poppins', sans-serif;
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

        .hover\:bg-accent\/90:hover {
            background-color: rgba(234, 179, 8, 0.9);
        }

        .menu-card {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
        }

        .menu-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.8) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .menu-card:hover::before {
            opacity: 1;
        }

        .restaurant-header {
            position: relative;
            overflow: hidden;
            border-radius: 1rem;
        }

        .restaurant-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.8) 100%);
            z-index: 1;
        }

        .restaurant-info {
            position: relative;
            z-index: 2;
        }

        .review-card {
            transition: transform 0.3s ease;
        }

        .review-card:hover {
            transform: translateY(-3px);
        }

        .glass-effect {
            background: rgba(31, 41, 55, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #1f2937;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #4b5563;
            border-radius: 20px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #6b7280;
        }

        .badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }

        .menu-image-container {
            height: 220px;
            overflow: hidden;
        }

        .menu-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .menu-card:hover .menu-image {
            transform: scale(1.05);
        }

        .rating-stars {
            display: inline-flex;
            align-items: center;
        }

        .rating-stars svg {
            filter: drop-shadow(0px 0px 1px rgba(234, 179, 8, 0.5));
        }
    </style>
</head>

<body class="black text-gray-100">
    <div class="flex h-screen overflow-hidden">
        <?php include 'sidebar.php'; ?>

        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <?php include 'header.php'; ?>

            <main class="flex-1 relative overflow-y-auto focus:outline-none custom-scrollbar">
                <!-- Restaurant Hero Section -->
                <div class="restaurant-header h-96 relative mb-8">
                    <img src="../AdminPanel/<?php echo $restaurant['restaurant_pic'] ?? 'assets/default-restaurant.png'; ?>"
                        alt="<?php echo htmlspecialchars($restaurant['name']); ?>"
                        class="w-full h-full object-cover">
                    
                    <div class="restaurant-info absolute bottom-0 left-0 w-full p-8">
                        <div class="container mx-auto">
                            <div class="flex flex-col md:flex-row justify-between items-end">
                                <div>
                                    <h1 class="text-5xl font-bold text-white mb-2 drop-shadow-lg"><?php echo htmlspecialchars($restaurant['name']); ?></h1>
                                    <div class="flex items-center mb-4">
                                        <div class="rating-stars mr-2">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <?php if ($i <= $avgRating): ?>
                                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.799-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                <?php else: ?>
                                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.799-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="text-white font-medium"><?php echo $avgRating; ?></span>
                                        <span class="text-gray-300 ml-1">(<?php echo $reviewCount; ?> reviews)</span>
                                    </div>
                                </div>
                                <div class="glass-effect px-4 py-2 rounded-lg flex items-center">
                                    <span class="<?php echo $restaurant['status'] == 'open' ? 'text-green-400' : 'text-red-400'; ?> font-semibold mr-2">
                                        <?php echo ucfirst($restaurant['status']); ?>
                                    </span>
                                    <div class="h-2 w-2 rounded-full <?php echo $restaurant['status'] == 'open' ? 'bg-green-400' : 'bg-red-400'; ?> animate-pulse"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container mx-auto px-4 pb-16">
                    <!-- Quick Info Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                        <div class="glass-effect rounded-xl p-6 flex items-start">
                            <div class="bg-accent/20 p-3 rounded-full mr-4">
                                <i class="fas fa-map-marker-alt text-accent text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white mb-1">Location</h3>
                                <p class="text-gray-300">
                                    <?php echo htmlspecialchars($restaurant['address']); ?>, 
                                    <?php echo htmlspecialchars($restaurant['city']); ?>,
                                    <?php echo htmlspecialchars($restaurant['state']); ?>, 
                                    <?php echo htmlspecialchars($restaurant['zip_code']); ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="glass-effect rounded-xl p-6 flex items-start">
                            <div class="bg-accent/20 p-3 rounded-full mr-4">
                                <i class="fas fa-phone text-accent text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white mb-1">Contact</h3>
                                <a href="tel:<?php echo htmlspecialchars($restaurant['phone']); ?>"
                                    class="text-gray-300 hover:text-accent transition-colors">
                                    <?php echo htmlspecialchars($restaurant['phone']); ?>
                                </a>
                            </div>
                        </div>
                        
                        <div class="glass-effect rounded-xl p-6 flex items-start">
                            <div class="bg-accent/20 p-3 rounded-full mr-4">
                                <i class="fas fa-utensils text-accent text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white mb-1">Cuisine</h3>
                                <p class="text-gray-300">
                                    <?php 
                                    echo !empty($restaurant['cuisine_types']) ? htmlspecialchars($restaurant['cuisine_types']) : 'Various Cuisines'; 
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Highlights -->
                    <div class="mb-16">
                        <div class="flex justify-between items-center mb-8">
                            <h2 class="text-3xl font-bold text-white">
                                <span class="border-b-4 border-accent pb-2">Menu Highlights</span>
                            </h2>
                            <?php if (count($menuItems) > 6): ?>
                                <a href="#" class="text-accent hover:text-accent/80 transition-colors font-medium flex items-center">
                                    View Full Menu
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (empty($menuHighlights)): ?>
                            <div class="glass-effect rounded-xl p-12 text-center">
                                <i class="fas fa-utensils text-gray-400 text-4xl mb-4"></i>
                                <h3 class="text-xl font-bold mb-2">No menu items available</h3>
                                <p class="text-gray-400 max-w-md mx-auto">This restaurant hasn't added any menu items yet. Check back soon for delicious offerings!</p>
                            </div>
                        <?php else: ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                <?php foreach ($menuHighlights as $item): ?>
                                    <div class="menu-card glass-effect rounded-xl overflow-hidden">
                                        <div class="menu-image-container">
                                            <img src="../AdminPanel/<?php echo htmlspecialchars($item['image_url'] ?? 'assets/default-menu-item.png'); ?>"
                                                alt="<?php echo htmlspecialchars($item['item_name']); ?>"
                                                class="menu-image">
                                        </div>
                                        <div class="badge">
                                            <span class="bg-accent text-black text-sm font-bold px-3 py-1 rounded-full shadow-lg">
                                                ₹<?php echo number_format($item['price'], 2); ?>
                                            </span>
                                        </div>
                                        <div class="p-6">
                                            <div class="flex justify-between items-start mb-2">
                                                <h3 class="text-xl font-semibold text-white"><?php echo htmlspecialchars($item['item_name']); ?></h3>
                                                <span class="<?php echo $item['is_available'] ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400'; ?> text-xs px-2 py-1 rounded-full">
                                                    <?php echo $item['is_available'] ? 'Available' : 'Unavailable'; ?>
                                                </span>
                                            </div>
                                            <p class="text-gray-300 text-sm mb-4"><?php echo htmlspecialchars($item['description']); ?></p>
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs bg-accent/10 text-accent px-2 py-1 rounded-full">
                                                    <?php echo htmlspecialchars($item['cuisine_name']); ?>
                                                </span>
                                                <button class="text-accent hover:text-white transition-colors">
                                                    <i class="fas fa-heart"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Reviews Section -->
                    <div id="reviews-section" class="pt-8">
                        <div class="flex justify-between items-center mb-8">
                            <h2 class="text-3xl font-bold text-white">
                                <span class="border-b-4 border-accent pb-2">Customer Reviews</span>
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
                            <!-- Review Stats -->
                            <div class="glass-effect rounded-xl p-8 lg:col-span-1">
                                <div class="flex items-center mb-6">
                                    <div class="bg-accent/20 rounded-xl p-4 mr-4">
                                        <span class="text-4xl font-bold text-accent"><?php echo $avgRating; ?></span>
                                    </div>
                                    <div>
                                        <div class="rating-stars mb-1">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <?php if ($i <= $avgRating): ?>
                                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.799-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                <?php else: ?>
                                                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.799-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="text-gray-300"><?php echo $reviewCount; ?> reviews</span>
                                    </div>
                                </div>

                                <!-- Rating Breakdown -->
                                <?php
                                $ratingCounts = [0, 0, 0, 0, 0]; // 5, 4, 3, 2, 1 stars
                                foreach ($reviews as $review) {
                                    $ratingCounts[$review['rating'] - 1]++;
                                }
                                ?>

                                <div class="space-y-4">
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <?php
                                        $percentage = $reviewCount > 0 ? ($ratingCounts[$i - 1] / $reviewCount) * 100 : 0;
                                        ?>
                                        <div class="flex items-center">
                                            <span class="text-sm text-gray-300 w-8"><?php echo $i; ?> ★</span>
                                            <div class="w-full bg-gray-700 rounded-full h-2.5 mx-2 overflow-hidden">
                                                <div class="bg-accent h-2.5 rounded-full" style="width: <?php echo $percentage; ?>%"></div>
                                            </div>
                                            <span class="text-sm text-gray-300 w-8"><?php echo $ratingCounts[$i - 1]; ?></span>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>

                            <!-- Review List -->
                            <div class="lg:col-span-2">
                                <?php if (count($reviews) > 0): ?>
                                    <div class="space-y-6">
                                        <?php 
                                        $publishedReviews = array_filter($reviews, function($review) {
                                            return $review['status'] == 'published';
                                        });
                                        
                                        if (count($publishedReviews) > 0):
                                            foreach ($publishedReviews as $review): 
                                        ?>
                                            <div class="review-card glass-effect rounded-xl p-6">
                                                <div class="flex items-start">
                                                    <img src="../AdminPanel/<?php echo $review['profile_pic'] ?? 'assets/default-avatar.png'; ?>"
                                                        alt="User" class="w-12 h-12 rounded-full mr-4 object-cover border-2 border-accent">
                                                    <div class="flex-1">
                                                        <div class="flex justify-between items-center mb-2">
                                                            <h4 class="font-semibold text-white"><?php echo htmlspecialchars($review['first_name'] . ' ' . $review['last_name']); ?></h4>
                                                            <span class="text-xs text-gray-400">
                                                                <?php echo date('M d, Y', strtotime($review['created_at'])); ?>
                                                            </span>
                                                        </div>
                                                        <div class="rating-stars mb-3">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <?php if ($i <= $review['rating']): ?>
                                                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.799-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                    </svg>
                                                                <?php else: ?>
                                                                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.799-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                    </svg>
                                                                <?php endif; ?>
                                                            <?php endfor; ?>
                                                        </div>
                                                        <p class="text-gray-300"><?php echo htmlspecialchars($review['review_text']); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php 
                                            endforeach;
                                        else: 
                                        ?>
                                            <div class="glass-effect rounded-xl p-12 text-center">
                                                <i class="far fa-comment-dots text-gray-500 text-4xl mb-4"></i>
                                                <h3 class="text-xl font-bold mb-2">No published reviews yet</h3>
                                                <p class="text-gray-400 max-w-md mx-auto">There are no published reviews for this restaurant yet.</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="glass-effect rounded-xl p-12 text-center">
                                        <i class="far fa-comment-dots text-gray-500 text-4xl mb-4"></i>
                                        <h3 class="text-xl font-bold mb-2">No reviews yet</h3>
                                        <p class="text-gray-400 max-w-md mx-auto">Be the first to review this restaurant!</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Back Button -->
                    <div class="flex justify-center mt-12">
                        <a href="restaurants.php"
                            class="px-8 py-4 bg-accent text-black font-semibold rounded-xl hover:bg-accent/90 transition-colors duration-300 shadow-lg flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Restaurants
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
