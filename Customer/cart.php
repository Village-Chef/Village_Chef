<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<style>
    .razorpay-container .razorpay-testmode-banner {
        display: none !important;
    }
</style>

<?php
$apiKey = "rzp_test_FFm35IphRdzhve"; // Replace with your Test Key
?>

<?php
if (isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];
}
?>


<?php
require '../dbCon.php';
$obj = new Foodies();
$tip = 0;
$total = 0;
$DelFee = 22;
$PlatformFee = 6;
$Gst = ($total + $tip + $DelFee + $PlatformFee); // Calculate GST as 18% of the total amount
$GrandTotal = $total + $DelFee + $PlatformFee + $tip + $Gst;

if (isset($_GET['tip'])) {
    $tip = $_GET['tip'];
}

if (isset($_GET['address'])) {
    $address = $_GET['address'];
}

?>
<?php
// Retrieve data from $_GET
if (isset($_GET['cart_id']) && isset($_GET['item_id']) && isset($_GET['quantity'])) {
    $cart_id = $_GET['cart_id']; // Get the cart ID
    $item_id = $_GET['item_id']; // Get the item ID
    $quantity = intval($_GET['quantity']); // Convert to integer to avoid issues


    if ($quantity <= 0) {
        // Delete the item from cart if quantity is 0 or negative
        $obj->deleteCartItem($cart_id, $item_id);
    } else {
        // Update quantity if it's 1 or more
        $obj->updateCartItemQuantity($cart_id, $item_id, $quantity);
    }
    header('Location: cart.php');
    exit();
}
if (isset($_GET['address'])) {
    $obj->updateUserAddress($uid, $_GET['address']);
    header("Location:cart.php");
}
?>

<style>
    /* From Uiverse.io by fthisilak */
    .pay-btn {
        position: relative;
        padding: 8px 16px;
        font-size: 16px;
        background: transparent;
        color: #eab308;
        border: 1px solid #eab308;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .pay-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.4);
    }

    .icon-container {
        position: relative;
        width: 24px;
        height: 24px;
    }

    .icon {
        position: absolute;
        top: 0;
        left: 0;
        width: 24px;
        height: 24px;
        color: #eab308;
        opacity: 0;
        visibility: hidden;
    }

    .default-icon {
        opacity: 1;
        visibility: visible;
    }

    /* Hover animations */
    .pay-btn:hover .icon {
        animation: none;
    }

    .pay-btn:hover .wallet-icon {
        opacity: 0;
        visibility: hidden;
    }

    .pay-btn:hover .card-icon {
        animation: iconRotate 2.5s infinite;
        animation-delay: 0s;
    }

    .pay-btn:hover .payment-icon {
        animation: iconRotate 2.5s infinite;
        animation-delay: 0.5s;
    }

    .pay-btn:hover .dollar-icon {
        animation: iconRotate 2.5s infinite;
        animation-delay: 1s;
    }

    .pay-btn:hover .check-icon {
        animation: iconRotate 2.5s infinite;
        animation-delay: 1.5s;
    }

    /* Active state - show only checkmark */
    .pay-btn:active .icon {
        animation: none;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .pay-btn:active .check-icon {
        animation: checkmarkAppear 0.6s ease forwards;
        visibility: visible;
    }

    .btn-text {
        font-weight: 600;
        font-family:
            system-ui,
            -apple-system,
            sans-serif;
    }

    @keyframes iconRotate {
        0% {
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px) scale(0.5);
        }

        5% {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }

        15% {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }

        20% {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px) scale(0.5);
        }

        100% {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px) scale(0.5);
        }
    }

    @keyframes checkmarkAppear {
        0% {
            opacity: 0;
            transform: scale(0.5) rotate(-45deg);
        }

        50% {
            opacity: 0.5;
            transform: scale(1.2) rotate(0deg);
        }

        100% {
            opacity: 1;
            transform: scale(1) rotate(0deg);
        }
    }
</style>

