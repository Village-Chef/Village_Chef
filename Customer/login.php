<body class="min-h-screen bg-black text-white ">

  <!-- Navbar -->
  <?php require 'navbar.php' ?>
  
  <!-- Login Section -->
  <main class="flex-grow flex items-center justify-center px-4 py-12">
    <div class="bg-zinc-900 p-8 rounded-xl shadow-lg max-w-md w-full">
      <h2 class="text-3xl font-bold mb-6 text-center">Welcome Back</h2>
      <form>
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email Address</label>
          <input
            type="email"
            id="email"
            name="email"
            class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
            placeholder="your@email.com"
            required>
        </div>
        <div class="mb-6">
          <label for="password" class="block text-sm font-medium text-gray-400 mb-2">Password</label>
          <input
            type="password"
            id="password"
            name="password"
            class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white"
            placeholder="••••••••"
            required>
        </div>
        <button
          type="submit"
          class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-2 px-4 rounded-md transition-colors">
          Log In
        </button>
      </form>
      <div class="mt-4 text-center">
        <a href="#" class="text-sm text-yellow-500 hover:underline">Forgot your password?</a>
      </div>
      <div class="mt-6 border-t border-zinc-800 pt-6 text-center">
        <p class="text-gray-400">Don't have an account?</p>
        <a href="signup.php" class="text-yellow-500 hover:underline font-medium">Sign up now</a>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <?php require 'footer.php' ?>
</body>

</html>