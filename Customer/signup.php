<body class="min-h-screen bg-black text-white flex flex-col">

    <!-- Navbar -->
    <?php require 'navbar.php' ?>

    <!-- Reg Section -->
    <main class="flex-grow flex items-center  justify-center px-4 py-12">
        <div class="bg-zinc-900 relative p-8 rounded-xl   shadow-lg max-w-md w-full">
            <h2 class="text-3xl font-bold mb-6 text-center">Create Your Account 🍕</h2>
            <form>
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

    <!-- Footer -->
    <?php require 'footer.php' ?>
</body>