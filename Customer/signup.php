<!DOCTYPE html>
<html lang="en" class="bg-black">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Village Chef - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/lucide-icons/dist/umd/lucide.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-black text-white flex flex-col">
    <!-- Header -->
    <header class=" py-4 lg:container mx-auto px-4 flex items-center justify-between">
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

        <nav class="hidden md:flex items-center space-x-8">
            <a href="home.php" class="hover:text-yellow-500 transition-colors">Home</a>
            <a href="about.php" class="hover:text-yellow-500 transition-colors">About Us</a>
            <a href="menu.php" class="hover:text-yellow-500 transition-colors">Menu</a>
            <a href="contact.php" class="hover:text-yellow-500 transition-colors">Contact</a>
        </nav>

        <div class="flex items-center space-x-4">
            <button class="p-2 hover:text-yellow-500 transition-colors">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-search">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
            </button>
            <button class="p-2 hover:text-yellow-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-shopping-cart">
                    <circle cx="8" cy="21" r="1" />
                    <circle cx="19" cy="21" r="1" />
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                </svg>
            </button>
            <a href="login.php">
            <button 
                class="hidden md:flex items-center border border-yellow-500 text-yellow-500 hover:bg-yellow-500 hover:text-black px-4 py-2 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-in"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
                Login
            </button>
            </a>
        </div>
    </header>
    <div class="">
        
    </div>
    <!-- Reg Section -->
    <main class="flex-grow flex items-center  justify-center px-4 py-12">
        <div class="bg-zinc-900 relative p-8 rounded-xl   shadow-lg max-w-md w-full">
            <h2 class="text-3xl font-bold mb-6 text-center">Create Your Account 🍕</h2>
            <form >
                <div class="mb-4">
                    <label for="fname" class="block text-sm font-medium text-gray-400 mb-2">First Name</label>
                    <input type="text" id="fname" name="fname"
                        class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
                        placeholder="First Name" required>
                </div>
                <div class="mb-4">
                    <label for="lname" class="block text-sm font-medium text-gray-400 mb-2">Last Name</label>
                    <input type="text" id="lname" name="lname"
                        class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
                        placeholder="Last Name" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email Address</label>
                    <input type="email" id="email" name="email"
                        class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
                        placeholder="example@email.com" required>
                </div>
                <div class="mb-4">
                    <label for="mobile" class="block text-sm font-medium text-gray-400 mb-2">Mobile Number</label>
                    <input type="number" maxlength="10" id="mobile" name="mobile"
                        class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
                        placeholder="+91 12345 12345" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-400 mb-2">Password</label>
                    <input type="password" id="password" name="password"
                        class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
                        placeholder="••••••••" required>
                </div>
                
                <div class="mb-6">
                    <label for="confirm-password" class="block text-sm font-medium text-gray-400 mb-2">Confirm
                        Password</label>
                    <input type="password" id="confirm-password" name="confirm-password"
                        class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
                        placeholder="••••••••" required>
                </div>
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" class="form-checkbox bg-zinc-800 border-zinc-700 text-yellow-500 rounded"
                            required>
                        <span class="ml-2 text-sm text-gray-400">I agree to the <a href="#"
                                class="text-yellow-500 hover:underline">Terms of Service</a> and <a href="#"
                                class="text-yellow-500 hover:underline">Privacy Policy</a></span>
                    </label>
                </div>
                <button type="submit"
                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-2 px-4 rounded-md transition-colors">
                    Sign Up
                </button>
            </form>
            <div class="mt-6 border-t border-zinc-800 pt-6 text-center">
                <p class="text-gray-400">Already have an account?</p>
                <a href="login.php" class="text-yellow-500 hover:underline font-medium">Log in here</a>
            </div>
        </div>
    </main>


    <!-- Add Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>