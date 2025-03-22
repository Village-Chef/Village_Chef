<?php
session_start();
require '../dbCon.php';
$obj = new Foodies();

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$restaurantId = $_GET['id'];
$restaurant = $obj->getRestaurantById($restaurantId);
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
    </style>
</head>

<body class="bg-primary text-gray-100">
    <div class="flex h-screen overflow-hidden">

        <?php include 'sidebar.php'; ?>

        <div class="flex flex-col w-0 flex-1 overflow-hidden">

            <?php include 'header.php'; ?>

            <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
                <div class="container mx-auto">

                    <div class="mb-8">
                        <img src="<?php echo $restaurant['restaurant_pic']; ?>" alt="<?php echo $restaurant['name']; ?>"
                            class="w-full h-64 object-cover rounded-xl shadow-xl border border-gray-700">
                        <h1 class="text-4xl font-bold text-accent mt-4"><?php echo $restaurant['name']; ?></h1>
                    </div>

                    <!-- Details  -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-accent mr-3 text-xl"></i>
                            <p class="text-gray-300">
                                <?php echo $restaurant['address']; ?>, <?php echo $restaurant['city']; ?>,
                                <?php echo $restaurant['state']; ?>, <?php echo $restaurant['zip_code']; ?>
                            </p>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-phone text-accent mr-3 text-xl"></i>
                            <a href="tel:<?php echo $restaurant['phone']; ?>"
                                class="text-gray-300 hover:text-accent transition-colors">
                                <?php echo $restaurant['phone']; ?>
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
                        <h2 class="text-2xl font-bold text-accent mb-4">Menu Highlights</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                            <div
                                class="bg-gray-800 rounded-xl overflow-hidden hover:scale-105 transition-transform duration-300 shadow-md border border-gray-700">
                                <img src="https://via.placeholder.com/300x200" alt="Dish 1"
                                    class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-white">Spicy Pizza</h3>
                                </div>
                            </div>
                            <div
                                class="bg-gray-800 rounded-xl overflow-hidden hover:scale-105 transition-transform duration-300 shadow-md border border-gray-700">
                                <img src="https://via.placeholder.com/300x200" alt="Dish 2"
                                    class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-white">Truffle Pasta</h3>
                                </div>
                            </div>
                            <div
                                class="bg-gray-800 rounded-xl overflow-hidden hover:scale-105 transition-transform duration-300 shadow-md border border-gray-700">
                                <img src="https://via.placeholder.com/300x200" alt="Dish 3"
                                    class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-white">Golden Dessert</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-8 border-gray-700">

                    <!-- Reviews  -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-accent mb-4">Reviews</h2>
                        <div class="flex items-center mb-4">
                            <div class="flex text-accent text-lg">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <p class="ml-2 text-gray-300">4.5 (123 reviews)</p>
                        </div>
                        <div class="space-y-6">
                            <div class="bg-gray-800 p-4 rounded-xl shadow-md border border-gray-700">
                                <div class="flex items-center mb-2">
                                    <img src="https://via.placeholder.com/40" alt="User 1"
                                        class="w-10 h-10 rounded-full mr-3 border border-accent/30">
                                    <div>
                                        <p class="font-semibold text-white">John Doe</p>
                                        <div class="flex text-accent">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-gray-300">"Amazing food and a cozy atmosphere! Highly recommend the
                                    truffle pasta."</p>
                            </div>
                        </div>
                    </div>


                    <div class="flex justify-center space-x-6">
                        <a href="restaurants.php"
                            class="px-6 py-3 bg-accent text-black font-semibold rounded-xl hover:bg-accent/80 transition-colors duration-300">
                            Back
                        </a>
                        <a href="menu.php?id=<?php echo $restaurant['restaurant_id']; ?>"
                            class="px-6 py-3 bg-accent text-black font-semibold rounded-xl hover:bg-accent/80 transition-colors duration-300">
                            View Menu
                        </a>
                        <a href="reservation.php?id=<?php echo $restaurant['restaurant_id']; ?>"
                            class="px-6 py-3 bg-accent text-black font-semibold rounded-xl hover:bg-accent/80 transition-colors duration-300">
                            Make Reservation
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>