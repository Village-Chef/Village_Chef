<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" class="bg-black">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Village Chef - Delicious Meals Delivered</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://unpkg.com/lucide-icons/dist/umd/lucide.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen  bg-black text-white ">
    <!-- Navbar -->
    <?php require 'navbar.php'; ?>

    <?php 
    if(isset($_SESSION['user'])) {
        echo "<script>window.location.href = 'menu.php';</script>";
    }
    ?>

    <!-- Hero Section -->
    <section class=" lg:container h-screen mx-auto px-4  py-20 flex flex-col md:flex-row items-center">
        <div class="md:w-1/2 mb-8 md:mb-0">
            <h1 class="text-5xl md:text-6xl font-bold leading-tight">
                Grab Big Deals <br>
                on <span class="text-yellow-500">Yummy Meals!</span>
            </h1>
            <p class="mt-4 text-gray-400 max-w-md">
                Experience the finest culinary delights delivered right to your doorstep.
                Fresh ingredients, authentic recipes, and unforgettable flavors.
            </p>
            <a href="search.php">
                <button class="mt-8 bg-yellow-500 hover:bg-yellow-600 text-black font-bold px-8 py-3 rounded-full">
                    Get Started
                </button>
            </a>

            <div class="mt-12 flex items-center">
                <div class="flex -space-x-4">
                    <img src="Assets/parthiv.jpg" alt="Customer"
                        class="w-12 h-12 rounded-full border-2 border-black object-cover">
                    <img src="Assets/purv.jpg" alt="Customer"
                        class="w-12 h-12 rounded-full border-2 border-black object-cover">
                    <img src="Assets/parthiv.jpg" alt="Customer"
                        class="w-12 h-12 rounded-full border-2 border-black object-cover">
                </div>
                <div class="ml-4">
                    <p class="font-medium">Our Happy Customers</p>
                    <div class="flex items-center">
                        <i class="lucide-star w-4 h-4 text-yellow-500 fill-yellow-500"></i>
                        <span class="ml-1 font-bold">4.8</span>
                        <span class="ml-1 text-sm text-gray-400">(18.5k Reviews)</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:w-1/2 relative">
            <div class="relative   overflow-hidden w-full aspect-square max-w-lg mx-auto">
                <img src="Assets\pizza2.png" alt="Delicious meal" class="absolute  inset-0 w-full h-full object-cover">

                <!-- Floating elements -->
                <div class="absolute bottom-4 right-4 bg-black/80 backdrop-blur-sm p-3 rounded-lg flex items-center">
                    <div class="mr-3">
                        <img src="Assets\pizza2.png" alt="Cheese Pizza" class="w-16 h-16 rounded-md object-cover">
                    </div>
                    <div>
                        <p class="font-medium">Cheese Pizza</p>
                        <div class="flex">
                            <i class="lucide-star w-3 h-3 text-yellow-500 fill-yellow-500"></i>
                            <i class="lucide-star w-3 h-3 text-yellow-500 fill-yellow-500"></i>
                            <i class="lucide-star w-3 h-3 text-yellow-500 fill-yellow-500"></i>
                            <i class="lucide-star w-3 h-3 text-yellow-500 fill-yellow-500"></i>
                            <i class="lucide-star w-3 h-3 text-yellow-500 fill-yellow-500"></i>
                        </div>
                        <p class="text-yellow-500 font-bold">₹299/-</p>
                    </div>
                </div>

                <div class="absolute top-8 left-8 animate-bounce">
                    <span class="text-3xl">😋</span>
                </div>

                <div class="absolute bottom-20 left-0 bg-black/80 backdrop-blur-sm p-3 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full overflow-hidden mr-3">
                            <img src="Assets/parthiv.jpg" alt="Food Courier" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="font-medium">Parthiv Shingala</p>
                            <p class="text-sm text-gray-400">Food Courier</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="bg-zinc-900 py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold">Why Choose <span class="text-yellow-500">Village Chef</span>
                </h2>
                <p class="mt-4 text-gray-400 max-w-2xl mx-auto">
                    We're committed to providing the best culinary experience with premium ingredients and exceptional
                    service.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Feature 1 -->
                <div class="bg-zinc-800 p-6 rounded-xl hover:shadow-lg hover:shadow-yellow-500/20 transition-all">
                    <div
                        class="w-16 h-16 bg-yellow-500/10 rounded-full flex items-center justify-center mb-4 text-yellow-500">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-utensils">
                            <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2" />
                            <path d="M7 2v20" />
                            <path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Premium Quality</h3>
                    <p class="text-gray-400">Only the finest ingredients sourced from trusted suppliers.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-zinc-800 p-6 rounded-xl hover:shadow-lg hover:shadow-yellow-500/20 transition-all">
                    <div
                        class="w-16 h-16 bg-yellow-500/10 rounded-full flex items-center justify-center mb-4 text-yellow-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-clock">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Fast Delivery</h3>
                    <p class="text-gray-400">Hot and fresh meals delivered within 30 minutes.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-zinc-800 p-6 rounded-xl hover:shadow-lg hover:shadow-yellow-500/20 transition-all">
                    <div
                        class="w-16 h-16 bg-yellow-500/10 rounded-full flex items-center justify-center mb-4 text-yellow-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-truck">
                            <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2" />
                            <path d="M15 18H9" />
                            <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14" />
                            <circle cx="17" cy="18" r="2" />
                            <circle cx="7" cy="18" r="2" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Free Shipping</h3>
                    <p class="text-gray-400">Free delivery on all orders over $30.</p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-zinc-800 p-6 rounded-xl hover:shadow-lg hover:shadow-yellow-500/20 transition-all">
                    <div
                        class="w-16 h-16 bg-yellow-500/10 rounded-full flex items-center justify-center mb-4 text-yellow-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-chef-hat">
                            <path
                                d="M17 21a1 1 0 0 0 1-1v-5.35c0-.457.316-.844.727-1.041a4 4 0 0 0-2.134-7.589 5 5 0 0 0-9.186 0 4 4 0 0 0-2.134 7.588c.411.198.727.585.727 1.041V20a1 1 0 0 0 1 1Z" />
                            <path d="M6 17h12" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Expert Chefs</h3>
                    <p class="text-gray-400">Meals prepared by professional chefs with years of experience.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold">What Our <span class="text-yellow-500">Customers Say</span>
                </h2>
                <p class="mt-4 text-gray-400 max-w-2xl mx-auto">
                    Don't just take our word for it. Here's what our satisfied customers have to say.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div
                    class="bg-zinc-900 border border-zinc-800 p-6 rounded-xl hover:border-yellow-500/50 transition-all">
                    <div class="flex items-center mb-4">
                        <img src="Assets/parthiv.jpg" alt="Sarah Johnson" class="w-12 h-12 rounded-full object-cover">
                        <div class="ml-4">
                            <h4 class="font-bold">Parthiv Shingala</h4>
                            <p class="text-sm text-gray-400">Food Enthusiast</p>
                        </div>
                    </div>
                    <div class="flex mb-3">
                        <i class="lucide-star w-4 h-4 text-yellow-500 fill-yellow-500"></i>
                        <i class="lucide-star w-4 h-4 text-yellow-500 fill-yellow-500"></i>
                        <i class="lucide-star w-4 h-4 text-yellow-500 fill-yellow-500"></i>
                        <i class="lucide-star w-4 h-4 text-yellow-500 fill-yellow-500"></i>
                        <i class="lucide-star w-4 h-4 text-yellow-500 fill-yellow-500"></i>
                    </div>
                    <p class="text-gray-300">The food quality is exceptional! I've been ordering weekly and have never
                        been disappointed. The delivery is always on time and the food is still hot.</p>
                </div>

                <!-- Testimonial 2 -->
                <div
                    class="bg-zinc-900 border border-zinc-800 p-6 rounded-xl hover:border-yellow-500/50 transition-all">
                    <div class="flex items-center mb-4">
                        <img src="Assets/purv.jpg" alt="Michael Chen" class="w-12 h-12 rounded-full object-cover">
                        <div class="ml-4">
                            <h4 class="font-bold">Purv Virpariya</h4>
                            <p class="text-sm text-gray-400">Busy Professional</p>
                        </div>
                    </div>
                    <div class="flex mb-3">
                        <i class="lucide-star w-4 h-4 text-yellow-500 fill-yellow-500"></i>
                        <i class="lucide-star w-4 h-4 text-yellow-500 fill-yellow-500"></i>
                        <i class="lucide-star w-4 h-4 text-yellow-500 fill-yellow-500"></i>
                        <i class="lucide-star w-4 h-4 text-yellow-500 fill-yellow-500"></i>
                        <i class="lucide-star w-4 h-4 text-yellow-500 fill-yellow-500"></i>
                    </div>
                    <p class="text-gray-300">Village Chef has been a lifesaver for me. The meals are delicious, healthy,
                        and save me so much time. Their customer service is also top-notch.</p>
                </div>

                <!-- Testimonial 3 -->
                <div
                    class="bg-zinc-900 border border-zinc-800 p-6 rounded-xl hover:border-yellow-500/50 transition-all">
                    <div class="flex items-center mb-4">
                        <img src="Assets/purv.jpg"
                            alt="Emily Rodriguez" class="w-12 h-12 rounded-full object-cover">
                        <div class="ml-4">
                            <h4 class="font-bold">Emily Rodriguez</h4>
                            <p class="text-sm text-gray-400">Family of Four</p>
                        </div>
                    </div>
                    <div class="flex mb-3">
                        <i class="lucide-star w-4 h-4 text-yellow-500 fill-yellow-500"></i>
                        <i class="lucide-star w-4 h-4 text-yellow-500 fill-yellow-500"></i>
                        <i class="lucide-star w-4 h-4 text-yellow-500 fill-yellow-500"></i>
                        <i class="lucide-star w-4 h-4 text-yellow-500 fill-yellow-500"></i>
                        <i class="lucide-star w-4 h-4 text-gray-600"></i>
                    </div>
                    <p class="text-gray-300">My entire family loves the variety of options. The kids' meals are
                        particularly great - healthy but still appealing to picky eaters!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <!-- <section class="py-16 bg-gradient-to-r from-zinc-900 to-black">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-zinc-800 rounded-2xl p-8 md:p-12 flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-8 md:mb-0">
                    <h2 class="text-3xl md:text-4xl font-bold">Ready to Enjoy <span class="text-yellow-500">Delicious
                            Meals?</span></h2>
                    <p class="mt-4 text-gray-400 max-w-md">
                        Sign up now and get 20% off your first order. Join thousands of satisfied customers today!
                    </p>
                </div>

                <div class="md:w-1/2 md:pl-8">
                    <div class="bg-zinc-900 p-6 rounded-xl">
                        <h3 class="text-xl font-bold mb-4">Get Started Today</h3>
                        <div class="space-y-4">
                            <input type="text" placeholder="Your Name"
                                class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white">
                            <input type="email" placeholder="Your Email"
                                class="w-full px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-md text-white">
                            <button
                                class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-2 px-4 rounded-md">
                                Subscribe & Save
                            </button>
                            <p class="text-xs text-gray-500 text-center">
                                By subscribing, you agree to our Terms of Service and Privacy Policy.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->


    <!-- Footer -->
    <!-- <footer class="bg-zinc-900 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="mr-2">
                            <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                                <svg class="text-black" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chef-hat">
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
                    <p class="text-gray-400 mb-4">
                        Bringing restaurant-quality meals to your doorstep since 2020.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">
                            <i class="lucide-facebook w-5 h-5"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">
                            <i class="lucide-twitter w-5 h-5"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">
                            <i class="lucide-instagram w-5 h-5"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">
                            <i class="lucide-linkedin w-5 h-5"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-lg mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">Home</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">Menu</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">Contact</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors">FAQ</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-lg mb-4">Contact Us</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Vraj Bhoomi Plazza , Surat</li>
                        <li>+91 9727181143</li>
                        <li>info@villagechef.com</li>
                        <li>Mon-Sun: 10:00 AM - 10:00 PM</li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-lg mb-4">Newsletter</h4>
                    <p class="text-gray-400 mb-4">Subscribe to get special offers and updates.</p>
                    <div class="flex">
                        <input type="email" placeholder="Your Email"
                            class="px-4 py-2 bg-zinc-800 border border-zinc-700 focus:border-yellow-500 rounded-l-md text-white">
                        <button class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-r-md">
                            Send
                        </button>
                    </div>
                </div>
            </div>

            <div class="border-t border-zinc-800 pt-8 text-center text-gray-500 text-sm">
                <p>&copy;
                    <script>document.write(new Date().getFullYear())</script> Village Chef. All rights reserved.
                </p>
            </div>
        </div>
    </footer> -->

    <?php 
    
    require "footer.php";

    ?>

    <!-- Add Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>