<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

require '../dbCon.php';
$foodies = new Foodies();

try {
    // Fetch recent activities data
    $recentUsers = $foodies->getRecentUsers(10); // Get 10 most recent users
    $recentRestaurants = $foodies->getRecentRestaurants(10); // Get 10 most recent restaurants
    $recentMenuItems = $foodies->getRecentMenuItems(10); // Get 10 most recent menu items
    $recentOrders = $foodies->getRecentOrders(10); // Get 10 most recent orders
    $recentReviews = $foodies->getRecentReviews(10); // Get 10 most recent reviews

    // Combine all activities into one array with type and timestamp
    $allActivities = [];

    // Add users
    foreach ($recentUsers as $user) {
        $allActivities[] = [
            'type' => 'user',
            'data' => $user,
            'timestamp' => strtotime($user['created_at']),
            'icon' => 'fa-user-plus',
            'color' => 'bg-blue-500',
            'title' => 'New User Registration'
        ];
    }

    // Add restaurants
    foreach ($recentRestaurants as $restaurant) {
        $allActivities[] = [
            'type' => 'restaurant',
            'data' => $restaurant,
            'timestamp' => strtotime($restaurant['created_at']),
            'icon' => 'fa-store',
            'color' => 'bg-purple-500',
            'title' => 'New Restaurant Added'
        ];
    }

    // Add menu items
    foreach ($recentMenuItems as $item) {
        $allActivities[] = [
            'type' => 'menu_item',
            'data' => $item,
            'timestamp' => strtotime($item['created_at']),
            'icon' => 'fa-utensils',
            'color' => 'bg-green-500',
            'title' => 'New Menu Item Added'
        ];
    }

    // Add orders
    foreach ($recentOrders as $order) {
        $allActivities[] = [
            'type' => 'order',
            'data' => $order,
            'timestamp' => strtotime($order['order_date']),
            'icon' => 'fa-shopping-cart',
            'color' => 'bg-accent',
            'title' => 'New Order Placed'
        ];
    }

    // Add reviews
    foreach ($recentReviews as $review) {
        $allActivities[] = [
            'type' => 'review',
            'data' => $review,
            'timestamp' => strtotime($review['created_at']),
            'icon' => 'fa-star',
            'color' => 'bg-yellow-500',
            'title' => 'New Review Posted'
        ];
    }

    // Sort all activities by timestamp (newest first)
    usort($allActivities, function ($a, $b) {
        return $b['timestamp'] - $a['timestamp'];
    });

    // Get counts for notification badges
    $todayUsers = count(array_filter($recentUsers, function ($user) {
        return date('Y-m-d', strtotime($user['created_at'])) === date('Y-m-d');
    }));

    $todayRestaurants = count(array_filter($recentRestaurants, function ($restaurant) {
        return date('Y-m-d', strtotime($restaurant['created_at'])) === date('Y-m-d');
    }));

    $todayMenuItems = count(array_filter($recentMenuItems, function ($item) {
        return date('Y-m-d', strtotime($item['created_at'])) === date('Y-m-d');
    }));

    $todayOrders = count(array_filter($recentOrders, function ($order) {
        return date('Y-m-d', strtotime($order['order_date'])) === date('Y-m-d');
    }));

} catch (Exception $e) {
    die("Error loading notification data: " . $e->getMessage());
}