<body class="min-h-screen bg-black text-white ">

    <!-- Navbar -->
    <?php require 'navbar.php' ?>

    <?php
    $uid = $_SESSION['user']['user_id'];
    $cartItems = $obj->getCartItems($uid);
    $currentUser = $obj->getUserById($uid);
    ?>


    <main class="flex-grow pt-12">
        <div class="max-w-4xl mx-auto px-4 py-12">
            <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center py-4">Checkout</h1>

            <!-- <div class="bg-zinc-900 rounded-lg p-6 mb-8">
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-zinc-700 pb-4">
                        <div class="flex items-center">
                            <img src="https://placehold.co/80x80" alt="Grilled Salmon"
                                class="w-20 h-20 object-cover rounded-md mr-4">
                            <div>
                                <h3 class="font-bold">Grilled Salmon bhai</h3>
                                <p class="text-gray-400">Quantity: 1</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-yellow-500">$22.99</p>
                            <button class="text-red-500 hover:text-red-600 transition-colors">Remove</button>
                        </div>
                    </div>


                </div> -->

            <div class="flex w-full flex-row gap-6 rounded-lg">

                <!-- Right Side Process -->
                <div class="w-[100%]  p-4">
                    <div class="max-w-2xl mx-auto">
                        <!-- Logged In Section -->
                        <div class="bg-white shadow-lg rounded-lg p-6 border border-gray-200">
                            <div class="flex items-center space-x-4">
                                <div class="bg-black text-white p-3 rounded-lg">
                                    <svg class="w-6 h-6" fill="white" viewBox="0 0 24 24">
                                        <path
                                            d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-yellow-500 text-lg">Logged in <span
                                            class="text-green-500">✔</span></p>
                                    <p class="text-gray-700 font-medium">Purv Virpariya | 8849019459</p>
                                </div>
                            </div>
                        </div>

                        <!-- Add Delivery Address Section -->
                        <div class="bg-white shadow-lg rounded-lg p-6 mt-4 border border-gray-200">
                            <div class="flex items-center space-x-4">
                                <div class="bg-black text-white p-3 rounded-lg">
                                    <svg class="w-6 h-6" fill="white" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2C8.13 2 5 5.13 5 9c0 3.87 7 13 7 13s7-9.13 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-yellow-500 text-lg">
                                        <?php echo (isset($_GET['address']) ? 'Your delivery address <span class="text-green-500"> ✔</span>' : 'Add a delivery address') ?>
                                    </p>
                                    <p class=" text-gray-700">You seem to be in the new location</p>
                                </div>
                            </div>

                            <!-- Address Box -->
                            <div class="border border-gray-300 rounded-lg p-6 mt-4">
                                <?php
                                if (isset($_GET['address'])) {
                                    $obj->updateUserAddress($uid, $_GET['address']);
                                    header("Location:cart.php");
                                }
                                if ($currentUser['address'] != null) {

                                    ?>
                                    <p class="text-gray-700 font-medium"><?php echo $currentUser['address'] ?></p>
                                    <?php
                                }
                                ?>

                                <?php
                                if ($currentUser['address'] != null) {
                                    ?>

                                    <?php
                                } else {
                                    ?>
                                    <p class="font-semibold text-green-500 text-lg flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 2C8.13 2 5 5.13 5 9c0 3.87 7 13 7 13s7-9.13 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                                        </svg>
                                        Add New Address
                                    </p>

                                    <?php
                                }

                                ?>
                                <button onclick="addAddress()"
                                    class="mt-4 px-6 py-2 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600">
                                    <?php echo 'ADD NEW'; ?>
                                </button>


                            </div>
                        </div>

                        <!-- Payment -->
                        <?php
                        if ($currentUser['address'] != null) {
                            ?>
                            <div class="bg-white shadow-lg rounded-lg p-6 mt-4 border border-gray-200">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-black text-white p-3 rounded-lg">
                                        <svg class="w-6 h-6" fill="white" viewBox="0 0 24 24">
                                            <path
                                                d="M2 5C2 3.9 2.9 3 4 3H20C21.1 3 22 3.9 22 5V19C22 20.1 21.1 21 20 21H4C2.9 21 2 20.1 2 19V5ZM4 7V9H20V7H4ZM4 17H20V11H4V17Z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-yellow-500 text-lg">Choose payment method
                                            <!-- <span  class="text-green-500">✔</span> -->
                                        </p>
                                        <div class="flex  mt-3">
                                            <button id="pay-btn" class="pay-btn">
                                                <span class="btn-text">Checkout</span>
                                                <div class="icon-container">
                                                    <svg viewBox="0 0 24 24" class="icon card-icon">
                                                        <path
                                                            d="M20,8H4V6H20M20,18H4V12H20M20,4H4C2.89,4 2,4.89 2,6V18C2,19.11 2.89,20 4,20H20C21.11,20 22,19.11 22,18V6C22,4.89 21.11,4 20,4Z"
                                                            fill="currentColor"></path>
                                                    </svg>
                                                    <svg viewBox="0 0 24 24" class="icon payment-icon">
                                                        <path
                                                            d="M2,17H22V21H2V17M6.25,7H9V6H6V3H18V6H15V7H17.75L19,17H5L6.25,7M9,10H15V8H9V10M9,13H15V11H9V13Z"
                                                            fill="currentColor"></path>
                                                    </svg>
                                                    <svg viewBox="0 0 24 24" class="icon dollar-icon">
                                                        <path
                                                            d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"
                                                            fill="currentColor"></path>
                                                    </svg>

                                                    <svg viewBox="0 0 24 24" class="icon wallet-icon default-icon">
                                                        <path
                                                            d="M21,18V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H19A2,2 0 0,1 21,5V6H12C10.89,6 10,6.9 10,8V16A2,2 0 0,0 12,18M12,16H22V8H12M16,13.5A1.5,1.5 0 0,1 14.5,12A1.5,1.5 0 0,1 16,10.5A1.5,1.5 0 0,1 17.5,12A1.5,1.5 0 0,1 16,13.5Z"
                                                            fill="currentColor"></path>
                                                    </svg>

                                                    <svg viewBox="0 0 24 24" class="icon check-icon">
                                                        <path d="M9,16.17L4.83,12L3.41,13.41L9,19L21,7L19.59,5.59L9,16.17Z"
                                                            fill="currentColor"></path>
                                                    </svg>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>

                <!-- Left Side Bill -->
                <div class="flex w-fit flex-col gap-4 bg-zinc-900 ">

                    <!-- Zig Zag Bar -->
                    <div class="relative bg-yellow-300  rotate-180 ">
                        <svg class="absolute top-0 left-0 h-fit" viewBox="0 0 1440 100" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill="#fde047"
                                d="M0,100 L30,50 L60,100 L90,50 L120,100 L150,50 L180,100 L210,50 L240,100 L270,50 L300,100 L330,50 L360,100 L390,50 L420,100 L450,50 L480,100 L510,50 L540,100 L570,50 L600,100 L630,50 L660,100 L690,50 L720,100 L750,50 L780,100 L810,50 L840,100 L870,50 L900,100 L930,50 L960,100 L990,50 L1020,100 L1050,50 L1080,100 L1110,50 L1140,100 L1170,50 L1200,100 L1230,50 L1260,100 L1290,50 L1320,100 L1350,50 L1380,100 L1410,50 L1440,100 V0 H0 Z">
                            </path>
                        </svg>
                    </div>

                    <div class=" p-2 rounded-lg shadow-md w-96">

                        <!-- Cart Items List -->
                        <div
                            class="flex items-center flex-col  w-full justify-between gap-2 mb-4 rounded-lg  text-white">


                            <?php
                            foreach ($cartItems as $cartItem) {
                                $total += (is_numeric($cartItem['price']) && is_numeric($cartItem['quantity']))
                                    ? intval($cartItem['price']) * intval($cartItem['quantity'])
                                    : 0;

                                ?>
                                <div class="flex items-center justify-between w-full mb-4 rounded-lg text-white">
                                    <!-- Stock Indicator -->
                                    <div
                                        class="flex h-4 w-4 items-center justify-center border border-green-600 p-0.5 rounded-full">
                                        <div class="h-2 w-2 bg-green-600 rounded-full"></div>
                                    </div>

                                    <!-- Item Name -->
                                    <div class="text-sm truncate max-w-[150px] text-start">
                                        <?php echo htmlspecialchars($cartItem['item_name']); ?>
                                    </div>




                                    <!-- Quantity Selector -->
                                    <div
                                        class="flex mb-0 items-center gap-3 rounded-full border border-white px-3 py-1 text-sm">
                                        <!-- <form method="get" action="cart.php"
                                            class="flex mb-0 items-center gap-3 rounded-full border border-white px-3 py-1 text-sm">
                                            <input type="hidden" name="cart_id" value="<?php echo $cartItem['cart_id']; ?>">
                                            <input type="hidden" name="item_id" value="<?php echo $cartItem['item_id']; ?>"> -->

                                        <a href="cart.php?cart_id=<?php echo $cartItem['cart_id']; ?>&item_id=<?php echo $cartItem['item_id']; ?>&quantity=<?php echo $cartItem['quantity'] - 1; ?>"
                                            class="cursor-pointer hover:text-red-400 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14" />
                                            </svg>
                                        </a>

                                        <span class="text-xs"><?php echo $cartItem['quantity']; ?></span>

                                        <a href="cart.php?cart_id=<?php echo $cartItem['cart_id']; ?>&item_id=<?php echo $cartItem['item_id']; ?>&quantity=<?php echo $cartItem['quantity'] + 1; ?>"
                                            class="cursor-pointer hover:text-green-400 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14" />
                                                <path d="M12 5v14" />
                                            </svg>
                                        </a>
                                        <!-- </form> -->
                                    </div>

                                    <!-- Price -->
                                    <span class="text-yellow-500 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M6 3h12" />
                                            <path d="M6 8h12" />
                                            <path d="m6 13 8.5 8" />
                                            <path d="M6 13h3" />
                                            <path d="M9 13c6.667 0 6.667-10 0-10" />
                                        </svg>
                                        <p><?php echo number_format($cartItem['price'] * $cartItem['quantity'], 0); ?></p>
                                    </span>
                                </div>


                                <?php
                            }
                            ?>
                        </div>

                        <!-- Any suggestions -->
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M18 10c0 4.418-3.582 8-8 8s-8-3.582-8-8 3.582-8 8-8 8 3.582 8 8zm-9 4h2v2H9v-2zm0-8h2v6H9V6z" />
                            </svg>
                            <input type="text"
                                class="text-yellow-500 w-full outline-none bg-transparent text-sm placeholder:text-white"
                                placeholder="Any suggestions? We will pass it on..." />
                        </div>

                        <!-- No Contact Delivery -->
                        <div class="border border-yellow-500 p-3 rounded-md flex items-start gap-2">
                            <input type="checkbox" class="mt-1 text-yellow-500">
                            <div>
                                <p class="font-semibold">Opt in for No-contact Delivery</p>
                                <p class="text-sm text-gray-500">Unwell, or avoiding contact? Please select no-contact
                                    delivery. Partner will safely place the order outside your door (not for COD).</p>
                            </div>
                        </div>

                        <!-- Bill -->
                        <div class="mt-4 text-sm">
                            <div class="flex justify-between py-2">
                                <span class="text-white">Item Total</span>
                                <span class="font-semibold">₹<?php echo $total ?></span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-white">Delivery Fee | 1.7 kms</span>
                                <span class="font-semibold">₹<?php echo $DelFee ?></span>
                            </div>
                            <div class="flex justify-between py-2 text-yellow-500">
                                <span>Delivery Tip</span>
                                <div class="flex flex-row  gap-2">
                                    <a href="?tip=20"><span
                                            class="px-3 py-1 cursor-pointer border border-yellow-500 <?php echo ($tip == 20) ? 'bg-yellow-500' : ''; ?> text-white rounded-full">20</span></a>
                                    <a href="?tip=30"><span
                                            class="px-3 py-1 cursor-pointer border border-yellow-500 <?php echo ($tip == 30) ? ' bg-yellow-500 ' : ''; ?> text-white rounded-full">30</span></a>
                                </div>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-white">Platform fee</span>
                                <div class="flex flex-row gap-2">
                                    <span class="font-semibold line-through text-gray-400">₹10.00</span>
                                    <span class="font-semibold">₹<?php echo $PlatformFee ?></span>
                                </div>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-white">GST and Restaurant Charges</span>
                                <span class="font-semibold">₹<?php $Gst = number_format($total * 0.09, 2);
                                echo number_format($total * 0.09, 2); ?>
                                </span>
                            </div>
                            <hr class="my-2">
                            <div class="flex justify-between text-lg font-bold">
                                <span>TO PAY</span>
                                <span>₹<?php echo $Gst + $PlatformFee + $DelFee + $tip + $total; ?></span>
                            </div>
                        </div>

                    </div>

                    <!-- Zig Zag Bar -->
                    <div class="relative bg-yellow-300  ">
                        <svg class="absolute top-0 left-0 h-fit" viewBox="0 0 1440 100" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill="#fde047"
                                d="M0,100 L30,50 L60,100 L90,50 L120,100 L150,50 L180,100 L210,50 L240,100 L270,50 L300,100 L330,50 L360,100 L390,50 L420,100 L450,50 L480,100 L510,50 L540,100 L570,50 L600,100 L630,50 L660,100 L690,50 L720,100 L750,50 L780,100 L810,50 L840,100 L870,50 L900,100 L930,50 L960,100 L990,50 L1020,100 L1050,50 L1080,100 L1110,50 L1140,100 L1170,50 L1200,100 L1230,50 L1260,100 L1290,50 L1320,100 L1350,50 L1380,100 L1410,50 L1440,100 V0 H0 Z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- <div class="mt-8 flex justify-between items-center">
                    <p class="text-xl font-bold">Total:</p>
                    <p class="text-2xl font-bold text-yellow-500">$22.99</p>
                </div> -->
        </div>

        <!-- <div class="bg-zinc-900 rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Checkout</h2>
                <form>
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-400 mb-2">Full Name</label>
                        <input type="text" id="name" name="name"
                            class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email Address</label>
                        <input type="email" id="email" name="email"
                            class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-400 mb-2">Delivery
                            Address</label>
                        <textarea id="address" name="address" rows="3"
                            class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
                            required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="card" class="block text-sm font-medium text-gray-400 mb-2">Card Number</label>
                        <input type="text" id="card" name="card"
                            class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
                            placeholder="1234 5678 9012 3456" required>
                    </div>
                    <button type="submit"
                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-2 px-4 rounded-md transition-colors">
                        Place Order
                    </button>
                </form>
            </div> -->
        </div>
    </main>

    <!-- Footer -->
    <?php require 'footer.php' ?>


    <!-- Add Address Modal -->
    <script>
        function addAddress() {
            Swal.fire({
                html: `
                        <form method="get" >   
                        <div class="w-full max-w-xs mx-auto gap-2 flex flex-col p-5 bg-white rounded-lg font-mono">
                                                 
                          <label class="block text-gray-700 text-md font-bold mb-2 text-start" for="unique-input"
                            >Enter Your Address</label
                          >
                          <input
                            class="text-sm custom-input w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm transition duration-300 ease-in-out transform focus:-translate-y-1 focus:outline-blue-300 hover:shadow-lg hover:border-blue-300 bg-gray-100"
                            placeholder="Enter street address"
                            name="address"
                            type="text"
                            id="unique-input"
                          />
                            <button type="submit" class="cursor-pointer transition-all 
                            bg-black text-white px-6 py-2 rounded-lg
                            border-yellow-500
                            border-b-[4px] hover:brightness-110 hover:-translate-y-[1px] hover:border-b-[6px]
                            active:border-b-[2px] active:brightness-90 active:translate-y-[2px] hover:shadow-md hover:shadow-yellow-500 shadow-yellow-500 active:shadow-none">
                              Add
                            </button>
                        
                        </div> 
                        </form>
                         `,
                inputAttributes: {
                    autocapitalize: "off"
                },
                showCancelButton: false,
                confirmButtonText: false,
                showConfirmButton: false,
                showLoaderOnConfirm: false,
            });
        }
    </script>


    <!-- Razpr Pay -->
    <script>
        var options = {
            "key": "<?php echo $apiKey; ?>",
            "amount": <?php $totalMain = ($Gst + $PlatformFee + $DelFee + $tip + $total);
            echo ($totalMain * 100); ?>, // Amount in paise (₹500)
            "currency": "INR",
            "name": "Demo Payment",
            "description": "Testing Razorpay",
            "image": "https://your-logo-url.com/logo.png",
            "handler": function (response) {
                alert("Payment Successful! Payment ID: " + response.razorpay_payment_id);
                window.location.href = "cart.php?payment_id=" + response.razorpay_payment_id;
            },
            "prefill": {
                "name": "John Doe",
                "email": "john@example.com",
                "contact": "9876543210"
            },
            "theme": {
                "color": "#3399cc"
            }
        };

        document.getElementById("pay-btn").onclick = function () {
            var rzp1 = new Razorpay(options);
            rzp1.open();
        };
    </script>


    <!-- Razor Pay -->
    <?php
    require '../vendor/autoload.php'; // Include Razorpay SDK
    
    use Razorpay\Api\Api;

    $keyId = "rzp_test_FFm35IphRdzhve";
    $keySecret = "eJVbPm8ip9aCGnHi8pWOuFnw";

    $api = new Api($keyId, $keySecret);

    $order = $api->order->create([
        'amount' => $totalMain * 100,  // ₹500 (Amount in paise)
        'currency' => 'INR',
        'payment_capture' => 1 // Auto capture payment
    ]);

    echo json_encode(['orderId' => $order['id']]);

    ?>

</body>

</html>