<script src="https://unpkg.com/@themesberg/flowbite@latest/dist/flowbite.bundle.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
?>

<body class="min-h-screen text-white bg-black">
    <?php
    $ActivePage = "Menu";
    require 'navbar.php';
    ?>

    <?php
    $uid = $_SESSION['user']['user_id'];
    $restaurant = $obj->getRestaurantById($id);
    $getAllMenuItems = $obj->getAllMenuItems();

    // Get restaurant reviews and calculate average rating
    $reviews = $obj->getRestaurantReviews($id);
    $reviewCount = count($reviews);
    $avgRating = 0;

    if ($reviewCount > 0) {
        $totalRating = 0;
        foreach ($reviews as $review) {
            $totalRating += $review['rating'];
        }
        $avgRating = round($totalRating / $reviewCount, 1);
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

    // Handle review submission
    // if (isset($_POST['submit_review'])) {
    //     $rating = $_POST['rating'];
    //     $comment = $_POST['comment'];

    //     // Add review to database
    //     $obj->addRestaurantReview($id, $uid, $rating, $comment);

    //     // Redirect to prevent form resubmission
    //     header("Location: restaurant-menu.php?id=$id&review_added=1");
    //     exit();
    // }
    ?>

    <!-- Menu Section -->
    <main class="flex-grow py-20 relative">
        <div class="flex flex-col gap-9 mx-auto max-w-7xl">
            <?php $status = "open"; ?>

            <main>
                <!-- Restaurant Banner with Review Summary -->
                <div class="relative w-full h-60">
                    <img src="../AdminPanel/<?php echo $restaurant['restaurant_pic'] ?>" alt="Restaurant Banner"
                        class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/50 flex flex-col justify-center p-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div>
                                <h1 class="text-3xl font-bold"><?php echo $restaurant['name'] ?></h1>
                                <p class="text-sm text-gray-300">Pizza, Fast Food, Desserts, Beverages</p>
                                <p class="text-sm text-gray-400"><?php echo $restaurant['address'] ?></p>
                            </div>

                            <!-- Review Summary -->
                            <div class="mt-4 md:mt-0 bg-black/40 backdrop-blur-sm p-3 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex items-center mr-2">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <?php if ($i <= $avgRating) : ?>
                                                <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.799-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            <?php elseif ($i - 0.5 <= $avgRating) : ?>
                                                <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.799-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            <?php else : ?>
                                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.799-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="text-white font-bold"><?php echo $avgRating; ?></span>
                                    <span class="text-gray-300 ml-1">(<?php echo $reviewCount; ?> reviews)</span>
                                </div>
                                <button onclick="document.getElementById('reviews-section').scrollIntoView({behavior: 'smooth'})"
                                    class="mt-2 text-sm text-yellow-500 hover:text-yellow-400 transition">
                                    Read reviews
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mx-auto p-6">
                    <div class="grid sm:grid-cols-[auto_1fr] gap-6">
                        <!-- Sidebar -->
                        <div class="w-full sm:w-fit">
                            <h2 class="text-lg font-semibold border-b border-zinc-700 pb-2 mb-3">Order Online</h2>
                            <ul class="space-y-2 px-4">
                                <li class="cursor-pointer text-yellow-500 font-medium">Veg Pizza (13)</li>
                                <li class="cursor-pointer text-gray-400">Non Veg Pizza (12)</li>
                                <li class="cursor-pointer text-gray-400">Meals And Deals (6)</li>
                                <li class="cursor-pointer text-gray-400">Thin n Crispy Pizzas (6)</li>
                                <li class="cursor-pointer text-gray-400">Flavour Fun Range (6)</li>
                            </ul>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4 sm:px-6 md:px-10">
                            <?php
                            foreach ($getAllMenuItems as $allMenu) {
                                if ($id == $allMenu['restaurant_id'])
                                    require('foodCard.php');
                            }
                            ?>
                        </div>
                    </div>

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
                                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                <?php if ($i <= $avgRating) : ?>
                                                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.799-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                <?php else : ?>
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

                                <?php for ($i = 5; $i >= 1; $i--) : ?>
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

                                <?php
                                // Get most common tags from reviews
                                $tags = [
                                    'Delicious food' => 85,
                                    'Great service' => 72,
                                    'Value for money' => 65,
                                    'Fast delivery' => 58,
                                    'Good portions' => 45
                                ];
                                ?>

                                <!-- <div class="flex flex-wrap gap-2 mb-4">
                                    <?php foreach ($tags as $tag => $count) : ?>
                                        <span class="bg-zinc-800 text-gray-300 text-xs px-3 py-1 rounded-full">
                                            <?php echo $tag; ?> (<?php echo $count; ?>)
                                        </span>
                                    <?php endforeach; ?>
                                </div> -->

                                <div class="mt-4">
                                    <h4 class="text-sm font-medium mb-2">Popular dishes mentioned</h4>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="bg-yellow-500/10 text-yellow-500 text-xs px-3 py-1 rounded-full">
                                            Margherita Pizza (24)
                                        </span>
                                        <span class="bg-yellow-500/10 text-yellow-500 text-xs px-3 py-1 rounded-full">
                                            Pepperoni Pizza (18)
                                        </span>
                                        <span class="bg-yellow-500/10 text-yellow-500 text-xs px-3 py-1 rounded-full">
                                            Garlic Bread (15)
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Review List -->
                        <div class="space-y-6">
                            <?php if (count($reviews) > 0) : ?>
                                <?php foreach ($reviews as $review) : ?>
                                    <?php if($review['status'] == 'published') {?>
                                    <div class="bg-zinc-900 rounded-xl p-6">
                                        <div class="flex justify-between items-start">
                                            <div class="flex items-start">
                                                <img src="../AdminPanel/<?php echo $review['user_profile_pic'] ?? 'assets/default-avatar.png'; ?>"
                                                    alt="User" class="w-10 h-10 rounded-full mr-3 object-cover">
                                                <div>
                                                    <h4 class="font-medium"><?php echo $review['user_first_name']; ?></h4>
                                                    <div class="flex items-center mt-1">
                                                        <div class="flex mr-2">
                                                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                                <?php if ($i <= $review['rating']) : ?>
                                                                    <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.799-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                    </svg>
                                                                <?php else : ?>
                                                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.799-2.034c-.784-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
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

                                            <?php if ($review['user_id'] == $uid) : ?>
                                                <div class="dropdown">
                                                    <button class="text-gray-400 hover:text-white">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                        </svg>
                                                    </button>
                                                    <div class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-zinc-800 rounded-md shadow-lg z-10">
                                                        <!-- <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:bg-zinc-700">Edit Review</a> -->
                                                        <a onclick="report()" class="block px-4 py-2 text-sm text-gray-300 cursor-pointer text-wrap hover:bg-zinc-700">Report Review</a>
                                                        <!-- <a href="#" class="block px-4 py-2 text-sm text-red-400 hover:bg-zinc-700">Delete Review</a> -->
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <p class="mt-3 text-gray-300"><?php echo $review['review_text']; ?></p>

                                        <?php if (!empty($review['images'])) : ?>
                                            <div class="mt-3 flex gap-2 overflow-x-auto pb-2">
                                                <?php foreach ($review['images'] as $image) : ?>
                                                    <img src="<?php echo $image; ?>" alt="Review Image"
                                                        class="w-20 h-20 object-cover rounded-md cursor-pointer hover:opacity-90 transition">
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!empty($review['ordered_items'])) : ?>
                                            <div class="mt-3 text-xs text-gray-400">
                                                <span class="font-medium">Ordered:</span>
                                                <?php echo implode(', ', $review['ordered_items']); ?>
                                            </div>
                                        <?php endif; ?>

                                        <div class="mt-4 flex items-center text-sm">
                                            <button class="flex items-center text-gray-400 hover:text-white mr-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                                </svg>
                                                Helpful (<?php echo $review['helpful_count'] ?? 0; ?>)
                                            </button>
                                            <button class="flex items-center text-gray-400 hover:text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                </svg>
                                                Reply
                                            </button>
                                        </div>

                                        <?php if (!empty($review['replies'])) : ?>
                                            <div class="mt-4 pl-4 border-l-2 border-zinc-800">
                                                <?php foreach ($review['replies'] as $reply) : ?>
                                                    <div class="mt-3">
                                                        <div class="flex items-start">
                                                            <img src="<?php echo $reply['user_image'] ?? 'assets/default-avatar.png'; ?>"
                                                                alt="User" class="w-8 h-8 rounded-full mr-2 object-cover">
                                                            <div>
                                                                <h5 class="font-medium text-sm">
                                                                    <?php echo $reply['user_name']; ?>
                                                                    <?php if ($reply['is_owner']) : ?>
                                                                        <span class="bg-yellow-500/20 text-yellow-500 text-xs px-2 py-0.5 rounded-full ml-2">Owner</span>
                                                                    <?php endif; ?>
                                                                </h5>
                                                                <p class="text-sm text-gray-300 mt-1"><?php echo $reply['comment']; ?></p>
                                                                <span class="text-xs text-gray-400 mt-1 block">
                                                                    <?php echo date('M d, Y', strtotime($reply['created_at'])); ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php } ?>
                                <?php endforeach; ?>

                                <!-- Load More Button -->
                                <?php if (count($reviews) > 5) : ?>
                                    <div class="flex justify-center mt-8">
                                        <button class="px-6 py-3 bg-zinc-800 hover:bg-zinc-700 text-white font-medium rounded-lg transition">
                                            Load More Reviews
                                        </button>
                                    </div>
                                <?php endif; ?>

                            <?php else : ?>
                                <div class="bg-zinc-900 rounded-xl p-8 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                    <h3 class="text-xl font-bold mb-2">No reviews yet</h3>
                                    <p class="text-gray-400 mb-6">Be the first to review this restaurant!</p>
                                    <!-- <button data-modal-target="review-modal" data-modal-toggle="review-modal"
                                        class="px-6 py-3 bg-yellow-500 text-black font-medium rounded-lg hover:bg-yellow-600 transition">
                                        Write a Review
                                    </button> -->
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <a href="menu.php" class="fixed cursor-pointer top-0 mt-20 left-0 m-5 flex px-4 hover:scale-105 transition-all py-2 gap-2 rounded-full bg-yellow-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-arrow-left-icon lucide-arrow-left text-black">
                <path d="m12 19-7-7 7-7" />
                <path d="M19 12H5" />
            </svg>
            <span class="text-black font-bold"> Back</span>
        </a>
    </main>

    <!-- Footer -->
    <?php require 'footer.php' ?>

    <script>
        function report() {
            Swal.fire({
                title: "Are you sure want to report?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Report it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Reported!",
                        text: "Your Report Submitted",
                        icon: "success"
                    });
                }
            });
        }
        // Star rating functionality
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.rating label');

            stars.forEach((star, index) => {
                star.addEventListener('click', () => {
                    stars.forEach((s, i) => {
                        if (i <= index) {
                            s.classList.add('text-yellow-500');
                            s.classList.remove('text-gray-400');
                        } else {
                            s.classList.remove('text-yellow-500');
                            s.classList.add('text-gray-400');
                        }
                    });
                });

                star.addEventListener('mouseover', () => {
                    stars.forEach((s, i) => {
                        if (i <= index) {
                            s.classList.add('text-yellow-500');
                            s.classList.remove('text-gray-400');
                        } else {
                            s.classList.remove('text-yellow-500');
                            s.classList.add('text-gray-400');
                        }
                    });
                });

                star.addEventListener('mouseout', () => {
                    stars.forEach((s, i) => {
                        const input = document.getElementById(`rating-${i+1}`);
                        if (input.checked) {
                            s.classList.add('text-yellow-500');
                            s.classList.remove('text-gray-400');
                        } else {
                            s.classList.remove('text-yellow-500');
                            s.classList.add('text-gray-400');
                        }
                    });
                });
            });

            // Initialize dropdown menus
            const dropdownButtons = document.querySelectorAll('.dropdown button');
            dropdownButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const menu = this.nextElementSibling;
                    menu.classList.toggle('hidden');
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(event) {
                dropdownButtons.forEach(button => {
                    if (!button.contains(event.target)) {
                        const menu = button.nextElementSibling;
                        if (!menu.classList.contains('hidden')) {
                            menu.classList.add('hidden');
                        }
                    }
                });
            });
        });
    </script>
</body>