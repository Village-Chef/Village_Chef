<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/lucide-icons/dist/umd/lucide.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .noscorll {
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
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-chef-hat">
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
                <a href="home.php" class="hover:text-yellow-500 <?php echo (isset($ActivePage) && $ActivePage == 'Home') ? 'text-yellow-500' : 'text-white'; ?>  transition-colors">Home</a>
                <a href="about.php" class="hover:text-yellow-500 <?php echo (isset($ActivePage) && $ActivePage == 'About') ? 'text-yellow-500' : 'text-white'; ?> transition-colors">About Us</a>
                <a href="menu.php" class="hover:text-yellow-500 <?php echo (isset($ActivePage) && $ActivePage == 'Menu') ? 'text-yellow-500' : 'text-white'; ?> transition-colors">Menu</a>
                <a href="contact.php" class="hover:text-yellow-500 <?php echo (isset($ActivePage) && $ActivePage == 'Contact') ? 'text-yellow-500' : 'text-white'; ?> transition-colors">Contact</a>
            </nav>

            <!-- Search + Cart  -->
            <div class="flex items-center gap-3">
                <button class="p-2 hover:text-yellow-500 transition-colors">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </button>
                <a href="cart.php">
                    <button class="p-2 hover:text-yellow-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-shopping-cart">
                            <circle cx="8" cy="21" r="1" />
                            <circle cx="19" cy="21" r="1" />
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                        </svg>
                    </button>
                </a>
                <a class="hidden md:flex" href="login.php">
                    <button
                        class="hidden md:flex items-center border border-yellow-500 text-yellow-500 hover:bg-yellow-500 hover:text-black px-4 py-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-log-in">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                            <polyline points="10 17 15 12 10 7" />
                            <line x1="15" x2="3" y1="12" y2="12" />
                        </svg>
                        Login
                    </button>
                </a>

                <!-- Menu Toggle -->
                <button id="mobile-menu-toggle" class="md:hidden p-2 hover:text-yellow-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu">
                        <line x1="4" x2="20" y1="12" y2="12" />
                        <line x1="4" x2="20" y1="6" y2="6" />
                        <line x1="4" x2="20" y1="18" y2="18" />
                    </svg>
                </button>

            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="mobile-menu md:hidden md:mt-4">
            <nav class="flex flex-col space-y-4 py-4 ">
                <a href="home.php" class="hover:text-yellow-500 <?php echo (isset($ActivePage) && $ActivePage == 'Home') ? 'text-yellow-500' : 'text-white'; ?>  transition-colors">Home</a>
                <a href="about.php" class="hover:text-yellow-500 <?php echo (isset($ActivePage) && $ActivePage == 'About') ? 'text-yellow-500' : 'text-white'; ?> transition-colors">About Us</a>
                <a href="menu.php" class="hover:text-yellow-500 <?php echo (isset($ActivePage) && $ActivePage == 'Menu') ? 'text-yellow-500' : 'text-white'; ?> transition-colors">Menu</a>
                <a href="contact.php" class="hover:text-yellow-500 <?php echo (isset($ActivePage) && $ActivePage == 'Contact') ? 'text-yellow-500' : 'text-white'; ?> transition-colors">Contact</a>
                <div class="flex flex-row gap-2">
                    <a class="md:hidden flex" href="login.php">
                        <button
                            class="md:hidden text-xs flex items-center border border-yellow-500 text-yellow-500 hover:bg-yellow-500 hover:text-black px-4 py-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-log-in">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                                <polyline points="10 17 15 12 10 7" />
                                <line x1="15" x2="3" y1="12" y2="12" />
                            </svg>
                            Login
                        </button>
                    </a>
                    <a class="md:hidden flex" href="signup.php">
                        <button
                            class="md:hidden flex text-xs items-center border border-yellow-500 text-yellow-500 hover:bg-yellow-500 hover:text-black px-4 py-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-log-in">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                                <polyline points="10 17 15 12 10 7" />
                                <line x1="15" x2="3" y1="12" y2="12" />
                            </svg>
                            Sign Up
                        </button>
                    </a>
                </div>

            </nav>
        </div>

    </header>

    <!-- Javascript for toggle -->
    <script>
        // Mobile menu toggle functionality
        document.getElementById('mobile-menu-toggle').addEventListener('click', function() {
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