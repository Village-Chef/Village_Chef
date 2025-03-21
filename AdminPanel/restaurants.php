<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Management | Food Ordering System</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#000000',
                        accent: '#eab308',
                    }
                }
            }
        }
    </script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64">
                <!-- Sidebar component -->
                <div class="flex flex-col flex-grow bg-primary pt-5 pb-4 overflow-y-auto">
                    <div class="flex items-center flex-shrink-0 px-4">
                        <h1 class="text-xl font-bold text-white">Food Admin</h1>
                    </div>
                    <div class="mt-5 flex-1 flex flex-col">
                        <nav class="flex-1 px-2 space-y-1">
                            <!-- Dashboard Link -->
                            <a href="dashboard.html"
                                class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-home mr-3 text-gray-400 group-hover:text-accent"></i>
                                Dashboard
                            </a>
                            <!-- Users Link -->
                            <a href="users.html"
                                class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-users mr-3 text-gray-400 group-hover:text-accent"></i>
                                Users
                            </a>
                            <!-- Restaurants Link -->
                            <a href="restaurants.html"
                                class="bg-gray-900 text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-store mr-3 text-accent"></i>
                                Restaurants
                            </a>
                            <!-- Menu Items Link -->
                            <a href="menu-items.html"
                                class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-utensils mr-3 text-gray-400 group-hover:text-accent"></i>
                                Menu Items
                            </a>
                            <!-- Orders Link -->
                            <a href="orders.html"
                                class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-shopping-cart mr-3 text-gray-400 group-hover:text-accent"></i>
                                Orders
                            </a>
                            <!-- Payments Link -->
                            <a href="payments.html"
                                class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-credit-card mr-3 text-gray-400 group-hover:text-accent"></i>
                                Payments
                            </a>
                            <!-- Reviews Link -->
                            <a href="reviews.html"
                                class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                <i class="fas fa-star mr-3 text-gray-400 group-hover:text-accent"></i>
                                Reviews
                            </a>
                        </nav>
                    </div>
                    <!-- Logout Button -->
                    <div class="px-2 mt-6 mb-4">
                        <a href="login.html"
                            class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-sign-out-alt mr-3 text-gray-400 group-hover:text-accent"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <!-- Top Navigation -->
            <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow">
                <button
                    class="md:hidden px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:bg-gray-100 focus:text-gray-600">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="flex-1 px-4 flex justify-between">
                    <div class="flex-1 flex">
                        <h1 class="text-2xl font-semibold text-gray-900 my-auto">Restaurant Management</h1>
                    </div>
                    <div class="ml-4 flex items-center md:ml-6">
                        <div class="ml-3 relative">
                            <div>
                                <button
                                    class="max-w-xs flex items-center text-sm rounded-full text-white focus:outline-none focus:shadow-solid">
                                    <img class="h-8 w-8 rounded-full"
                                        src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                        alt="">
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
                <!-- Search and Add Restaurant -->
                <div class="flex flex-col md:flex-row justify-between mb-6">
                    <div class="w-full md:w-1/3 mb-4 md:mb-0">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-accent focus:ring-1 focus:ring-accent sm:text-sm"
                                placeholder="Search restaurants...">
                        </div>
                    </div>
                    <div>
                        <button type="button"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-accent hover:bg-accent/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                            <i class="fas fa-plus mr-2"></i> Add Restaurant
                        </button>
                    </div>
                </div>

                <!-- Restaurants Table -->
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Restaurant
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Owner
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Phone
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Address
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <!-- Restaurant Row 1 -->
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full object-cover"
                                                            src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=256&h=256&q=80"
                                                            alt="">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            Burger King
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            Fast Food
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">Michael Johnson</div>
                                                <div class="text-sm text-gray-500">michael.johnson@example.com</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">(555) 987-6543</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">123 Main St, New York, NY 10001</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button class="text-accent hover:text-accent/80 mr-3">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="text-red-600 hover:text-red-900 mr-3">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <button class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Restaurant Row 2 -->
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full object-cover"
                                                            src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=256&h=256&q=80"
                                                            alt="">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            Pizza Hut
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            Pizza
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">Sarah Williams</div>
                                                <div class="text-sm text-gray-500">sarah.williams@example.com</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">(555) 123-4567</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">456 Broadway, New York, NY 10002
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button class="text-accent hover:text-accent/80 mr-3">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="text-red-600 hover:text-red-900 mr-3">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <button class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Restaurant Row 3 -->
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full object-cover"
                                                            src="https://images.unsplash.com/photo-1513639776629-7b61b0ac49cb?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=256&h=256&q=80"
                                                            alt="">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            KFC
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            Fried Chicken
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">David Brown</div>
                                                <div class="text-sm text-gray-500">david.brown@example.com</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">(555) 234-5678</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">789 5th Ave, New York, NY 10003</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button class="text-accent hover:text-accent/80 mr-3">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="text-red-600 hover:text-red-900 mr-3">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <button class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Restaurant Row 4 -->
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full object-cover"
                                                            src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=256&h=256&q=80"
                                                            alt="">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            Olive Garden
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            Italian
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">Jennifer Lee</div>
                                                <div class="text-sm text-gray-500">jennifer.lee@example.com</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">(555) 345-6789</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">101 Park Ave, New York, NY 10004
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Inactive
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button class="text-accent hover:text-accent/80 mr-3">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="text-red-600 hover:text-red-900 mr-3">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <button class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Restaurant Row 5 -->
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full object-cover"
                                                            src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=256&h=256&q=80"
                                                            alt="">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            Taco Bell
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            Mexican
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">Robert Garcia</div>
                                                <div class="text-sm text-gray-500">robert.garcia@example.com</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">(555) 456-7890</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">222 Lexington Ave, New York, NY 10005
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button class="text-accent hover:text-accent/80 mr-3">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="text-red-600 hover:text-red-900 mr-3">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <button class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div
                    class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 mt-4 rounded-lg">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <a href="#"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Previous
                        </a>
                        <a href="#"
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Next
                        </a>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing <span class="font-medium">1</span> to <span class="font-medium">5</span> of
                                <span class="font-medium">12</span> results
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                aria-label="Pagination">
                                <a href="#"
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Previous</span>
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                                <a href="#" aria-current="page"
                                    class="z-10 bg-accent border-accent text-white relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                    1
                                </a>
                                <a href="#"
                                    class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                    2
                                </a>
                                <a href="#"
                                    class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                    3
                                </a>
                                <a href="#"
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Next</span>
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Add Restaurant Modal (Hidden by default) -->
                <div class="fixed inset-0 overflow-y-auto hidden" id="add-restaurant-modal">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                            aria-hidden="true">&#8203;</span>
                        <div
                            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                            Add New Restaurant
                                        </h3>
                                        <div class="mt-4">
                                            <form class="space-y-4">
                                                <div>
                                                    <label for="restaurant-name"
                                                        class="block text-sm font-medium text-gray-700">Restaurant
                                                        Name</label>
                                                    <input type="text" name="restaurant-name" id="restaurant-name"
                                                        class="mt-1 focus:ring-accent focus:border-accent block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                </div>
                                                <div>
                                                    <label for="restaurant-type"
                                                        class="block text-sm font-medium text-gray-700">Restaurant
                                                        Type</label>
                                                    <select id="restaurant-type" name="restaurant-type"
                                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-accent focus:border-accent sm:text-sm">
                                                        <option>Fast Food</option>
                                                        <option>Pizza</option>
                                                        <option>Italian</option>
                                                        <option>Mexican</option>
                                                        <option>Chinese</option>
                                                        <option>Indian</option>
                                                        <option>Other</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label for="owner-name"
                                                        class="block text-sm font-medium text-gray-700">Owner
                                                        Name</label>
                                                    <input type="text" name="owner-name" id="owner-name"
                                                        class="mt-1 focus:ring-accent focus:border-accent block w-  name="
                                                        owner-name" id="owner-name"
                                                        class="mt-1 focus:ring-accent focus:border-accent block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                </div>
                                                <div>
                                                    <label for="owner-email"
                                                        class="block text-sm font-medium text-gray-700">Owner
                                                        Email</label>
                                                    <input type="email" name="owner-email" id="owner-email"
                                                        class="mt-1 focus:ring-accent focus:border-accent block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                </div>
                                                <div>
                                                    <label for="phone"
                                                        class="block text-sm font-medium text-gray-700">Phone
                                                        Number</label>
                                                    <input type="tel" name="phone" id="phone"
                                                        class="mt-1 focus:ring-accent focus:border-accent block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                </div>
                                                <div>
                                                    <label for="address"
                                                        class="block text-sm font-medium text-gray-700">Address</label>
                                                    <input type="text" name="address" id="address"
                                                        class="mt-1 focus:ring-accent focus:border-accent block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                </div>
                                                <div>
                                                    <label for="status"
                                                        class="block text-sm font-medium text-gray-700">Status</label>
                                                    <select id="status" name="status"
                                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-accent focus:border-accent sm:text-sm">
                                                        <option>Active</option>
                                                        <option>Inactive</option>
                                                        <option>Pending</option>
                                                    </select>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="button"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-accent text-base font-medium text-white hover:bg-accent/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent sm:ml-3 sm:w-auto sm:text-sm">
                                    Save
                                </button>
                                <button type="button"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>