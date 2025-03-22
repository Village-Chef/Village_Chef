<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}

require '../dbCon.php';
$obj = new Foodies();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments Management | Food Ordering System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
</head>

<body class="bg-primary text-gray-100">
    <div class="flex h-screen overflow-hidden">
        <?php include 'sidebar.php'; ?>

        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <?php include 'header.php'; ?>

            <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
                <!-- Search and Export -->
                <div class="flex flex-col md:flex-row justify-between mb-6 gap-4">
                    <div class="w-full md:w-1/3">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text"
                                class="block w-full pl-10 pr-3 py-2 bg-gray-800 border border-gray-700 rounded-xl placeholder-gray-400 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent"
                                placeholder="Search payments...">
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button type="button"
                            class="inline-flex items-center px-4 py-2 border border-gray-600 text-gray-300 rounded-xl hover:bg-gray-700/30 transition-colors">
                            <i class="fas fa-file-export mr-2"></i> Export
                        </button>
                        <button type="button"
                            class="inline-flex items-center px-4 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">
                            <i class="fas fa-file-invoice-dollar mr-2"></i> Generate Report
                        </button>
                    </div>
                </div>

                <!-- Payment Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-900/30 rounded-full p-3">
                                <i class="fas fa-dollar-sign text-green-400 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-400">Total Payments</p>
                                <h3 class="text-xl font-semibold text-white">$12,345.67</h3>
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-green-400">
                            <i class="fas fa-arrow-up mr-1"></i> 12% from last month
                        </div>
                    </div>
                    <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-900/30 rounded-full p-3">
                                <i class="fas fa-check-circle text-blue-400 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-400">Successful</p>
                                <h3 class="text-xl font-semibold text-white">245</h3>
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-blue-400">
                            <i class="fas fa-arrow-up mr-1"></i> 8% from last month
                        </div>
                    </div>
                    <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-900/30 rounded-full p-3">
                                <i class="fas fa-times-circle text-red-400 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-400">Failed</p>
                                <h3 class="text-xl font-semibold text-white">12</h3>
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-red-400">
                            <i class="fas fa-arrow-down mr-1"></i> 3% from last month
                        </div>
                    </div>
                    <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-900/30 rounded-full p-3">
                                <i class="fas fa-undo text-yellow-400 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-400">Refunded</p>
                                <h3 class="text-xl font-semibold text-white">8</h3>
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-yellow-400">
                            <i class="fas fa-equals mr-1"></i> Same as last month
                        </div>
                    </div>
                </div>

                <!-- Filter Options -->
                <div class="bg-gray-800 p-4 rounded-xl border border-gray-700 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label for="status-filter" class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                            <select id="status-filter"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                                <option value="">All Statuses</option>
                                <option value="successful">Successful</option>
                                <option value="pending">Pending</option>
                                <option value="failed">Failed</option>
                                <option value="refunded">Refunded</option>
                            </select>
                        </div>
                        <div>
                            <label for="payment-method-filter" class="block text-sm font-medium text-gray-300 mb-2">Payment Method</label>
                            <select id="payment-method-filter"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                                <option value="">All Methods</option>
                                <option value="credit-card">Credit Card</option>
                                <option value="debit-card">Debit Card</option>
                                <option value="paypal">PayPal</option>
                                <option value="apple-pay">Apple Pay</option>
                                <option value="google-pay">Google Pay</option>
                            </select>
                        </div>
                        <div>
                            <label for="date-from" class="block text-sm font-medium text-gray-300 mb-2">Date From</label>
                            <input type="date" id="date-from"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                        </div>
                        <div>
                            <label for="date-to" class="block text-sm font-medium text-gray-300 mb-2">Date To</label>
                            <input type="date" id="date-to"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                        </div>
                        <div>
                            <label for="amount-filter" class="block text-sm font-medium text-gray-300 mb-2">Amount Range</label>
                            <select id="amount-filter"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                                <option value="">All Amounts</option>
                                <option value="0-10">$0 - $10</option>
                                <option value="10-25">$10 - $25</option>
                                <option value="25-50">$25 - $50</option>
                                <option value="50-100">$50 - $100</option>
                                <option value="100+">$100+</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-3">
                        <button type="button"
                            class="px-6 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">Apply Filters</button>
                        <button type="button"
                            class="px-6 py-2 border border-gray-600 text-gray-300 rounded-xl hover:bg-gray-700/30 transition-colors">Reset</button>
                    </div>
                </div>

                <!-- Payments Table -->
                <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-xl overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Payment ID</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Order ID</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Customer</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Amount</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Payment Method</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Status</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Date</th>
                                <th class="px-6 py-4 text-right text-sm font-medium text-gray-300 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            <tr class="hover:bg-gray-700/20 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-white">#PAY-001234</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">#ORD-001234</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full border border-gray-600"
                                                src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                                alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-white">Jane Cooper</div>
                                            <div class="text-sm text-gray-400">jane.cooper@example.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-accent font-medium">$25.99</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-300"><i class="far fa-credit-card mr-1"></i> Credit Card</div>
                                    <div class="text-xs text-gray-400">**** 4242</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs bg-green-900/30 text-green-400">Successful</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">Mar 20, 2023<br><span class="text-xs">2:35 PM</span></td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end space-x-3">
                                        <button class="text-blue-400 hover:text-blue-300"><i class="fas fa-eye"></i></button>
                                        <button class="text-yellow-400 hover:text-yellow-300"><i class="fas fa-undo"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-700/20 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-white">#PAY-001235</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">#ORD-001235</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full border border-gray-600"
                                                src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                                alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-white">Michael Johnson</div>
                                            <div class="text-sm text-gray-400">michael.johnson@example.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-accent font-medium">$32.50</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-300"><i class="fab fa-paypal mr-1"></i> PayPal</div>
                                    <div class="text-xs text-gray-400">michael@example.com</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs bg-green-900/30 text-green-400">Successful</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">Mar 20, 2023<br><span class="text-xs">3:20 PM</span></td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end space-x-3">
                                        <button class="text-blue-400 hover:text-blue-300"><i class="fas fa-eye"></i></button>
                                        <button class="text-yellow-400 hover:text-yellow-300"><i class="fas fa-undo"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-700/20 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-white">#PAY-001236</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">#ORD-001236</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full border border-gray-600"
                                                src="https://images.unsplash.com/photo-1520813792240-56fc4a3765a7?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                                alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-white">Olivia Wilson</div>
                                            <div class="text-sm text-gray-400">olivia.wilson@example.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-accent font-medium">$18.99</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-300"><i class="fab fa-apple mr-1"></i> Apple Pay</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs bg-blue-900/30 text-blue-400">Pending</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">Mar 20, 2023<br><span class="text-xs">4:10 PM</span></td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end space-x-3">
                                        <button class="text-blue-400 hover:text-blue-300"><i class="fas fa-eye"></i></button>
                                        <button class="text-yellow-400 hover:text-yellow-300 opacity-50 cursor-not-allowed"><i class="fas fa-undo"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-700/20 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-white">#PAY-001237</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">#ORD-001237</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full border border-gray-600"
                                                src="https://images.unsplash.com/photo-1566492031773-4f4e44671857?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                                alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-white">Robert Brown</div>
                                            <div class="text-sm text-gray-400">robert.brown@example.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-accent font-medium">$45.00</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-300"><i class="far fa-credit-card mr-1"></i> Credit Card</div>
                                    <div class="text-xs text-gray-400">**** 1234</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs bg-green-900/30 text-green-400">Successful</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">Mar 20, 2023<br><span class="text-xs">5:00 PM</span></td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end space-x-3">
                                        <button class="text-blue-400 hover:text-blue-300"><i class="fas fa-eye"></i></button>
                                        <button class="text-yellow-400 hover:text-yellow-300"><i class="fas fa-undo"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>

</html>