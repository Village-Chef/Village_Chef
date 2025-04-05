<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require '../dbCon.php';
$obj = new Foodies();
if (isset($_SESSION['user']['user_id'])) {
    $user = $_SESSION['user']['user_id'];
    $userdataNavbar = $obj->getUserById($user);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- <link href="https://unpkg.com/lucide-icons/dist/umd/lucide.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .noscorll {
            scrollbar-width: none;
        }

        .noscorllRest {
            scrollbar-width: none;
        }

        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .mobile-menu.open {
            max-height: 300px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <header class="w-[100%] mx-auto  bgNavbar backdrop-blur-lg bg-opacity-30 fixed z-50  py-4 px-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center">
                <div class="mr-2">
                    <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-black w-6 h-6" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chef-hat">
                            <path
                                d="M17 21a1 1 0 0 0 1-1v-5.35c0-.457.316-.844.727-1.041a4 4 0 0 0-2.134-7.589 5 5 0 0 0-9.186 0 4 4 0 0 0-2.134 7.588c.411.198.727.585.727 1.041V20a1 1 0 0 0 1 1Z" />
                            <path d="M6 17h12" />
                        </svg>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-yellow-500 font-bold italic text-xl leading-none">Village</span>
                    <span class="font-bold text-xl leading-none">CHEF</span>
                </div>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center space-x-8">
                <?php
                if (isset($_SESSION['user'])) {
                    ?>
                    <!-- <a href="menu.php"
                        class="hover:text-yellow-500 <?php echo (isset($ActivePage) && $ActivePage == 'Menu') ? 'text-yellow-500' : 'text-white'; ?> transition-colors">Menu</a> -->
                    <?php
                } else {
                    ?>
                    <a href="home.php"
                        class="hover:text-yellow-500 <?php echo (isset($ActivePage) && $ActivePage == 'Home') ? 'text-yellow-500' : 'text-white'; ?>  transition-colors">Home</a>
                    <a href="about.php"
                        class="hover:text-yellow-500 <?php echo (isset($ActivePage) && $ActivePage == 'About') ? 'text-yellow-500' : 'text-white'; ?> transition-colors">About
                        Us</a>
                    <!-- <a href="menu.php"
                        class="hover:text-yellow-500 <?php echo (isset($ActivePage) && $ActivePage == 'Menu') ? 'text-yellow-500' : 'text-white'; ?> transition-colors">Menu</a> -->
                    <a href="contact.php"
                        class="hover:text-yellow-500 <?php echo (isset($ActivePage) && $ActivePage == 'Contact') ? 'text-yellow-500' : 'text-white'; ?> transition-colors">Contact</a>

                    <?php
                }
                ?>
            </nav>

            <!-- Search + Cart  -->
            <div class="flex items-center sm:gap-3">
                <a href="search.php">
                <button class="p-2 hover:text-yellow-500 transition-colors">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-search">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </button>
                </a>
                <a href="menu.php">
                    <button
                        class="p-2 <?php echo (isset($ActivePage) && $ActivePage == 'menu') ? 'text-yellow-500' : 'text-white'; ?> transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-utensils-crossed-icon lucide-utensils-crossed">
                            <path d="m16 2-2.3 2.3a3 3 0 0 0 0 4.2l1.8 1.8a3 3 0 0 0 4.2 0L22 8" />
                            <path d="M15 15 3.3 3.3a4.2 4.2 0 0 0 0 6l7.3 7.3c.7.7 2 .7 2.8 0L15 15Zm0 0 7 7" />
                            <path d="m2.1 21.8 6.4-6.3" />
                            <path d="m19 5-7 7" />
                        </svg>
                    </button>
                </a>
                <!-- <a href="orders_user.php">
                    <button class="p-2 hover:text-yellow-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-shopping-bag-icon lucide-shopping-bag">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                            <path d="M3 6h18" />
                            <path d="M16 10a4 4 0 0 1-8 0" />
                        </svg>
                    </button>
                </a> -->
                <a href="cart.php" class="me-1">
                    <button
                        class="p-2 <?php echo (isset($ActivePage) && $ActivePage == 'cart') ? 'text-yellow-500' : 'hover:text-yellow-500'; ?> transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 " width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart">
                            <circle cx="8" cy="21" r="1" />
                            <circle cx="19" cy="21" r="1" />
                            <path
                                d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                        </svg>
                    </button>
                </a>

                <?php if (isset($_SESSION['user'])): ?>
                    <!-- <a href="logout.php">
                        <button
                            class="hidden md:flex items-center border border-yellow-500 text-yellow-500 hover:bg-yellow-500 hover:text-black px-4 py-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" x2="9" y1="12" y2="12" />
                            </svg>
                            Logout
                        </button>
                    </a> -->
                    <a href="account_user.php">
                        <button
                            class="flex items-center justify-center border border-yellow-500 text-yellow-500  hover:bg-yellow-500 hover:text-black px-4 py-2 rounded-full w-full">
                            <i class="fa-solid fa-user sm:mr-2"></i>
                            <div class="sm:inline hidden">
                                <?php echo $userdataNavbar['first_name'] . " " . $userdataNavbar['last_name']; ?>
                            </div>
                        </button>
                    </a>
                    </button>
                <?php else: ?>
                    <a href="login.php">
                        <button
                            class="hidden md:flex items-center border border-yellow-500 text-yellow-500 hover:bg-yellow-500 hover:text-black px-4 py-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-in">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                                <polyline points="10 17 15 12 10 7" />
                                <line x1="15" x2="3" y1="12" y2="12" />
                            </svg>
                            Login
                        </button>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="mobile-menu md:hidden md:mt-4">
            <nav class="flex flex-col space-y-4 py-4 ">
                <a href="home.php"
                    class="hover:text-yellow-500 <?php echo (isset($ActivePage) && $ActivePage == 'Home') ? 'text-yellow-500' : 'text-white'; ?>  transition-colors">Home</a>
                <a href="about.php"
                    class="hover:text-yellow-500 <?php echo (isset($ActivePage) && $ActivePage == 'About') ? 'text-yellow-500' : 'text-white'; ?> transition-colors">About
                    Us</a>
                <a href="menu.php"
                    class="hover:text-yellow-500 <?php echo (isset($ActivePage) && $ActivePage == 'Menu') ? 'text-yellow-500' : 'text-white'; ?> transition-colors">Menu</a>
                <a href="contact.php"
                    class="hover:text-yellow-500 <?php echo (isset($ActivePage) && $ActivePage == 'Contact') ? 'text-yellow-500' : 'text-white'; ?> transition-colors">Contact</a>
                <div class="flex flex-row gap-2">
                    <?php if (isset($_SESSION['user'])): ?>
                        <a href="logout.php">
                            <button
                                class="hidden md:flex items-center border border-yellow-500 text-yellow-500 hover:bg-yellow-500 hover:text-black px-4 py-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                    <polyline points="16 17 21 12 16 7" />
                                    <line x1="21" x2="9" y1="12" y2="12" />
                                </svg>
                                Logout
                            </button>
                        </a>
                    <?php else: ?>
                        <a href="login.php">
                            <button
                                class="hidden md:flex items-center border border-yellow-500 text-yellow-500 hover:bg-yellow-500 hover:text-black px-4 py-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-in">
                                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                                    <polyline points="10 17 15 12 10 7" />
                                    <line x1="15" x2="3" y1="12" y2="12" />
                                </svg>
                                Login
                            </button>
                        </a>
                    <?php endif; ?>
                </div>

            </nav>
        </div>

    </header>

    <!-- Javascript for toggle -->
    <script>
        // Mobile menu toggle functionality
        document.getElementById('mobile-menu-toggle').addEventListener('click', function () {
            document.getElementById('mobile-menu').classList.toggle('open');

            // Change icon between bars and times (x)
            // const icon = this.querySelector('i');
            // if (icon.classList.contains('fa-bars')) {
            //   icon.classList.remove('fa-bars');
            //   icon.classList.add('fa-times');
            // } else {
            //   icon.classList.remove('fa-times');
            //   icon.classList.add('fa-bars');
            // }
        });
    </script>

    <!-- Add Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>