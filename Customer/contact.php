<body class=" min-h-screen text-white bg-black">

  <!-- Navbar -->
  <?php 
  $ActivePage="Contact";
  require 'navbar.php' ?>

  <!-- Contact Section -->
  <main class="flex-grow pt-12">
    <div class="max-w-4xl px-4 py-12 mx-auto">
      <h1 class="mb-8 text-4xl font-bold text-center md:text-5xl">Contact Us</h1>

      <div class="grid grid-cols-1 gap-12 md:grid-cols-2">
        <div>
          <h2 class="mb-4 text-2xl font-bold">Get in Touch</h2>
          <form>
            <div class="mb-4">
              <label for="name" class="block mb-2 text-sm font-medium text-gray-400">Your Name</label>
              <input
                type="text"
                id="name"
                name="name"
                class="w-full px-4 py-2 text-white border rounded-md bg-zinc-800 border-zinc-700 focus:border-yellow-500"
                required>
            </div>
            <div class="mb-4">
              <label for="email" class="block mb-2 text-sm font-medium text-gray-400">Email Address</label>
              <input
                type="email"
                id="email"
                name="email"
                class="w-full px-4 py-2 text-white border rounded-md bg-zinc-800 border-zinc-700 focus:border-yellow-500"
                required>
            </div>
            <div class="mb-4">
              <label for="message" class="block mb-2 text-sm font-medium text-gray-400">Your Message</label>
              <textarea
                id="message"
                name="message"
                rows="4"
                class="w-full px-4 py-2 text-white border rounded-md bg-zinc-800 border-zinc-700 focus:border-yellow-500"
                required></textarea>
            </div>
            <button
              type="submit"
              class="w-full px-4 py-2 font-bold text-black transition-colors bg-yellow-500 rounded-md hover:bg-yellow-600">
              Send Message
            </button>
          </form>
        </div>

        <div>
          <h2 class="mb-4 text-2xl font-bold">Contact Information</h2>
          <div class="space-y-4">
            <div class="flex items-start">
              <i class="w-5 h-5 mt-1 mr-3 text-yellow-500 lucide-map-pin"></i>
              <p>201 Ashirward Hostel</p>
            </div>
            <div class="flex items-start">
              <i class="w-5 h-5 mt-1 mr-3 text-yellow-500 lucide-phone"></i>
              <p>+91 12345 67890</p>
            </div>
            <div class="flex items-start">
              <i class="w-5 h-5 mt-1 mr-3 text-yellow-500 lucide-mail"></i>
              <p>villagechef@gmailcom</p>
            </div>
            <div class="flex items-start">
              <i class="w-5 h-5 mt-1 mr-3 text-yellow-500 lucide-clock"></i>
              <p>Mon-Sun: 10:00 AM - 10:00 PM</p>
            </div>
          </div>

          <!-- <div class="mt-8">
            <h3 class="mb-4 text-xl font-bold">Follow Us</h3>
            <div class="flex space-x-4">
              <a href="#" class="text-gray-400 transition-colors hover:text-yellow-500">
                <i class="w-6 h-6 lucide-facebook"></i>
              </a>
              <a href="#" class="text-gray-400 transition-colors hover:text-yellow-500">
                <i class="w-6 h-6 lucide-twitter"></i>
              </a>
              <a href="#" class="text-gray-400 transition-colors hover:text-yellow-500">
                <i class="w-6 h-6 lucide-instagram"></i>
              </a>
              <a href="#" class="text-gray-400 transition-colors hover:text-yellow-500">
                <i class="w-6 h-6 lucide-linkedin"></i>
              </a>
            </div>
          </div> -->
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <?php require 'footer.php' ?>
</body>

</html>