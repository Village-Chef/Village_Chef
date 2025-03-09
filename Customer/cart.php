<body class="min-h-screen bg-black text-white ">

    <!-- Navbar -->
    <?php require 'navbar.php' ?>

    <main class="flex-grow">
        <div class="max-w-4xl mx-auto px-4 py-12">
            <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center">Your Cart</h1>

            <div class="bg-zinc-900 rounded-lg p-6 mb-8">
                <div class="space-y-4">
                    <!-- Cart Item -->
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

                    <!-- Add more cart items here -->

                </div>

                <div class="mt-8 flex justify-between items-center">
                    <p class="text-xl font-bold">Total:</p>
                    <p class="text-2xl font-bold text-yellow-500">$22.99</p>
                </div>
            </div>

            <div class="bg-zinc-900 rounded-lg p-6">
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
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php require 'footer.php' ?>
</body>

</html>