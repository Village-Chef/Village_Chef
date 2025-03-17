<script src="https://unpkg.com/@themesberg/flowbite@latest/dist/flowbite.bundle.js"></script>
<?php
if (isset($_GET['sort'])) {
  $sort = $_GET['sort'];

  if ($sort == 'pop') {
    $sort = "Popularity";
  } elseif ($sort == 'ra') {
    $sort = "Rating: High to Low";
  } elseif ($sort == 'casc') {
    $sort = "Cost: Low to High";
  } elseif ($sort == 'cdesc') {
    $sort = "Cost: High to Low";
  }

}
?>

<body class="min-h-screen text-white bg-black ">

  <!-- Navbar -->
  <?php
  $ActivePage = "Menu";
  require 'navbar.php' ?>

  <!-- Menu Section -->
  <main class="flex-grow py-20">
    <div class=" flex flex-col gap-9  mx-auto max-w-7xl">
      <!-- List of Foods -->
      <div class="flex flex-col sm:px-4 px-2 py-6 gap-9 ">
        <h1 class=" text-xl font-bold md:text-4xl text-start">Inspiration for your first order</h1>
        <form method="get">
          <div class="flex flex-row gap-4  overflow-x-scroll overflow-y-hidden md:gap-8 noscorll scroll-smooth">
            <button type="submit" name="category" value="Pizza" class="flex flex-col">
              <div class="flex flex-col flex-shrink-0 w-20 h-20 md:w-32 md:h-32">
                <img class="w-full h-full border-4 border-yellow-500 rounded-full" src="Assets/pizza2.png" />
              </div>
              <p class="z-10 my-3 font-sans  text-center text-sm sm:text-md">Pizza</p>
            </button>
            <button type="submit" name="category" value="Burger" class="flex flex-col">
              <div class="flex flex-col flex-shrink-0 w-20 h-20 md:w-32 md:h-32">
                <img class="w-full h-full border-4 border-yellow-500 rounded-full" src="Assets/burger.png" />
              </div>
              <p class="z-10 my-3 font-sans text-center text-sm sm:text-md">Burger</p>
            </button>

            <div class="flex flex-col">
              <div class="flex flex-col flex-shrink-0 w-20 h-20 md:w-32 md:h-32">
                <img class="w-full h-full border-4 border-yellow-500 rounded-full"
                  src="https://b.zmtcdn.com/data/o2_assets/fc641efbb73b10484257f295ef0b9b981634401116.png" />
              </div>
              <p class="z-10 my-3 font-sans text-center text-sm sm:text-md">Sandwich</p>
            </div>
            <div class="flex flex-col">
              <div class="flex flex-col flex-shrink-0 w-20 h-20 md:w-32 md:h-32">
                <img class="w-full h-full border-4 border-yellow-500 rounded-full"
                  src="https://b.zmtcdn.com/data/o2_assets/52eb9796bb9bcf0eba64c643349e97211634401116.png" />
              </div>
              <p class="z-10 my-3 font-sans text-center text-sm sm:text-md">Gujrati Thali</p>
            </div>
            <div class="flex flex-col ">
              <div class="flex flex-col flex-shrink-0 w-20 h-20 md:w-32 md:h-32">
                <img class="w-full h-full border-4 border-yellow-500 rounded-full"
                  src="https://b.zmtcdn.com/data/o2_assets/bf2d0e73add1c206aeeb9fec762438111727708719.png" />
              </div>
              <p class="z-10 my-3 font-sans text-center text-sm sm:text-md">Biryani</p>
            </div>

            <div class="flex flex-col">
              <div class="flex flex-col flex-shrink-0 w-20 h-20 md:w-32 md:h-32">
                <img class="w-full h-full border-4 border-yellow-500 rounded-full"
                  src="https://b.zmtcdn.com/data/dish_images/c2f22c42f7ba90d81440a88449f4e5891634806087.png" />
              </div>
              <p class="z-10 my-3 font-sans text-center text-sm sm:text-md">Rolls</p>
            </div>
            <div class="flex flex-col">
              <div class="flex flex-col flex-shrink-0 w-20 h-20 md:w-32 md:h-32">
                <img class="w-full h-full border-4 border-yellow-500 rounded-full"
                  src="https://b.zmtcdn.com/data/o2_assets/019409fe8f838312214d9211be010ef31678798444.jpeg" />
              </div>
              <p class="z-10 my-3 font-sans text-center text-sm sm:text-md">North Indian</p>
            </div>
            <div class="flex flex-col">
              <div class="flex flex-col flex-shrink-0 w-20 h-20 md:w-32 md:h-32">
                <img class="w-full h-full border-4 border-yellow-500 rounded-full"
                  src="https://b.zmtcdn.com/data/o2_assets/2b5a5b533473aada22015966f668e30e1633434990.png" />
              </div>
              <p class="z-10 my-3 font-sans text-center text-sm sm:text-md">North Indian</p>
            </div>
            <div class="flex flex-col">
              <div class="flex flex-col flex-shrink-0 w-20 h-20 md:w-32 md:h-32">
                <img class="w-full h-full border-4 border-yellow-500 rounded-full" src="Assets/icecream.png" />
              </div>
              <p class="z-10 my-3 font-sans text-center text-sm sm:text-md">ice Cream</p>
            </div>
          </div>
        </form>
      </div>
      <script>
        document.addEventListener("DOMContentLoaded", function () {
          const scrollContainer = document.querySelector(".noscorll");

          scrollContainer.addEventListener("wheel", function (event) {
            event.preventDefault();
            scrollContainer.scrollLeft += event.deltaY;
          });
        });

      </script>

      <!-- Top restaurants -->
      <!-- <div class="flex flex-col sm:px-4 px-2 py-6 gap-9">
        <h1 class=" text-xl font-bold md:text-4xl text-start ">Top restaurants for you</h1>
        <div class="flex flex-row gap-4 overflow-x-scroll overflow-y-hidden md:gap-8 noscorll scroll-smooth">
          <div class="flex flex-col">
            <div class="flex flex-col flex-shrink-0 w-20 h-20 md:w-32 md:h-32">
              <img class="w-full h-full border-4 border-yellow-500 rounded-full" src="Assets/pizza2.png" />
            </div>
            <p class="z-10 my-3 font-sans  text-center text-sm sm:text-md">Pizza</p>
          </div>
          <div class="flex flex-col">
            <div class="flex flex-col flex-shrink-0 w-20 h-20 md:w-32 md:h-32">
              <img class="w-full h-full border-4 border-yellow-500 rounded-full" src="Assets/burger.png" />
            </div>
            <p class="z-10 my-3 font-sans text-center text-sm sm:text-md">Burger</p>
          </div>
          <div class="flex flex-col">
            <div class="flex flex-col flex-shrink-0 w-20 h-20 md:w-32 md:h-32">
              <img class="w-full h-full border-4 border-yellow-500 rounded-full" src="https://b.zmtcdn.com/data/o2_assets/fc641efbb73b10484257f295ef0b9b981634401116.png" />
            </div>
            <p class="z-10 my-3 font-sans text-center text-sm sm:text-md">Sandwich</p>
          </div>
          <div class="flex flex-col">
            <div class="flex flex-col flex-shrink-0 w-20 h-20 md:w-32 md:h-32">
              <img class="w-full h-full border-4 border-yellow-500 rounded-full" src="https://b.zmtcdn.com/data/o2_assets/52eb9796bb9bcf0eba64c643349e97211634401116.png" />
            </div>
            <p class="z-10 my-3 font-sans text-center text-sm sm:text-md">Gujrati Thali</p>
          </div>
          <div class="flex flex-col ">
            <div class="flex flex-col flex-shrink-0 w-20 h-20 md:w-32 md:h-32">
              <img class="w-full h-full border-4 border-yellow-500 rounded-full" src="https://b.zmtcdn.com/data/o2_assets/bf2d0e73add1c206aeeb9fec762438111727708719.png" />
            </div>
            <p class="z-10 my-3 font-sans text-center text-sm sm:text-md">Biryani</p>
          </div>

          <div class="flex flex-col">
            <div class="flex flex-col flex-shrink-0 w-20 h-20 md:w-32 md:h-32">
              <img class="w-full h-full border-4 border-yellow-500 rounded-full" src="https://b.zmtcdn.com/data/dish_images/c2f22c42f7ba90d81440a88449f4e5891634806087.png" />
            </div>
            <p class="z-10 my-3 font-sans text-center text-sm sm:text-md">Rolls</p>
          </div>
          <div class="flex flex-col">
            <div class="flex flex-col flex-shrink-0 w-20 h-20 md:w-32 md:h-32">
              <img class="w-full h-full border-4 border-yellow-500 rounded-full" src="https://b.zmtcdn.com/data/o2_assets/019409fe8f838312214d9211be010ef31678798444.jpeg" />
            </div>
            <p class="z-10 my-3 font-sans text-center text-sm sm:text-md">North Indian</p>
          </div>
          <div class="flex flex-col">
            <div class="flex flex-col flex-shrink-0 w-20 h-20 md:w-32 md:h-32">
              <img class="w-full h-full border-4 border-yellow-500 rounded-full" src="https://b.zmtcdn.com/data/o2_assets/2b5a5b533473aada22015966f668e30e1633434990.png" />
            </div>
            <p class="z-10 my-3 font-sans text-center text-sm sm:text-md">North Indian</p>
          </div>
          <div class="flex flex-col">
            <div class="flex flex-col flex-shrink-0 w-20 h-20 md:w-32 md:h-32">
              <img class="w-full h-full border-4 border-yellow-500 rounded-full" src="Assets/icecream.png" />
            </div>
            <p class="z-10 my-3 font-sans text-center text-sm sm:text-md">ice Cream</p>
          </div>
        </div>
      </div> -->

      <!-- Filters -->
      <div class=" <?php echo isset($_REQUEST['category']) ? 'flex' : 'hidden'; ?> gap-2 flex-row sm:px-4 px-2 py-6">
        <!-- Filter -->
        <button
          class="flex gap-3 cursor-pointer w-fit rounded-full text-md px-4 py-2 items-center bg-zinc-900 text-yellow-500"
          type="button" data-dropdown-toggle="dropdown">
          Filters
          <svg class="h-5 w-5 stroke-[2px] cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>

        <!-- Dropdown menu -->
        <div class="hidden text-base  z-50 list-none bg-zinc-900 divide-y divide-gray-100 rounded shadow my-4"
          id="dropdown">
          <ul class="py-1" aria-labelledby="dropdown">
            <li>
              <a href="?category=<?php $sortM = $_REQUEST['category'];
              echo $sortM; ?>&sort=pop"
                class="text-sm hover:bg-zinc-800 bg-zinc-900 text-white  block px-4 py-2">Popularity</a>
            </li>
            <li>
              <a href="?category=<?php $sortM = $_REQUEST['category'];
              echo $sortM; ?>&sort=ra"
                class="text-sm hover:bg-zinc-800 bg-zinc-900 text-white  block px-4 py-2">Rating: High to Low</a>
            </li>
            <li>
              <a href="?category=<?php $sortM = $_REQUEST['category'];
              echo $sortM; ?>&sort=casc"
                class="text-sm hover:bg-zinc-800 bg-zinc-900 text-white  block px-4 py-2">Cost: Low to High</a>
            </li>
            <li>
              <a href="?category=<?php $sortM = $_REQUEST['category'];
              echo $sortM; ?>&sort=cdesc"
                class="text-sm hover:bg-zinc-800 bg-zinc-900 text-white  block px-4 py-2">Cost: High to Low</a>
            </li>
          </ul>
        </div>

        <!-- Cat & Filter -->
        <div class="flex flex-row gap-3 ">

          <!-- category  -->
          <div
            class=" <?php echo isset($_GET['category']) ? 'flex' : 'hidden' ?>  text-md gap-3 cursor-pointer rounded-full px-4 py-2 items-center bg-zinc-900 text-yellow-500">
            <p>
              <?php
              if (isset($_GET['category'])) {
                $cat = $_GET['category'];
                echo $cat;
              }
              ?>
            </p>
            <a href="menu.php">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 stroke-[2px] cursor-pointer" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="lucide lucide-x">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
              </svg>
            </a>
          </div>

          <span class="flex items-center text-2xl text-yellow-500">/</span>

          <!-- Sort  -->
          <div
            class=" <?php echo isset($_GET['sort']) ? 'flex' : 'hidden' ?>  text-sm gap-3 cursor-pointer rounded-full px-4 py-2 items-center bg-zinc-900 text-yellow-500">
            <p>
              <?php
              echo $sort;
              ?>
            </p>
            <!-- <a href="menu.php">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 stroke-[2px] cursor-pointer" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
              </svg>
            </a> -->
          </div>
        </div>
      </div>
      <!-- Responsive Grid Layout -->
      <div class="grid grid-cols-1 sm:grid-cols-2  md:grid-cols-3 lg:grid-cols-4 gap-6 px-4 sm:px-6 md:px-10">
        <?php
        if (isset($_GET['category'])) {
          for ($i = 0; $i < 10; $i++) {
            require('menuCard.php');
          }
        }
        ?>
      </div>



    </div>

    </div>
  </main>




  <!-- Footer -->
  <?php require 'footer.php' ?>
</body>