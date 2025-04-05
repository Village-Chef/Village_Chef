<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search - Village Chef</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/@themesberg/flowbite@latest/dist/flowbite.bundle.js"></script>
</head>

<body class="min-h-screen text-white bg-black">
    <!-- Navbar placeholder - would be replaced with your actual navbar -->
    <!-- <nav class="bg-zinc-900 border-b border-zinc-800 py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="#" class="text-2xl font-bold text-yellow-500">Village Chef</a>
            <div class="hidden md:flex space-x-6">
                <a href="#" class="text-gray-300 hover:text-white">Home</a>
                <a href="#" class="text-gray-300 hover:text-white">Menu</a>
                <a href="#" class="text-yellow-500">Search</a>
                <a href="#" class="text-gray-300 hover:text-white">About</a>
                <a href="#" class="text-gray-300 hover:text-white">Contact</a>
            </div>
            <div class="flex items-center space-x-4">
                <a href="#" class="text-gray-300 hover:text-white">
                    <i class="fas fa-shopping-cart"></i>
                </a>
                <a href="#" class="text-gray-300 hover:text-white">
                    <i class="fas fa-user"></i>
                </a>
                <button class="md:hidden text-gray-300 hover:text-white">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </nav> -->
    <?php require("navbar.php");

    if (isset($_GET['query'])) {
        $searchQuery = htmlspecialchars($_GET['query'], ENT_QUOTES, 'UTF-8'); // Sanitize the input
        // echo "<h1 class='text-2xl font-bold mb-6'>Search Results for: " . $searchQuery . "</h1>";

        try {
            // Call the search method
            $results = $obj->search($searchQuery);
        } catch (Exception $e) {
            echo "<p class='text-red-500'>Error: " . $e->getMessage() . "</p>";
        }
    } else {
        // echo "<p class='text-gray-400'>Please enter a search query.</p>";
    }
    if (isset($_SESSION['user']['user_id'])) {
        $uid = $_SESSION['user']['user_id'];
    }
    if (isset($_GET['query']) && !empty($_GET['query'])) {
        $searchQuery = htmlspecialchars($_GET['query'], ENT_QUOTES, 'UTF-8'); // Sanitize the input
        try {
            // Call the search method with the query
            $results = $obj->search($searchQuery);
        } catch (Exception $e) {
            echo "<p class='text-red-500'>Error: " . $e->getMessage() . "</p>";
        }
    } else {
            $results = $obj->getAllResults();
    }

    if (isset($_GET['addtoCart'])) {
        $allcart = $obj->getAllCarts();
        $userHasCart = false;
        foreach ($allcart as $cart) {
            if ($cart['user_id'] == $uid && $cart['status'] == 'active') {
                $userHasCart = true;
                $cart_id = $cart['cart_id'];
                break;
            }
        }
        if (!$userHasCart) {
            $cart_id = $obj->addCart($uid);
        }

        $obj->addCartItem($cart_id, $_GET['addtoCart'], 1, $_GET['price']);
        echo "<script> window.location.href='search.php?query=$query'; </script>";
    }




    // Add this at the top of your file
    if (isset($_GET['updateCart']) && isset($_GET['item_id']) && isset($_GET['quantity'])) {
        $cart_id = $_GET['updateCart'];
        $item_id = $_GET['item_id'];
        $quantity = $_GET['quantity'];

        // Don't allow quantity to go below 1
        if ($quantity <= 0) {
            // Delete the item from cart if quantity is 0 or negative
            $obj->deleteCartItem($cart_id, $item_id);
        } else {
            // Update quantity if it's 1 or more
            $obj->updateCartItemQuantity($cart_id, $item_id, $quantity);
        }
        echo "<script> window.location.href='search.php?query=$query'; </script>";
    }
    ?>
    <!-- <diV class="pt-20"></diV> -->
    <!-- Search Hero Section -->
    <div class="relative bg-zinc-900 py-32">
        <div class="absolute inset-0 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1974&q=80"
                alt="Food Background" class="w-full h-full object-cover opacity-10">
        </div>
        <div class="relative container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Find Your Perfect Meal</h1>
            <p class="text-gray-300 text-lg mb-8 max-w-2xl mx-auto">
                Search for restaurants, dishes, cuisines, or anything food-related. Your next delicious meal is just a search away.
            </p>

            <!-- Main Search Bar -->
            <div class="max-w-3xl mx-auto">
                <div class="relative">
                    <form method="GET">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="search" name="query" id="main-search"
                            class="block w-full p-4 pl-10 text-lg rounded-lg bg-zinc-800 border border-zinc-700 placeholder-gray-400 text-white focus:ring-yellow-500 focus:border-yellow-500"
                            placeholder="Search for restaurants, dishes, cuisines..." required>
                        <button type="submit"
                            class="absolute right-2.5 bottom-2.5 bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-black text-sm px-4 py-2 transition">
                            Search
                        </button>
                    </form>
                </div>

                <!-- Search Suggestions -->
                <div class="flex flex-wrap justify-center mt-4 gap-2">
                    <span class="text-sm bg-zinc-800 hover:bg-zinc-700 px-3 py-1 rounded-full cursor-pointer transition suggestion">
                        Pizza
                    </span>
                    <span class="text-sm bg-zinc-800 hover:bg-zinc-700 px-3 py-1 rounded-full cursor-pointer transition suggestion">
                        Italian
                    </span>
                    <span class="text-sm bg-zinc-800 hover:bg-zinc-700 px-3 py-1 rounded-full cursor-pointer transition suggestion">
                        Burger
                    </span>
                    <span class="text-sm bg-zinc-800 hover:bg-zinc-700 px-3 py-1 rounded-full cursor-pointer transition suggestion">
                        Chinese
                    </span>
                    <span class="text-sm bg-zinc-800 hover:bg-zinc-700 px-3 py-1 rounded-full cursor-pointer transition suggestion">
                        Vegetarian
                    </span>
                    <span class="text-sm bg-zinc-800 hover:bg-zinc-700 px-3 py-1 rounded-full cursor-pointer transition suggestion">
                        gujrati
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Filters and Results -->
    <div class="container mx-auto px-4 py-10">
        <!-- Filters and Sort -->
        <div class="flex flex-col md:flex-row justify-end mb-8 gap-4">
            <!-- Filters -->
            <!-- <div class="flex flex-wrap gap-2">
                <button id="filter-dropdown-button" data-dropdown-toggle="filter-dropdown"
                    class="flex items-center bg-zinc-800 hover:bg-zinc-700 text-white font-medium rounded-lg text-sm px-4 py-2.5 transition">
                    <i class="fas fa-filter mr-2"></i>
                    Filters
                    <i class="fas fa-chevron-down ml-2"></i>
                </button>
                <div id="filter-dropdown" class="hidden z-10 w-60 bg-zinc-800 rounded-lg shadow">
                    <div class="p-4">
                        <h6 class="text-sm font-medium mb-3">Cuisine Type</h6>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input id="italian" type="checkbox" class="w-4 h-4 bg-zinc-700 border-zinc-600 rounded focus:ring-yellow-500">
                                <label for="italian" class="ml-2 text-sm text-gray-300">Italian</label>
                            </div>
                            <div class="flex items-center">
                                <input id="chinese" type="checkbox" class="w-4 h-4 bg-zinc-700 border-zinc-600 rounded focus:ring-yellow-500">
                                <label for="chinese" class="ml-2 text-sm text-gray-300">Chinese</label>
                            </div>
                            <div class="flex items-center">
                                <input id="indian" type="checkbox" class="w-4 h-4 bg-zinc-700 border-zinc-600 rounded focus:ring-yellow-500">
                                <label for="indian" class="ml-2 text-sm text-gray-300">Indian</label>
                            </div>
                            <div class="flex items-center">
                                <input id="mexican" type="checkbox" class="w-4 h-4 bg-zinc-700 border-zinc-600 rounded focus:ring-yellow-500">
                                <label for="mexican" class="ml-2 text-sm text-gray-300">Mexican</label>
                            </div>
                        </div>

                        <h6 class="text-sm font-medium mt-4 mb-3">Price Range</h6>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input id="price-1" type="checkbox" class="w-4 h-4 bg-zinc-700 border-zinc-600 rounded focus:ring-yellow-500">
                                <label for="price-1" class="ml-2 text-sm text-gray-300">$ (Inexpensive)</label>
                            </div>
                            <div class="flex items-center">
                                <input id="price-2" type="checkbox" class="w-4 h-4 bg-zinc-700 border-zinc-600 rounded focus:ring-yellow-500">
                                <label for="price-2" class="ml-2 text-sm text-gray-300">$$ (Moderate)</label>
                            </div>
                            <div class="flex items-center">
                                <input id="price-3" type="checkbox" class="w-4 h-4 bg-zinc-700 border-zinc-600 rounded focus:ring-yellow-500">
                                <label for="price-3" class="ml-2 text-sm text-gray-300">$$$ (Expensive)</label>
                            </div>
                        </div>

                        <h6 class="text-sm font-medium mt-4 mb-3">Rating</h6>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input id="rating-4" type="checkbox" class="w-4 h-4 bg-zinc-700 border-zinc-600 rounded focus:ring-yellow-500">
                                <label for="rating-4" class="ml-2 text-sm text-gray-300">4+ Stars</label>
                            </div>
                            <div class="flex items-center">
                                <input id="rating-3" type="checkbox" class="w-4 h-4 bg-zinc-700 border-zinc-600 rounded focus:ring-yellow-500">
                                <label for="rating-3" class="ml-2 text-sm text-gray-300">3+ Stars</label>
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button class="text-sm text-gray-400 hover:text-white">Clear All</button>
                            <button class="text-sm bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-1 rounded-lg transition">Apply</button>
                        </div>
                    </div>
                </div>

                <button id="dietary-dropdown-button" data-dropdown-toggle="dietary-dropdown"
                    class="flex items-center bg-zinc-800 hover:bg-zinc-700 text-white font-medium rounded-lg text-sm px-4 py-2.5 transition">
                    <i class="fas fa-leaf mr-2"></i>
                    Dietary
                    <i class="fas fa-chevron-down ml-2"></i>
                </button>
                <div id="dietary-dropdown" class="hidden z-10 w-48 bg-zinc-800 rounded-lg shadow">
                    <div class="p-4">
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input id="vegetarian" type="checkbox" class="w-4 h-4 bg-zinc-700 border-zinc-600 rounded focus:ring-yellow-500">
                                <label for="vegetarian" class="ml-2 text-sm text-gray-300">Vegetarian</label>
                            </div>
                            <div class="flex items-center">
                                <input id="vegan" type="checkbox" class="w-4 h-4 bg-zinc-700 border-zinc-600 rounded focus:ring-yellow-500">
                                <label for="vegan" class="ml-2 text-sm text-gray-300">Vegan</label>
                            </div>
                            <div class="flex items-center">
                                <input id="gluten-free" type="checkbox" class="w-4 h-4 bg-zinc-700 border-zinc-600 rounded focus:ring-yellow-500">
                                <label for="gluten-free" class="ml-2 text-sm text-gray-300">Gluten-Free</label>
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button class="text-sm text-gray-400 hover:text-white">Clear</button>
                            <button class="text-sm bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-1 rounded-lg transition">Apply</button>
                        </div>
                    </div>
                </div>

                <div class="flex items-center bg-zinc-800 text-white font-medium rounded-lg text-sm px-4 py-2.5">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    <span class="truncate max-w-[100px] md:max-w-none">Near Me</span>
                </div>
            </div> -->

            <!-- Sort -->
            <!-- <div class="flex items-center">
                <label for="sort" class="mr-2 text-sm font-medium text-gray-300">Sort by:</label>
                <select id="sort" name="sort" class="bg-zinc-800 border border-zinc-700 text-white text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 p-2.5">
                    <option value="relevance">Relevance</option>
                    <option value="rating">Rating (High to Low)</option>
                    <option value="reviews">Most Reviews</option>
                    <option value="price-low">Price (Low to High)</option>
                    <option value="price-high">Price (High to Low)</option>
                </select>
            </div> -->
        </div>

        <!-- Search Results -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-6">Search Results</h2>

            <!-- Results Count and View Toggle -->

            <?php if (isset($_GET['query']) || $results ) { 
                $query="All";
                if(isset($_GET['query'])) {
                    $query=$_GET['query'];
                }
                ?>
                <div class="mb-4">
                    <?php if (isset($results) && count($results) > 0) {
                        
                        ?>
                        <p class="text-gray-400">Showing <?php echo count($results); ?> result<?php echo count($results) > 1 ? 's' : ''; ?> for "<?php echo htmlspecialchars($query, ENT_QUOTES, 'UTF-8'); ?>"</p>
                    <?php } else { 
                        
                        ?>
                        <p class="text-gray-400">No results found for "<?php echo htmlspecialchars($query?$query:'All', ENT_QUOTES, 'UTF-8'); ?>"</p>
                    <?php } ?>
                </div>
                <!-- Results Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    if (count($results) > 0) {
                        // print_r($results); // Debugging line to check the structure of $results
                        foreach ($results as $result) {


                            // Display results based on type (restaurant or menu_item)
                            if ($result['type'] === 'restaurant') {
                    ?>
                                <div class="bg-zinc-900 <?php echo ($result['rest_status'] === 'inactive' || $result['rest_status'] === 'closed') ? 'opacity-50' : '' ?> rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition group">
                                    <div class="relative">
                                        <img src="../AdminPanel/<?php echo $result['rest_image_url']; ?>"
                                            alt="Italian Restaurant" class="w-full h-48 object-cover <?php echo ($result['rest_status'] === 'inactive' || $result['rest_status'] === 'closed') ? '' : 'group-hover:scale-105' ?>   transition duration-300">
                                        <div class="absolute top-2 right-2 bg-black/70 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-md flex items-center">
                                            <i class="fas fa-star text-yellow-500 mr-1"></i>
                                            4.7
                                        </div>
                                        <?php if ($result['rest_status'] === 'inactive' || $result['rest_status'] === 'closed'): ?>
                                            <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-semibold px-3 py-1 rounded-md z-10">
                                                Currently Closed
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="p-4">
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-lg font-semibold <?php echo ($result['rest_status'] === 'inactive' || $result['rest_status'] === 'closed') ? 'text-gray-400' : 'group-hover:text-yellow-500' ?>  transition"><?php echo $result['rest_name'] ?></h3>
                                            <!-- <span class="bg-yellow-500/10 text-yellow-500 text-xs px-2 py-1 rounded-md">$$$</span> -->
                                        </div>
                                        <p class="text-sm text-gray-400 mt-1">Italian, Pizza, Pasta</p>
                                        <div class="flex items-center mt-2 text-sm text-gray-400">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            <span>1.8 km away</span>
                                            <span class="mx-2">•</span>
                                            <span class=" <?php echo ($result['rest_status'] === 'inactive' || $result['rest_status'] === 'closed') ? 'text-gray-400' : 'text-green-500' ?>"><?php echo ($result['rest_status'] === 'inactive' || $result['rest_status'] === 'closed') ? 'Closed' : 'Open Now' ?></span>
                                        </div>
                                        <div class="mt-3 flex items-center justify-between">
                                            <span class="text-sm bg-zinc-800 px-2 py-1 rounded-md">20-35 min</span>
                                            <?php if ($result['rest_status'] === 'inactive' || $result['rest_status'] === 'closed') {
                                            } else {

                                            ?>
                                                <a href="restaurant.php?id=<?php echo $result['rest_id'] ?>" class="text-yellow-500 hover:text-yellow-400 text-sm font-medium">View Menu</a>
                                            <?php

                                            }
                                            ?>

                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            if ($result['type'] === 'menu_item') {
                                // $res=$result['item_restaurant_name'];
                            ?>

                                <div class="bg-zinc-900 rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition group <?php echo ($result['item_restaurant_status'] == "inactive" || $result['item_availability'] == 0) ? 'opacity-50' : ''; ?>">
                                    <div class="relative">
                                        <img src="../AdminPanel/<?php echo htmlspecialchars($result['item_image_url']); ?>"
                                            alt="<?php echo htmlspecialchars($result['item_name']); ?>"
                                            class="w-full h-48 object-cover <?php echo ($result['item_restaurant_status'] == "inactive" || $result['item_availability'] == 0) ? '' : 'group-hover:scale-105'; ?> transition duration-300">

                                        <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-md">
                                            Bestseller
                                        </div>
                                        <?php if ($result['item_restaurant_status'] == "inactive" || $result['item_availability'] == 0): ?>
                                            <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-semibold px-3 py-1 rounded-md z-10">
                                                Currently Closed
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="p-4">
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-lg font-semibold <?php echo ($result['item_restaurant_status'] == "inactive" || $result['item_availability'] == 0) ? 'text-gray-400' : 'group-hover:text-yellow-500'; ?> transition">
                                                <?php echo htmlspecialchars($result['item_name']); ?>
                                            </h3>
                                            <span class="text-lg font-bold  <?php echo ($result['item_restaurant_status'] == "inactive" || $result['item_availability'] == 0) ? 'text-gray-400' : 'text-yellow-500' ?>">₹<?php echo htmlspecialchars($result['item_price']); ?></span>
                                        </div>
                                        <p class="text-sm text-gray-400 mt-1">at <a href="#" class=" <?php echo ($result['item_restaurant_status'] == "inactive" || $result['item_availability'] == 0) ? ' text-gray-400' : 'text-white' ?> hover:text-yellow-500"><?php echo htmlspecialchars($result['item_restaurant_name']); ?></a></p>
                                        <div class="flex items-center mt-2 text-sm text-gray-400">
                                            <span class="bg-zinc-800 text-gray-300 text-xs px-2 py-1 rounded-md flex items-center mr-2">
                                                <i class="fas fa-fire text-red-500 mr-1"></i>
                                                Spicy
                                            </span>
                                            <span class="bg-zinc-800 text-gray-300 text-xs px-2 py-1 rounded-md flex items-center">
                                                <i class="fas fa-utensils mr-1"></i>
                                                Non-Veg
                                            </span>
                                        </div>
                                        <div class="mt-3 flex items-center justify-between">
                                            <span class="text-sm bg-zinc-800 px-2 py-1 rounded-md">30 min delivery</span>
                                            <?php if ($result['item_restaurant_status'] == "inactive" || $result['item_availability'] == 0) { ?>
                                                <button class="text-gray-400 bg-gray-700 cursor-not-allowed text-xs font-medium px-3 py-1 rounded-lg transition flex items-center" disabled>
                                                    <i class="fas fa-cart-plus mr-1"></i>
                                                    Unavailable
                                                </button>
                                            <?php } else { ?>


                                                <?php
                                                $cartItems = $obj->getCartItems($uid);
                                                $itemExists = false;
                                                $itemQuantity = 0;

                                                // Check if item exists in cart
                                                foreach ($cartItems as $cartItem) {
                                                    if ($cartItem['item_id'] == $result['item_id']) {
                                                        $itemExists = true;
                                                        $itemQuantity = $cartItem['quantity'];
                                                        break;
                                                    }
                                                }
                                                if ($itemExists) { ?>

                                                    <div class="flex items-center gap-3 rounded-full border border-white px-2 py-1 text-sm">

                                                        <a
                                                            href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $result['rest_id']; ?>&updateCart=<?php echo $cartItem['cart_id']; ?>&item_id=<?php echo $result['item_id']; ?>&action=decrease&quantity=<?php echo $itemQuantity - 1; ?>&query=<?php echo $query; ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                                                fill="#eab308" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round" class="lucide lucide-minus text-yellow-500 cursor-pointer">
                                                                <path d="M5 12h14" />
                                                            </svg>
                                                        </a>
                                                        <span class="text-sm"><?php echo $itemQuantity; ?></span>
                                                        <a
                                                            href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $result['rest_id']; ?>&updateCart=<?php echo $cartItem['cart_id']; ?>&item_id=<?php echo $result['item_id']; ?>&action=increase&quantity=<?php echo $itemQuantity + 1; ?>&query=<?php echo $query; ?>">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                                                fill="#eab308" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round" class="lucide lucide-plus cursor-pointer text-yellow-500">
                                                                <path d="M5 12h14" />
                                                                <path d="M12 5v14" />
                                                            </svg>
                                                        </a>
                                                    </div>

                                                <?php } else {
                                                ?>
                                                    <form
                                                        action="?id=<?php echo $result['rest_id']; ?>&addtoCart=<?php echo $result['item_id'] ?>&price=<?php echo $result['item_price'] ?>"
                                                        method="POST">
                                                        <button class="text-black bg-yellow-500 hover:bg-yellow-600 text-xs font-medium px-3 py-1 rounded-lg transition flex items-center">
                                                            <i class="fas fa-cart-plus mr-1"></i>
                                                            Add to Cart
                                                        </button>
                                                    </form>

                                                <?php
                                                } ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                    <?php }
                        }
                    } else {
                        echo "<p class='text-gray-400'>No results found for your search.</p>";
                    }
                    ?>

                </div>
            <?php } ?>

            <!-- Pagination -->
            <!-- <div class="flex justify-center mt-10">
                <nav aria-label="Page navigation">
                    <ul class="flex items-center -space-x-px h-10 text-base">
                        <li>
                            <a href="#" class="flex items-center justify-center px-4 h-10 ml-0 leading-tight bg-zinc-800 border border-zinc-700 rounded-l-lg hover:bg-zinc-700 text-gray-400 hover:text-white">
                                <span class="sr-only">Previous</span>
                                <i class="fas fa-chevron-left w-3 h-3"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" aria-current="page" class="z-10 flex items-center justify-center px-4 h-10 leading-tight bg-yellow-500 text-black font-medium">1</a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center px-4 h-10 leading-tight bg-zinc-800 border border-zinc-700 hover:bg-zinc-700 text-gray-400 hover:text-white">2</a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center px-4 h-10 leading-tight bg-zinc-800 border border-zinc-700 hover:bg-zinc-700 text-gray-400 hover:text-white">3</a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center px-4 h-10 leading-tight bg-zinc-800 border border-zinc-700 hover:bg-zinc-700 text-gray-400 hover:text-white">4</a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center px-4 h-10 leading-tight bg-zinc-800 border border-zinc-700 hover:bg-zinc-700 text-gray-400 hover:text-white">5</a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center px-4 h-10 leading-tight bg-zinc-800 border border-zinc-700 rounded-r-lg hover:bg-zinc-700 text-gray-400 hover:text-white">
                                <span class="sr-only">Next</span>
                                <i class="fas fa-chevron-right w-3 h-3"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div> -->
        </div>

        <!-- Recent Searches -->
        <!-- <div class="mt-12">
            <h3 class="text-xl font-bold mb-4">Recent Searches</h3>
            <div class="flex flex-wrap gap-2">
                <a href="#" class="flex items-center bg-zinc-800 hover:bg-zinc-700 text-gray-300 text-sm px-3 py-1.5 rounded-full transition">
                    <span>Burger</span>
                    <i class="fas fa-times ml-2 text-gray-500 hover:text-white"></i>
                </a>
                <a href="#" class="flex items-center bg-zinc-800 hover:bg-zinc-700 text-gray-300 text-sm px-3 py-1.5 rounded-full transition">
                    <span>Chinese restaurants</span>
                    <i class="fas fa-times ml-2 text-gray-500 hover:text-white"></i>
                </a>
                <a href="#" class="flex items-center bg-zinc-800 hover:bg-zinc-700 text-gray-300 text-sm px-3 py-1.5 rounded-full transition">
                    <span>Vegetarian</span>
                    <i class="fas fa-times ml-2 text-gray-500 hover:text-white"></i>
                </a>
                <a href="#" class="flex items-center bg-zinc-800 hover:bg-zinc-700 text-gray-300 text-sm px-3 py-1.5 rounded-full transition">
                    <span>Desserts</span>
                    <i class="fas fa-times ml-2 text-gray-500 hover:text-white"></i>
                </a>
            </div>
        </div> -->

        <!-- Popular Searches -->
        <!-- <div class="mt-8">
            <h3 class="text-xl font-bold mb-4">Popular Searches</h3>
            <div class="flex flex-wrap gap-2">
                <a href="#" class="bg-zinc-800 hover:bg-zinc-700 text-gray-300 text-sm px-3 py-1.5 rounded-full transition">
                    Pizza
                </a>
                <a href="#" class="bg-zinc-800 hover:bg-zinc-700 text-gray-300 text-sm px-3 py-1.5 rounded-full transition">
                    Burger
                </a>
                <a href="#" class="bg-zinc-800 hover:bg-zinc-700 text-gray-300 text-sm px-3 py-1.5 rounded-full transition">
                    Chinese
                </a>
                <a href="#" class="bg-zinc-800 hover:bg-zinc-700 text-gray-300 text-sm px-3 py-1.5 rounded-full transition">
                    Italian
                </a>
                <a href="#" class="bg-zinc-800 hover:bg-zinc-700 text-gray-300 text-sm px-3 py-1.5 rounded-full transition">
                    Fast Food
                </a>
                <a href="#" class="bg-zinc-800 hover:bg-zinc-700 text-gray-300 text-sm px-3 py-1.5 rounded-full transition">
                    Vegetarian
                </a>
                <a href="#" class="bg-zinc-800 hover:bg-zinc-700 text-gray-300 text-sm px-3 py-1.5 rounded-full transition">
                    Desserts
                </a>
                <a href="#" class="bg-zinc-800 hover:bg-zinc-700 text-gray-300 text-sm px-3 py-1.5 rounded-full transition">
                    Breakfast
                </a>
            </div>
        </div> -->
    </div>


    <?php require("footer.php"); ?>
    <script>
        // Simple script to handle dropdown toggles
        document.addEventListener('DOMContentLoaded', function() {
            // For demonstration purposes only - in a real app, use Flowbite's built-in functionality
            const dropdownButtons = document.querySelectorAll('[data-dropdown-toggle]');

            dropdownButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-dropdown-toggle');
                    const targetDropdown = document.getElementById(targetId);

                    if (targetDropdown.classList.contains('hidden')) {
                        targetDropdown.classList.remove('hidden');
                    } else {
                        targetDropdown.classList.add('hidden');
                    }
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(event) {
                dropdownButtons.forEach(button => {
                    const targetId = button.getAttribute('data-dropdown-toggle');
                    const targetDropdown = document.getElementById(targetId);

                    if (!button.contains(event.target) && !targetDropdown.contains(event.target)) {
                        targetDropdown.classList.add('hidden');
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Get all suggestion elements
            const suggestions = document.querySelectorAll('.suggestion');
            const searchInput = document.getElementById('main-search');
            const searchForm = searchInput.closest('form');

            // Add click event listener to each suggestion
            suggestions.forEach(suggestion => {
                suggestion.addEventListener('click', function() {
                    // Set the clicked suggestion text to the search input
                    searchInput.value = this.textContent.trim();

                    // Submit the form
                    searchForm.submit();
                });
            });
        });
    </script>
</body>

</html>