<script src="https://unpkg.com/@themesberg/flowbite@latest/dist/flowbite.bundle.js"></script>

<body class="min-h-screen text-white bg-black ">

    <!-- Navbar -->
    <?php
  $ActivePage = "Menu";
  require 'navbar.php';
  ?>

    <!-- Menu Section -->
    <main class="flex-grow py-20">
        <div class=" flex flex-col gap-9  mx-auto max-w-7xl">

            <?php $status = "open"; ?>

            <main>
                <!-- Restaurant Banner -->
                <div class="relative w-full h-60">
                    <img src="https://b.zmtcdn.com/data/pictures/chains/2/18871562/52a3b0fd51941d2fc936dbae5a202559.jpg"
                        alt="Restaurant Banner" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/50 flex flex-col justify-center p-6">
                        <h1 class="text-3xl font-bold">Pizza Hut</h1>
                        <p class="text-sm text-gray-300">Pizza, Fast Food, Desserts, Beverages</p>
                        <p class="text-sm text-gray-400">Shop 25 & 26, Ground Floor, Maruti Solaris, Near Madhuban
                            Resort, Hadgood, Anand</p>
                    </div>
                </div>

                <div class=" mx-auto p-6">
                    <div class="grid sm:grid-cols-[auto_1fr] gap-6">
                        <!-- Sidebar -->
                        <div class="w-full  sm:w-fit ">
                            <h2 class="text-lg font-semibold border-b border-zinc-700 pb-2 mb-3">Order Online</h2>
                            <ul class="space-y-2 px-4">
                                <li class="cursor-pointer text-yellow-500 font-medium">Veg Pizza (13)</li>
                                <li class="cursor-pointer text-gray-400">Non Veg Pizza (12)</li>
                                <li class="cursor-pointer text-gray-400">Meals And Deals (6)</li>
                                <li class="cursor-pointer text-gray-400">Thin n Crispy Pizzas (6)</li>
                                <li class="cursor-pointer text-gray-400">Flavour Fun Range (6)</li>
                            </ul>
                        </div>

                        <div
                            class="grid grid-cols-1  md:grid-cols-2  lg:grid-cols-3  gap-6 px-4 sm:px-6 md:px-10">
                            <?php
                                for ($i = 0; $i < 10; $i++) { 
                                    require('menuCard.php');
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </main>


        </div>
        </div>
    </main>




    <!-- Footer -->
    <?php require 'footer.php' ?>
</body>