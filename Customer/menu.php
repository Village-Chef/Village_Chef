<script src="https://unpkg.com/@themesberg/flowbite@latest/dist/flowbite.bundle.js"></script>

<?php
$ActivePage = "menu";
if (isset($_GET['sort'])) {
  $sort = $_GET['sort'];

  if ($sort == 'pop') {
    $sort = "Popularity";
  } elseif ($sort == 'ra') {
    $sort = "Rating: High to Low";
  } elseif ($sort == 'casc') {
    $sort = "Cost: Low to High";
  } elseif ($sort == 'cdesc') {
    $sort = "Cost: High to Low";
  }
}
?>
<style>
  /* From Uiverse.io by pathikcomp */
  .main>.inp {
    display: none;
  }

  .main {
    font-weight: 800;
    color: white;
    background-color: #eab308;
    padding: 3px 15px;
    border-radius: 10px;

    display: flex;
    align-items: center;
    height: 2.5rem;
    width: 8rem;
    position: relative;
    cursor: pointer;
    justify-content: space-between;
  }

  .arrow {
    height: 34%;
    aspect-ratio: 1;
    margin-block: auto;
    position: relative;
    display: flex;
    justify-content: center;
    transition: all 0.3s;
  }

  .arrow::after,
  .arrow::before {
    content: "";
    position: absolute;
    background-color: white;
    height: 100%;
    width: 2.5px;
    border-radius: 500px;
    transform-origin: bottom;
  }

  .arrow::after {
    transform: rotate(35deg) translateX(-0.5px);
  }

  .arrow::before {
    transform: rotate(-35deg) translateX(0.5px);
  }

  .main>.inp:checked+.arrow {
    transform: rotateX(180deg);
  }

  .menu-container {
    background-color: white;
    color: #eab308;
    border-radius: 10px;
    position: absolute;
    width: 100%;
    left: 0;
    bottom: 120%;
    overflow: hidden;
    clip-path: inset(0% 0% 0% 0% round 10px);
    transition: all 0.4s;
  }

  .menu-list {
    --delay: 0.4s;
    --trdelay: 0.15s;
    padding: 8px 10px;
    border-radius: inherit;
    transition: background-color 0.2s 0s;
    position: relative;
    transform: translateY(30px);
    opacity: 0;
  }

  .menu-list::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    height: 1px;
    background-color: rgba(0, 0, 0, 0.3);
    width: 95%;
  }

  .menu-list:hover {
    background-color: rgb(223, 223, 223);
  }

  .inp:checked~.menu-container {
    clip-path: inset(10% 50% 90% 50% round 10px);
  }

  .inp:not(:checked)~.menu-container .menu-list {
    transform: translateY(0);
    opacity: 1;
  }

  .inp:not(:checked)~.menu-container .menu-list:nth-child(1) {
    transition:
      transform 0.4s var(--delay),
      opacity 0.4s var(--delay);
  }

  .inp:not(:checked)~.menu-container .menu-list:nth-child(2) {
    transition:
      transform 0.4s calc(var(--delay) + (var(--trdelay) * 1)),
      opacity 0.4s calc(var(--delay) + (var(--trdelay) * 1));
  }

  .inp:not(:checked)~.menu-container .menu-list:nth-child(3) {
    transition:
      transform 0.4s calc(var(--delay) + (var(--trdelay) * 2)),
      opacity 0.4s calc(var(--delay) + (var(--trdelay) * 2));
  }

  .inp:not(:checked)~.menu-container .menu-list:nth-child(4) {
    transition:
      transform 0.4s calc(var(--delay) + (var(--trdelay) * 3)),
      opacity 0.4s calc(var(--delay) + (var(--trdelay) * 3));
  }

  .bar-inp {
    -webkit-appearance: none;
    display: none;
    visibility: hidden;
  }

  .bar {
    display: flex;
    height: 50%;
    width: 20px;
    flex-direction: column;
    gap: 3px;
  }

  .bar-list {
    --transform: -25%;
    display: block;
    width: 100%;
    height: 3px;
    border-radius: 50px;
    background-color: white;
    transition: all 0.4s;
    position: relative;
  }

  .inp:not(:checked)~.bar>.top {
    transform-origin: top right;
    transform: translateY(var(--transform)) rotate(-45deg);
  }

  .inp:not(:checked)~.bar>.middle {
    transform: translateX(-50%);
    opacity: 0;
  }

  .inp:not(:checked)~.bar>.bottom {
    transform-origin: bottom right;
    transform: translateY(calc(var(--transform) * -1)) rotate(45deg);
  }
