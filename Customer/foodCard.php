<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Card Design</title>
</head>

<body>
    <div class="menu-card h-fit group relative rounded-2xl overflow-hidden shadow-lg bg-zinc-900 border border-zinc-800 <?php echo ($allMenu['is_available'] == "
            0") ? 'opacity-60' : 'hover:border-yellow-500/30 hover:-translate-y-2'; ?> transition-all duration-300 ">
        <!-- Image Section with Hover Effect -->
        <div class="relative overflow-hidden">
            <img src="../AdminPanel/<?php echo $allMenu['image_url'] ?>" alt="Delicious Pizza" class="<?php echo ($allMenu['is_available'] == "
                    0") ? 'grayscale' : 'group-hover:scale-110'; ?> w-full h-48 object-cover rounded-t-2xl
                transition-transform duration-500 " />

            <!-- Bestseller Badge -->
            <span
                class="absolute top-2 left-2 bg-yellow-500 text-black text-xs font-semibold px-3 py-1 rounded-md z-10">
                Bestseller
            </span>

            <!-- Closed Badge -->
            <?php if ($allMenu['is_available'] == "0"): ?>
                <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-semibold px-3 py-1 rounded-md z-10">
                    Currently Closed
                </span>
            <?php endif; ?>

            <!-- Rating Badge -->
            <!-- <div
                class="absolute top-2 right-2 bg-black/70 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-md flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-yellow-500 mr-1" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.799-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                4.8
            </div> -->

            <!-- Overlay with Quick Actions -->
            <?php if ($allMenu['is_available'] !== "0"): ?>
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                    <div
                        class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 flex justify-center space-x-2">
                        <!-- <button
                            class="bg-yellow-500 text-black text-xs font-medium px-3 py-2 rounded-lg hover:bg-yellow-600 transition flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Quick View
                        </button> -->
                    </div>
                </div>
            <?php endif; ?>
        </div>


        <!-- Card Content -->

        <div class="p-4 flex flex-col gap-3">
            <div>
                <div class="flex justify-between items-start">
                    <h2
                        class="text-xl font-semibold text-white line-clamp-1 text-ellipsis <?php echo ($allMenu['is_available'] == '0') ? '' : 'group-hover:text-yellow-500'; ?>  transition-colors">
                        <?php echo $allMenu['item_name']; ?>
                    </h2>
                    <span
                        class="bg-yellow-500/10 <?php echo ($allMenu['is_available'] == '0') ? '' : 'text-yellow-500'; ?>  text-xs px-2 py-1 rounded-md">Veg</span>
                </div>
                <p class="text-sm text-gray-400 mt-1 line-clamp-2"><?php echo $allMenu['description'] ?></p>
            </div>

            <!-- Features / Tags  -->
            <div class="flex flex-wrap gap-2 mt-1">
                <?php
                $data = $allMenu["tags"];
                $tags = is_string($data) ? json_decode($data, true) : $data;

                foreach ($tags as $tag) {
                ?>

                    <span class="bg-zinc-800 text-gray-300 text-xs px-2 py-1 rounded-md flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" />
                        </svg>
                        <?php echo $tag ?>
                    </span>

                <?php
                }
                ?>
            </div>

            <!-- Delivery Time & Price -->
            <div class="flex justify-between items-center mt-1">
                <div class="flex w-full justify-between items-center">
                    <div
                        class="text-lg font-semibold <?php echo ($allMenu['is_available'] == '0') ? '' : 'text-yellow-500'; ?> ">
                        ₹<?php echo $allMenu['price']; ?>
                    </div>


                    <?php

                    $itemExists = false;
                    $itemQuantity = 0;
                    if (isset($_SESSION['user']['user_id'])) {

                        $cartItems = $obj->getCartItems($uid);

                        foreach ($cartItems as $cartItem) {
                            if ($cartItem['item_id'] == $allMenu['item_id']) {
                                $itemExists = true;
                                $itemQuantity = $cartItem['quantity'];
                                break;
                            }
                        }
                    }
                    // Check if item exists in cart


                    if ($itemExists) { ?>
                        <div class="flex items-center gap-3 rounded-full border border-white px-2 py-1 text-sm">

                            <a
                                href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $id; ?>&updateCart=<?php echo $cartItem['cart_id']; ?>&item_id=<?php echo $allMenu['item_id']; ?>&action=decrease&quantity=<?php echo $itemQuantity - 1; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="#eab308" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-minus text-yellow-500 cursor-pointer">
                                    <path d="M5 12h14" />
                                </svg>
                            </a>
                            <span class="text-sm"><?php echo $itemQuantity; ?></span>
                            <a
                                href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $id; ?>&updateCart=<?php echo $cartItem['cart_id']; ?>&item_id=<?php echo $allMenu['item_id']; ?>&action=increase&quantity=<?php echo $itemQuantity + 1; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="#eab308" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-plus cursor-pointer text-yellow-500">
                                    <path d="M5 12h14" />
                                    <path d="M12 5v14" />
                                </svg>
                            </a>
                        </div>
                        <?php } else {
                         if($allMenu['is_available'] != "0"){
                        if (isset($_SESSION['user']['user_id'])) {
                        ?>
                            <form
                                action="?id=<?php echo $id; ?>&addtoCart=<?php echo $allMenu['item_id'] ?>&price=<?php echo $allMenu['price'] ?>"
                                method="POST">
                                <button class="text-black bg-yellow-500 hover:bg-yellow-600 text-xs font-medium px-3 py-1 rounded-lg transition flex items-center">
                                    <i class="fas fa-cart-plus mr-1"></i>
                                    Add to Cart
                                </button>
                            </form>
                        <?php
                        } else {
                        ?>
                         
                            <form
                                action="login.php"
                                method="POST">
                                <button class="text-black bg-yellow-500 hover:bg-yellow-600 text-xs font-medium px-3 py-1 rounded-lg transition flex items-center">
                                    <i class="fas fa-cart-plus mr-1"></i>
                                    Add to Cart
                                </button>
                            </form>
                            
                    <?php

                        }
                    }
                    }
                    
                    ?>




                </div>

            </div>

        </div>

        <!-- Discount Badge (Conditional) -->
        <!-- <div class="absolute -right-12  top-0 bg-red-500 text-white px-12 py-1 rotate-45 transform shadow-lg text-xs font-bold">
        20% OFF
    </div> -->

    </div>

</body>

</html>