<script src="https://unpkg.com/@themesberg/flowbite@latest/dist/flowbite.bundle.js"></script>
<?php




if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

?>


<body class="min-h-screen text-white bg-black ">

    <?php
    $ActivePage = "Menu";
    require 'navbar.php';
    ?>

    <?php
    $uid = $_SESSION['user']['user_id'];


    $restaurant = $obj->getRestaurantById($id);

    $getAllMenuItems = $obj->getAllMenuItems();


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
    }

    ?>
    <?php
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

    }
    ?>

    <!-- Menu Section -->
    <main class="flex-grow py-20">
        <div class=" flex flex-col gap-9  mx-auto max-w-7xl">

            <?php $status = "open"; ?>

            <main>
                <!-- Restaurant Banner -->
                <div class="relative w-full h-60">
                    <img src="../AdminPanel/<?php echo $restaurant['restaurant_pic'] ?>" alt="Restaurant Banner"
                        class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/50 flex flex-col justify-center p-6">
                        <h1 class="text-3xl font-bold"><?php echo $restaurant['name'] ?></h1>
                        <p class="text-sm text-gray-300">Pizza, Fast Food, Desserts, Beverages</p>
                        <p class="text-sm text-gray-400"><?php echo $restaurant['address'] ?></p>
                    </div>
                </div>

                <div class=" mx-auto p-6">
                    <div class="grid sm:grid-cols-[auto_1fr] gap-6">
                        <!-- Sidebar -->
                        <div class="w-full  sm:w-fit ">
                            <h2 class="text-lg font-semibold border-b border-zinc-700 pb-2 mb-3">Order Online</h2>
                            <ul class="space-y-2 px-4">
                                <li class="cursor-pointer text-yellow-500 font-medium">Veg Pizza (13)</li>
                                <li class="cursor-pointer text-gray-400">Non Veg Pizza (12)</li>
                                <li class="cursor-pointer text-gray-400">Meals And Deals (6)</li>
                                <li class="cursor-pointer text-gray-400">Thin n Crispy Pizzas (6)</li>
                                <li class="cursor-pointer text-gray-400">Flavour Fun Range (6)</li>
                            </ul>
                        </div>

                        <div class="grid grid-cols-1  md:grid-cols-2  lg:grid-cols-3  gap-6 px-4 sm:px-6 md:px-10">
                            <?php
                            foreach ($getAllMenuItems as $allMenu) {
                                if ($id == $allMenu['restaurant_id'])
                                    require('foodCard.php');
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </main>


        </div>
        </div>
    </main>




    <!-- Footer -->
    <?php require 'footer.php' ?>
</body>