// Function to format time ago
function timeAgo($timestamp)
{
    $difference = time() - $timestamp;

    if ($difference < 60) {
        return "Just now";
    } elseif ($difference < 3600) {
        $minutes = floor($difference / 60);
        return $minutes . " minute" . ($minutes > 1 ? "s" : "") . " ago";
    } elseif ($difference < 86400) {
        $hours = floor($difference / 3600);
        return $hours . " hour" . ($hours > 1 ? "s" : "") . " ago";
    } elseif ($difference < 172800) {
        return "Yesterday";
    } else {
        return date('M j, Y', $timestamp);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications | Food Ordering System</title>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterButtons = document.querySelectorAll('[data-filter]');
            const timelineItems = document.querySelectorAll('[data-activity-type]');

            filterButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const filterType = this.dataset.filter;

                    // Reset all buttons
                    filterButtons.forEach(btn => {
                        btn.classList.remove(
                            'bg-gray-700', 'text-white',
                            'bg-blue-400', 'bg-purple-400', 'bg-green-400',
                            'bg-accent', 'bg-yellow-400',
                            'text-blue-500', 'text-purple-500', 'text-green-500',
                            'text-accent', 'text-yellow-500'
                        );
                        btn.classList.add('hover:bg-opacity-20');
                    });

                    // Style clicked button
                    if (filterType === 'all') {
                        this.classList.add('bg-gray-700', 'text-white');
                    } else {
                        const colorClass = this.dataset.color.replace('500', '400');
                        this.classList.add(colorClass, 'text-white');
                    }

                    // Filter items
                    timelineItems.forEach(item => {
                        item.style.display = (filterType === 'all' ||
                            item.dataset.activityType === filterType) ? '' : 'none';
                    });
                });
            });
        });
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-primary text-gray-100">
    <div class="flex h-screen overflow-hidden">
        <?php include 'sidebar.php'; ?>

        <div id="mainContent" class="flex flex-col w-0 flex-1 overflow-hidden">
            <?php include 'header.php'; ?>

            <main class="flex-1 relative overflow-y-auto focus:outline-none p-6 space-y-8">
                <!-- Page Header -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold">Notifications</h1>
                        <p class="text-gray-400 mt-1">View all recent activities in your system</p>
                    </div>

                    <!-- <div class="flex gap-3">
                        <button
                            class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-xl transition flex items-center gap-2">
                            <i class="fas fa-check-double"></i>
                            <span>Mark All as Read</span>
                        </button>
                        <button
                            class="bg-accent hover:bg-accent/80 text-black px-4 py-2 rounded-xl transition flex items-center gap-2">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </button>
                    </div> -->
                </div>

                <!-- Activity Summary Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                    <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-400 mb-2">New Users Today</p>
                                <p class="text-3xl font-bold text-accent"><?= $todayUsers ?></p>
                            </div>
                            <div class="bg-blue-500/20 p-4 rounded-xl">
                                <i class="fas fa-user-plus text-2xl text-blue-500"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-400 mb-2">New Restaurants Today</p>
                                <p class="text-3xl font-bold text-accent"><?= $todayRestaurants ?></p>
                            </div>
                            <div class="bg-purple-500/20 p-4 rounded-xl">
                                <i class="fas fa-store text-2xl text-purple-500"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-400 mb-2">New Menu Items Today</p>
                                <p class="text-3xl font-bold text-accent"><?= $todayMenuItems ?></p>
                            </div>
                            <div class="bg-green-500/20 p-4 rounded-xl">
                                <i class="fas fa-utensils text-2xl text-green-500"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-400 mb-2">New Orders Today</p>
                                <p class="text-3xl font-bold text-accent"><?= $todayOrders ?></p>
                            </div>
                            <div class="bg-accent/20 p-4 rounded-xl">
                                <i class="fas fa-shopping-cart text-2xl text-accent"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Filters -->
                <div class="bg-gray-800 p-4 rounded-2xl border border-gray-700 shadow-xl">
                    <div class="flex flex-wrap gap-3">
                        <button data-filter="all"
                            class="bg-gray-700 text-white px-4 py-2 rounded-xl transition flex items-center gap-2">
                            <i class="fas fa-filter"></i>
                            <span>All Activities</span>
                        </button>
                        <button data-filter="user" data-color="bg-blue-500"
                            class="text-blue-500 hover:bg-blue-500/20 px-4 py-2 rounded-xl transition flex items-center gap-2">
                            <i class="fas fa-user-plus"></i>
                            <span>Users</span>
                        </button>
                        <button data-filter="restaurant" data-color="bg-purple-500"
                            class="text-purple-500 hover:bg-purple-500/20 px-4 py-2 rounded-xl transition flex items-center gap-2">
                            <i class="fas fa-store"></i>
                            <span>Restaurants</span>
                        </button>
                        <button data-filter="menu_item" data-color="bg-green-500"
                            class="text-green-500 hover:bg-green-500/20 px-4 py-2 rounded-xl transition flex items-center gap-2">
                            <i class="fas fa-utensils"></i>
                            <span>Menu Items</span>
                        </button>
                        <button data-filter="order" data-color="bg-accent"
                            class="text-accent hover:bg-accent/20 px-4 py-2 rounded-xl transition flex items-center gap-2">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Orders</span>
                        </button>
                        <button data-filter="review" data-color="bg-yellow-500"
                            class="text-yellow-500 hover:bg-yellow-500/20 px-4 py-2 rounded-xl transition flex items-center gap-2">
                            <i class="fas fa-star"></i>
                            <span>Reviews</span>
                        </button>
                    </div>
                </div>


                <!-- Timeline -->
                <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-xl">
                    <h2 class="text-xl font-bold mb-6">Recent Activities</h2>

                    <div class="relative">
                        <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gray-700"></div>

                        <div class="space-y-8">
                            <?php foreach ($allActivities as $activity): ?>
                                <div class="relative flex items-start gap-6" data-activity-type="<?= $activity['type'] ?>">
                                    <!-- Timeline dot -->
                                    <div
                                        class="absolute left-8 top-8 w-4 h-4 rounded-full bg-gray-800 border-4 border-<?= str_replace('bg-', '', $activity['color']) ?> transform -translate-x-1/2 -translate-y-1/2 z-10">
                                    </div>

                                    <!-- Icon -->
                                    <div class="<?= $activity['color'] ?>/20 p-4 rounded-xl flex-shrink-0 ml-4">
                                        <i
                                            class="fas <?= $activity['icon'] ?> text-xl <?= str_replace('bg-', 'text-', $activity['color']) ?>"></i>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 bg-gray-700/30 p-4 rounded-xl">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2">
                                            <h3 class="font-bold text-lg"><?= $activity['title'] ?></h3>
                                            <span
                                                class="text-gray-400 text-sm"><?= timeAgo($activity['timestamp']) ?></span>
                                        </div>

                                        <?php if ($activity['type'] === 'user'): ?>
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="w-12 h-12 rounded-full bg-gray-600 flex items-center justify-center overflow-hidden">
                                                    <?php if (!empty($activity['data']['profile_pic'])): ?>
                                                        <img src="<?= $activity['data']['profile_pic'] ?>" alt="User"
                                                            class="w-full h-full object-cover">
                                                    <?php else: ?>
                                                        <i class="fas fa-user text-gray-400"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <p class="font-medium"><?= $activity['data']['first_name'] ?>
                                                        <?= $activity['data']['last_name'] ?>
                                                    </p>
                                                    <p class="text-gray-400"><?= $activity['data']['email'] ?></p>
                                                </div>
                                            </div>
                                            <div class="mt-3 flex gap-2">
                                                <a href="users.php?id=<?= $activity['data']['user_id'] ?>"
                                                    class="text-accent hover:underline text-sm">View Profile</a>
                                                <span class="text-gray-500">•</span>
                                                <a href="mailto:<?= $activity['data']['email'] ?>"
                                                    class="text-accent hover:underline text-sm">Send Email</a>
                                            </div>
                                        <?php elseif ($activity['type'] === 'restaurant'): ?>
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="w-12 h-12 rounded-lg bg-gray-600 flex items-center justify-center overflow-hidden">
                                                    <?php if (!empty($activity['data']['restaurant_pic'])): ?>
                                                        <img src="<?= $activity['data']['restaurant_pic'] ?>" alt="Restaurant"
                                                            class="w-full h-full object-cover">
                                                    <?php else: ?>
                                                        <i class="fas fa-store text-gray-400"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <p class="font-medium"><?= $activity['data']['name'] ?></p>
                                                    <p class="text-gray-400"><?= $activity['data']['city'] ?>,
                                                        <?= $activity['data']['state'] ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="mt-3 flex gap-2">
                                                <a href="viewRestaurant.php?id=<?= $activity['data']['restaurant_id'] ?>"
                                                    class="text-accent hover:underline text-sm">View Restaurant</a>
                                                <span class="text-gray-500">•</span>
                                                <a href="viewmenuItems.php?restaurant=<?= $activity['data']['restaurant_id'] ?>"
                                                    class="text-accent hover:underline text-sm">View Menu</a>
                                            </div>
                                        <?php elseif ($activity['type'] === 'menu_item'): ?>
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="w-12 h-12 rounded-lg bg-gray-600 flex items-center justify-center overflow-hidden">
                                                    <?php if (!empty($activity['data']['image_url'])): ?>
                                                        <img src="<?= $activity['data']['image_url'] ?>" alt="Menu Item"
                                                            class="w-full h-full object-cover">
                                                    <?php else: ?>
                                                        <i class="fas fa-utensils text-gray-400"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <p class="font-medium"><?= $activity['data']['item_name'] ?></p>
                                                    <p class="text-gray-400">
                                                        <?= $activity['data']['restaurant_name'] ?> •
                                                        <span
                                                            class="text-accent">₹<?= number_format($activity['data']['price'], 2) ?></span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="mt-3 flex gap-2">
                                                <a href="viewmenuItems.php?id=<?= $activity['data']['item_id'] ?>"
                                                    class="text-accent hover:underline text-sm">View Item</a>
                                                <span class="text-gray-500">•</span>
                                                <a href="viewRestaurant.php?id=<?= $activity['data']['restaurant_id'] ?>"
                                                    class="text-accent hover:underline text-sm">View Restaurant</a>
                                            </div>
                                        <?php elseif ($activity['type'] === 'order'): ?>
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-lg bg-accent/20 flex items-center justify-center">
                                                    <i class="fas fa-receipt text-accent"></i>
                                                </div>
                                                <div>
                                                    <p class="font-medium">Order #<?= $activity['data']['order_id'] ?></p>
                                                    <p class="text-gray-400">
                                                        <?= $activity['data']['first_name'] ?>
                                                        <?= $activity['data']['last_name'] ?> •
                                                        <?= $activity['data']['restaurant_name'] ?> •
                                                        <span
                                                            class="text-accent">₹<?= number_format($activity['data']['total_amount'], 2) ?></span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="mt-3 flex gap-2">
                                                <a href="orders.php?id=<?= $activity['data']['order_id'] ?>"
                                                    class="text-accent hover:underline text-sm">View Order</a>
                                                <span class="text-gray-500">•</span>
                                                <a href="users.php?id=<?= $activity['data']['user_id'] ?>"
                                                    class="text-accent hover:underline text-sm">View Customer</a>
                                            </div>
                                        <?php elseif ($activity['type'] === 'review'): ?>
                                            <div class="flex items-start gap-4">
                                                <div
                                                    class="w-12 h-12 rounded-lg bg-yellow-500/20 flex items-center justify-center">
                                                    <i class="fas fa-star text-yellow-500"></i>
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2">
                                                        <p class="font-medium"><?= $activity['data']['first_name'] ?>
                                                            <?= $activity['data']['last_name'] ?>
                                                        </p>
                                                        <span class="text-gray-500">•</span>
                                                        <p class="text-gray-400"><?= $activity['data']['restaurant_name'] ?></p>
                                                        <span class="text-gray-500">•</span>
                                                        <div class="flex items-center">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <?php if ($i <= $activity['data']['rating']): ?>
                                                                    <i class="fas fa-star text-yellow-500 text-sm"></i>
                                                                <?php else: ?>
                                                                    <i class="far fa-star text-gray-500 text-sm"></i>
                                                                <?php endif; ?>
                                                            <?php endfor; ?>
                                                        </div>
                                                    </div>
                                                    <p class="mt-2 text-gray-300"><?= $activity['data']['review_text'] ?></p>
                                                </div>
                                            </div>
                                            <div class="mt-3 flex gap-2">
                                                <a href="reviews.php?id=<?= $activity['data']['review_id'] ?>"
                                                    class="text-accent hover:underline text-sm">View Review</a>
                                                <span class="text-gray-500">•</span>
                                                <a href="viewRestaurant.php?id=<?= $activity['data']['restaurant_id'] ?>"
                                                    class="text-accent hover:underline text-sm">View Restaurant</a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <?php if (empty($allActivities)): ?>
                                <div class="text-center py-12">
                                    <div class="bg-gray-700/30 p-8 rounded-xl inline-block mb-4">
                                        <i class="fas fa-bell-slash text-4xl text-gray-500"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-400">No Recent Activities</h3>
                                    <p class="text-gray-500 mt-2">There are no recent activities to display at this time.
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Load More Button -->
                <div class="flex justify-center">
                    <button
                        class="bg-gray-800 hover:bg-gray-700 text-white px-6 py-3 rounded-xl transition flex items-center gap-2">
                        <i class="fas fa-sync-alt"></i>
                        <span>Load More Activities</span>
                    </button>
                </div>
            </main>
        </div>
    </div>
</body>

</html>