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

  <!-- Menu Section -->
  <main class="flex-grow">
    <div class="max-w-7xl mx-auto px-4 py-12">
      <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center">Our Menu</h1>
      
      <div class="mb-12">
        <h2 class="text-2xl font-bold mb-4">Appetizers</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div class="bg-zinc-900 rounded-lg overflow-hidden">
            <img src="https://placehold.co/300x200" alt="Bruschetta" class="w-full h-48 object-cover">
            <div class="p-4">
              <h3 class="font-bold text-lg mb-2">Bruschetta</h3>
              <p class="text-gray-400 mb-4">Toasted bread topped with fresh tomatoes, garlic, and basil.</p>
              <div class="flex justify-between items-center">
                <span class="text-yellow-500 font-bold">$8.99</span>
                <button class="bg-yellow-500 text-black px-4 py-2 rounded-full hover:bg-yellow-600 transition-colors">
                  Add to Cart
                </button>
              </div>
            </div>
          </div>
          <!-- Add more appetizer items here -->
        </div>
      </div>

      <div class="mb-12">
        <h2 class="text-2xl font-bold mb-4">Main Courses</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div class="bg-zinc-900 rounded-lg overflow-hidden">
            <img src="https://placehold.co/300x200" alt="Grilled Salmon" class="w-full h-48 object-cover">
            <div class="p-4">
              <h3 class="font-bold text-lg mb-2">Grilled Salmon</h3>
              <p class="text-gray-400 mb-4">Fresh salmon fillet grilled to perfection, served with seasonal vegetables.</p>
              <div class="flex justify-between items-center">
                <span class="text-yellow-500 font-bold">$22.99</span>
                <button class="bg-yellow-500 text-black px-4 py-2 rounded-full hover:bg-yellow-600 transition-colors">
                  Add to Cart
                </button>
              </div>
            </div>
          </div>
          <!-- Add more main course items here -->
        </div>
      </div>

      <div class="mb-12">
        <h2 class="text-2xl font-bold mb-4">Desserts</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div class="bg-zinc-900 rounded-lg overflow-hidden">
            <img src="https://placehold.co/300x200" alt="Tiramisu" class="w-full h-48 object-cover">
            <div class="p-4">
              <h3 class="font-bold text-lg mb-2">Tiramisu</h3>
              <p class="text-gray-400 mb-4">Classic Italian dessert with layers of coffee-soaked ladyfingers and mascarpone cream.</p>
              <div class="flex justify-between items-center">
                <span class="text-yellow-500 font-bold">$9.99</span>
                <button class="bg-yellow-500 text-black px-4 py-2 rounded-full hover:bg-yellow-600 transition-colors">
                  Add to Cart
                </button>
              </div>
            </div>
          </div>
          <!-- Add more dessert items here -->
        </div>
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