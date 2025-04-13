<body class="min-h-screen bg-black text-white ">

    <!-- Navbar -->
    <?php 
    $ActivePage="About";
    require 'navbar.php' ?>

    <main class="flex-grow pt-12">
        <div class="max-w-4xl mx-auto px-4 py-12">
            <!-- <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center">About Village Chef</h1> -->

            <div class="mb-12">
                <img src="Assets/office2.jpeg" alt="Village Chef Team"
                    class="w-full h-auto rounded-xl mb-6">
                <p class="text-gray-300 mb-4">
                    Village Chef was born out of a passion for bringing authentic, home-cooked meals to busy urban
                    dwellers. Founded in 2020, we've quickly become a favorite among food enthusiasts who crave the
                    warmth and flavor of traditional cooking.
                </p>
                <p class="text-gray-300 mb-4">
                    Our team of expert chefs, each specializing in different regional cuisines, work tirelessly to
                    create dishes that not only tantalize your taste buds but also remind you of home. We source our
                    ingredients from local farmers and suppliers, ensuring that every meal is fresh, sustainable, and
                    supports our community.
                </p>
            </div>

            <h2 class="text-2xl font-bold mb-4">Our Mission</h2>
            <p class="text-gray-300 mb-8">
                At Village Chef, our mission is to reconnect people with the joy of wholesome, delicious meals without
                the hassle of cooking. We believe that everyone deserves access to high-quality, nutritious food that
                doesn't compromise on taste or convenience.
            </p>

            <!-- <h2 class="text-2xl font-bold mb-4">Meet Our Chefs</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <img src="https://placehold.co/200x200" alt="Chef Maria"
                        class="w-32 h-32 rounded-full mx-auto mb-4">
                    <h3 class="font-bold">Chef Maria</h3>
                    <p class="text-gray-400">Specializes in Italian Cuisine</p>
                </div>
                <div class="text-center">
                    <img src="Assets/office.jpeg" alt="Chef Akira"
                        class="w-32 h-32 rounded-full mx-auto mb-4">
                    <h3 class="font-bold">Chef Akira</h3>
                    <p class="text-gray-400">Specializes in Japanese Cuisine</p>
                </div>
                <div class="text-center">
                    <img src="https://placehold.co/200x200" alt="Chef Aisha"
                        class="w-32 h-32 rounded-full mx-auto mb-4">
                    <h3 class="font-bold">Chef Aisha</h3>
                    <p class="text-gray-400">Specializes in Middle Eastern Cuisine</p>
                </div>
            </div> -->
        </div>
    </main>

    <!-- Footer -->
    <?php require 'footer.php' ?>
</body>

</html>