</style>

<body class="min-h-screen text-white bg-black ">

  <!-- Navbar -->
  <?php
  require 'navbar.php';
  if (isset($_SESSION['user']['user_id'])) {
    $uid = $_SESSION['user']['user_id'];
    $restaurants = $obj->getAllRestaurants();
    $getAllMenuItems = $obj->getAllMenuItems();
    $getAllMenuItemsWithCuisineMap = $obj->getAllMenuItemsWithCuisineMap();
    // print_r($getAllMenuItemsWithCuisineMap);
    // print_r($getAllMenuItems);
    $userdata = $obj->getUserById($uid);
  } else {
    echo "<script> window.location.href='login.php'; </script>";
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
    echo "<script> window.location.href='menu.php'; </script>";
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
    echo "<script> window.location.href='menu.php'; </script>";
  }

  $data = $obj->getAllCuisines();
  // print_r($data);
  ?>

  <!-- Menu Section -->
  <main class="flex-grow py-20">
    <div class=" flex flex-col gap-2  mx-auto max-w-7xl">
      <script src="https://unpkg.com/@themesberg/flowbite@latest/dist/flowbite.bundle.js"></script>

      <?php
      // Handle multiple category selection
      $selectedCategories = isset($_GET['category']) ? (array) $_GET['category'] : [];
      ?>

      <style>
        /* Checkbox styling */
        .cuisine-option {
          position: relative;
        }

        .cuisine-option input[type="checkbox"] {
          position: absolute;
          opacity: 0;
          width: 100%;
          height: 100%;
          cursor: pointer;
        }

        .cuisine-option input[type="checkbox"]:checked+label {
          border-color: #eab308;
          box-shadow: 0 0 15px rgba(234, 179, 8, 0.4);
        }

        .cuisine-option label {
          transition: all 0.3s ease;
        }
      </style>

      <!-- Cuisine Selection Section -->
      <div class="flex flex-col sm:px-4 px-2 py-6 gap-9">
        <h1 class="text-xl font-bold md:text-4xl text-start">Inspiration for your first order</h1>
        <form method="get" id="categoryForm" class="auto-submit-form">
          <div class="flex flex-row gap-4 overflow-x-scroll overflow-y-hidden md:gap-8 noscorll scroll-smooth">
            <?php foreach ($data as $cuisine):
              $cuisineName = htmlspecialchars($cuisine['cuisine_name']);
              $cuisineID = htmlspecialchars($cuisine['cuisine_id']);
              $imagePath = 'Assets/' . strtolower(str_replace(' ', '', $cuisineName)) . '.jpg';
              ?>
              <div class="flex flex-col cuisine-option">
                <input type="checkbox" name="category[]" value="<?= $cuisineName ?>" id="cuisine_<?= $cuisineID ?>"
                  <?= in_array($cuisineName, $selectedCategories) ? 'checked' : '' ?> onchange="this.form.submit()">
                <label for="cuisine_<?= $cuisineID ?>"
                  class="flex flex-col flex-shrink-0 w-20 h-20 md:w-32 md:h-32 border-4 border-transparent rounded-full cursor-pointer">
                  <img class="w-full h-full rounded-full object-cover" src="<?= $imagePath ?>"
                    alt="<?= $cuisineName ?> cuisine">
                </label>
                <p class="z-10 my-3 font-sans text-center text-yellow-500 text-sm sm:text-md"><?= $cuisineName ?></p>
                <!-- <p><?php echo $cuisineName ?></p> -->
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Preserve sorting parameter -->
          <?php if (isset($_GET['sort'])): ?>
            <input type="hidden" name="sort" value="<?= htmlspecialchars($_GET['sort']) ?>">
          <?php endif; ?>
        </form>
      </div>

      <!-- Selected Categories Display -->
      <div class="<?= !empty($selectedCategories) ? 'flex' : 'hidden' ?> gap-2 flex-row sm:px-4 px-2 py-6">
        <div class="flex flex-row gap-3 flex-wrap">
          <?php foreach ($selectedCategories as $index => $cat):
            // Create URL without this category
            $newCategories = $selectedCategories;
            unset($newCategories[$index]);
            $queryParams = [
              'category' => array_values($newCategories),
              'sort' => $_GET['sort'] ?? null
            ];
            $removeUrl = '?' . http_build_query(array_filter($queryParams));
            ?>
            <div
              class="text-md gap-3 cursor-pointer rounded-full px-4 py-2 items-center bg-zinc-900 text-yellow-500 flex">
              <p><?= $cat ?></p>
              <a href="<?= $removeUrl ?>">
                <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Sorting Dropdown -->
      <div class="hidden text-base z-50 list-none bg-zinc-900 divide-y divide-gray-100 rounded shadow my-4"
        id="dropdown">
        <ul class="py-1" aria-labelledby="dropdown">
          <?php
          $sortOptions = [
            'pop' => 'Popularity',
            'ra' => 'Rating: High to Low',
            'casc' => 'Cost: Low to High',
            'cdesc' => 'Cost: High to Low'
          ];

          foreach ($sortOptions as $key => $label) {
            $queryParams = [
              'category' => $selectedCategories,
              'sort' => $key
            ];
            $url = '?' . http_build_query($queryParams);
            ?>
            <li>
              <a href="<?= $url ?>" class="text-sm hover:bg-zinc-800 bg-zinc-900 text-white block px-4 py-2">
                <?= $label ?>
              </a>
            </li>
          <?php } ?>
        </ul>
      </div>

      <script>
        // Automatically submit form when checkboxes change
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
          checkbox.addEventListener('change', function () {
            // Add slight delay for visual feedback
            setTimeout(() => this.form.submit(), 150);
          });
        });
      </script>

      <!-- Updated Grid Section -->
      <?php if (!empty($selectedCategories)): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 px-4 sm:px-6 md:px-10">
          <?php
          foreach ($getAllMenuItemsWithCuisineMap as $allMenu) {
            $id= $allMenu['restaurant_id'];
            if (in_array($allMenu['cuisine_name'], $selectedCategories)) {
              require('foodCard.php');
            }
          }
          ?>
        </div>
      <?php endif; ?>

      <!-- Modified Sorting Links -->
      <div class="hidden text-base z-50 list-none bg-zinc-900 divide-y divide-gray-100 rounded shadow my-4"
        id="dropdown">
        <ul class="py-1" aria-labelledby="dropdown">
          <?php
          $sortOptions = [
            'pop' => 'Popularity',
            'ra' => 'Rating: High to Low',
            'casc' => 'Cost: Low to High',
            'cdesc' => 'Cost: High to Low'
          ];

          foreach ($sortOptions as $key => $label) {
            $queryParams = [];
            if (!empty($selectedCategories)) {
              $queryParams['category'] = $selectedCategories;
            }
            $queryParams['sort'] = $key;
            $url = '?' . http_build_query($queryParams);
            ?>
            <li>
              <a href="<?= $url ?>" class="text-sm hover:bg-zinc-800 bg-zinc-900 text-white block px-4 py-2">
                <?= $label ?>
              </a>
            </li>
          <?php } ?>
        </ul>
      </div>

      <!-- Responsive Grid Layout -->
      <?php if (isset($_REQUEST['category'])) {
        ?>
        <div class="grid grid-cols-1 sm:grid-cols-2  md:grid-cols-3 lg:grid-cols-4 gap-6 px-4 sm:px-6 md:px-10">
          <?php
          $catid = $_REQUEST['category'];
          if (isset($_GET['category'])) {

            foreach ($getAllMenuItemsWithCuisineMap as $allMenu) {
              if ($allMenu['cuisine_name'] === $_REQUEST['category']) {
                ?>

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
                        $cartItems = $obj->getCartItems($uid);
                        $itemExists = false;
                        $itemQuantity = 0;

                        // Check if item exists in cart
                        foreach ($cartItems as $cartItem) {
                          if ($cartItem['item_id'] == $allMenu['item_id']) {
                            $itemExists = true;
                            $itemQuantity = $cartItem['quantity'];
                            break;
                          }
                        }

                        if ($itemExists) { ?>
                          <div class="flex items-center gap-3 rounded-full border border-white px-2 py-1 text-sm">

                            <a
                              href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $allMenu['restaurant_id']; ?>&updateCart=<?php echo $cartItem['cart_id']; ?>&item_id=<?php echo $allMenu['item_id']; ?>&action=decrease&quantity=<?php echo $itemQuantity - 1; ?>">
                              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="#eab308"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-minus text-yellow-500 cursor-pointer">
                                <path d="M5 12h14" />
                              </svg>
                            </a>
                            <span class="text-sm"><?php echo $itemQuantity; ?></span>
                            <a
                              href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $allMenu['restaurant_id']; ?>&updateCart=<?php echo $cartItem['cart_id']; ?>&item_id=<?php echo $allMenu['item_id']; ?>&action=increase&quantity=<?php echo $itemQuantity + 1; ?>">
                              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="#eab308"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-plus cursor-pointer text-yellow-500">
                                <path d="M5 12h14" />
                                <path d="M12 5v14" />
                              </svg>
                            </a>
                          </div>
                        <?php } else { ?>
                          <form
                            action="?id=<?php echo $allMenu['restaurant_id']; ?>&addtoCart=<?php echo $allMenu['item_id'] ?>&price=<?php echo $allMenu['price'] ?>"
                            method="POST">
                            <button type="submit" class="bg-yellow-500 text-black text-xs font-semibold px-4 p-1 rounded-md z-10">
                              Add to Cart
                            </button>
                          </form>
                        <?php } ?>
                      </div>

                    </div>

                  </div>

                  <!-- Discount Badge (Conditional) -->
                  <!-- <div class="absolute -right-12  top-0 bg-red-500 text-white px-12 py-1 rotate-45 transform shadow-lg text-xs font-bold">
        20% OFF
    </div> -->

                </div>

                <?php
              }
            }
          }
          ?>
        </div>
        <?php
      }
      ?>

      <!-- List of Resturants -->
      <div class="flex flex-col sm:px-4 px-2 py-6 gap-9 ">
        <h1 class=" text-xl font-bold md:text-4xl text-start">Top brands for you</h1>
        <!-- <form method="get"> -->
        <div class="flex flex-row gap-4  overflow-x-scroll overflow-y-hidden md:gap-8 noscorllRest  scroll-smooth">
          <?php
          foreach ($restaurants as $restaurant) {
            ?>
            <a href="restaurant.php?id=<?php echo $restaurant['restaurant_id'] ?>" name="id"
              value="<?php echo $restaurant['restaurant_id'] ?>" class="flex flex-col">
              <div class="flex flex-col flex-shrink-0 w-20 h-20 md:w-32 md:h-32">
                <img class="w-full h-full border-4 border-yellow-500 rounded-full"
                  src="../AdminPanel/<?php echo $restaurant['restaurant_pic'] ?>" />
              </div>
              <p class="z-10 my-3 font-sans  text-center text-sm sm:text-md"><?php echo $restaurant['name'] ?></p>
            </a>
            <?php
          }
          ?>
        </div>
        <!-- </form> -->
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2  md:grid-cols-3  lg:grid-cols-4  gap-6 px-4 sm:px-6 md:px-10">
        <?php
        foreach ($getAllMenuItems as $allMenu) {
          $id=$allMenu['restaurant_id'];
          ?>
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
                  $cartItems = $obj->getCartItems($uid);
                  $itemExists = false;
                  $itemQuantity = 0;

                  // Check if item exists in cart
                  foreach ($cartItems as $cartItem) {
                    if ($cartItem['item_id'] == $allMenu['item_id']) {
                      $itemExists = true;
                      $itemQuantity = $cartItem['quantity'];
                      break;
                    }
                  }

                  if ($itemExists) { ?>
                    <div class="flex items-center gap-3 rounded-full border border-white px-2 py-1 text-sm">

                      <a
                        href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $allMenu['restaurant_id']; ?>&updateCart=<?php echo $cartItem['cart_id']; ?>&item_id=<?php echo $allMenu['item_id']; ?>&action=decrease&quantity=<?php echo $itemQuantity - 1; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="#eab308"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          class="lucide lucide-minus text-yellow-500 cursor-pointer">
                          <path d="M5 12h14" />
                        </svg>
                      </a>
                      <span class="text-sm"><?php echo $itemQuantity; ?></span>
                      <a
                        href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $allMenu['restaurant_id']; ?>&updateCart=<?php echo $cartItem['cart_id']; ?>&item_id=<?php echo $allMenu['item_id']; ?>&action=increase&quantity=<?php echo $itemQuantity + 1; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="#eab308"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          class="lucide lucide-plus cursor-pointer text-yellow-500">
                          <path d="M5 12h14" />
                          <path d="M12 5v14" />
                        </svg>
                      </a>
                    </div>
                  <?php } else { ?>
                    <?php if ($allMenu['is_available'] != "0"): ?>
                    <form
                      action="?id=<?php echo $allMenu['restaurant_id']; ?>&addtoCart=<?php echo $allMenu['item_id'] ?>&price=<?php echo $allMenu['price'] ?>"
                      method="POST">
                      <button type="submit" class="bg-yellow-500 text-black text-xs font-semibold px-4 p-1 rounded-md z-10">
                        Add to Cart
                      </button>
                    </form>
                    <?php endif; ?>
                  <?php } ?>
                </div>

              </div>

            </div>

            <!-- Discount Badge (Conditional) -->
            <!-- <div class="absolute -right-12  top-0 bg-red-500 text-white px-12 py-1 rotate-45 transform shadow-lg text-xs font-bold">
        20% OFF
    </div> -->

          </div>
          <?php
        }
        ?>

      </div>



    </div>

    </div>
    <label class="main fixed bottom-0 right-0 m-4">
      Menu
      <input class="inp" checked="" type="checkbox" />
      <div class="bar">
        <span class="top bar-list"></span>
        <span class="middle bar-list"></span>
        <span class="bottom bar-list"></span>
      </div>
      <section class="menu-container">
        <div class="menu-list"><a href="menu.php">Menu</a></div>
        <div class="menu-list"><a href="cart.php">Cart</a></div>
        <div class="menu-list"><a href="orders_user.php">Past Orders</a></div>
        <div class="menu-list"><a href="account_user.php">Account</a></div>
      </section>
    </label>

  </main>


  <script>
  document.addEventListener("DOMContentLoaded", function () {
    const scrollContainers = document.querySelectorAll(".noscorll, .noscorllRest");

    scrollContainers.forEach((scrollContainer) => {
      if (scrollContainer) {
        scrollContainer.addEventListener("wheel", function (event) {
          if (event.deltaY !== 0) {
            event.preventDefault();
            scrollContainer.scrollLeft += event.deltaY;
          }
        });
      }
    });
  });
</script>


  <!-- Footer -->
  <?php require 'footer.php' ?>
</body>