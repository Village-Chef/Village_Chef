<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}

require '../dbCon.php';
$obj = new Foodies();

$result = $obj->getAllOrders();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management | Food Ordering System</title>
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

        function openModal(userId, status) {
            document.getElementById('user_id').value = userId;
            document.getElementById('status').value = status;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function openDeleteModal(userId) {
            document.getElementById('delete_user_id').value = userId;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
        function openOrderDetailsModal() {
            document.getElementById('order-details-modal').classList.remove('hidden');
        }

        function closeOrderDetailsModal() {
            document.getElementById('order-details-modal').classList.add('hidden');
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
                                placeholder="Search orders...">
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button type="button"
                            class="inline-flex items-center px-4 py-2 border border-gray-600 text-gray-300 rounded-xl hover:bg-gray-700/30 transition-colors">
                            <i class="fas fa-file-export mr-2"></i> Export
                        </button>
                        <button type="button"
                            class="inline-flex items-center px-4 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">
                            <i class="fas fa-plus mr-2"></i> Create Order
                        </button>
                    </div>
                </div>

                <!-- Filter Options -->
                <div class="bg-gray-800 p-4 rounded-xl border border-gray-700 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="status-filter"
                                class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                            <select id="status-filter"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="out-for-delivery">Out for Delivery</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <label for="restaurant-filter"
                                class="block text-sm font-medium text-gray-300 mb-2">Restaurant</label>
                            <select id="restaurant-filter"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                                <option value="">All Restaurants</option>
                                <option value="burger-king">Burger King</option>
                                <option value="pizza-hut">Pizza Hut</option>
                                <option value="kfc">KFC</option>
                                <option value="olive-garden">Olive Garden</option>
                                <option value="taco-bell">Taco Bell</option>
                            </select>
                        </div>
                        <div>
                            <label for="date-from" class="block text-sm font-medium text-gray-300 mb-2">Date
                                From</label>
                            <input type="date" id="date-from"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                        </div>
                        <div>
                            <label for="date-to" class="block text-sm font-medium text-gray-300 mb-2">Date To</label>
                            <input type="date" id="date-to"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-3">
                        <button type="button"
                            class="px-6 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">Apply
                            Filters</button>
                        <button type="button"
                            class="px-6 py-2 border border-gray-600 text-gray-300 rounded-xl hover:bg-gray-700/30 transition-colors">Reset</button>
                    </div>
                </div>

                <!-- Orders Table -->
                <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-xl overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Order ID
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Customer
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Restaurant
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Total</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Status</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Date</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Last Updated
                                </th>
                                <th class="px-6 py-4 text-right text-sm font-medium text-gray-300 uppercase">Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">

                            <?php
                            foreach ($result as $row) {
                                ?>
                                <tr class="hover:bg-gray-700/20 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-white"><?php echo $row['order_id'] ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full border border-gray-600"
                                                    src="<?php echo $row['profile_pic'] ?>" alt="">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-white"><?php echo $row['first_name'] ?>
                                                    <?php echo $row['last_name'] ?>
                                                </div>
                                                <div class="text-sm text-gray-400"><?php echo $row['email'] ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-300"><?php echo $row['restaurant_name'] ?></td>
                                    <td class="px-6 py-4 text-sm text-accent font-medium">₹
                                        <?php echo $row['total_amount'] ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php
                                        $status = $row['status'];
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-900/30 text-yellow-400',
                                            'confirmed' => 'bg-blue-900/30 text-blue-400',
                                            'preparing' => 'bg-orange-900/30 text-orange-400',
                                            'out_for_delivery' => 'bg-purple-900/30 text-purple-400',
                                            'delivered' => 'bg-green-900/30 text-green-400',
                                            'cancelled' => 'bg-red-900/30 text-red-400'
                                        ];
                                        ?>
                                        <span
                                            class="px-2.5 py-1 rounded-full text-xs <?php echo $statusClasses[$status]; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $status)); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-300">
                                        <?php
                                        $date = new DateTime($row['order_date']);
                                        echo $date->format('M d, Y') . '<br><span class="text-xs">' . $date->format('g:i A') . '</span>';
                                        ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-300">
                                        <?php
                                        $date = new DateTime($row['updated_at']);
                                        echo $date->format('M d, Y') . '<br><span class="text-xs">' . $date->format('g:i A') . '</span>';
                                        ?>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end space-x-3">
                                            <button onclick="openOrderDetailsModal()"
                                                class="text-blue-400 hover:text-blue-300">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button
                                                onclick="openModal('<?php echo $row['order_id']; ?>', '<?php echo $row['status']; ?>')"
                                                class="text-accent hover:text-accent/80 transition-colors">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="openDeleteModal('<?php echo $row['order_id']; ?>')"
                                                class="text-red-500 hover:text-red-400 transition-colors">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <?php
                            }
                            ?>

                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div
                    class="bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-700 mt-4 rounded-xl">
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-400">
                                Showing <span class="font-medium text-white">1</span> to <span
                                    class="font-medium text-white">5</span> of <span
                                    class="font-medium text-white">35</span> results
                            </p>
                        </div>
                        <nav class="relative z-0 inline-flex rounded-xl shadow-sm -space-x-px">
                            <a href="#"
                                class="px-3 py-2 rounded-l-xl border border-gray-700 bg-gray-800 text-gray-400 hover:bg-gray-700">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            <a href="#" class="px-4 py-2 border border-accent bg-accent/20 text-accent">1</a>
                            <a href="#" class="px-4 py-2 border border-gray-700 text-gray-400 hover:bg-gray-700">2</a>
                            <a href="#" class="px-4 py-2 border border-gray-700 text-gray-400 hover:bg-gray-700">3</a>
                            <a href="#"
                                class="px-3 py-2 rounded-r-xl border border-gray-700 bg-gray-800 text-gray-400 hover:bg-gray-700">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Order Details Modal -->
                <div class="fixed inset-0 overflow-y-auto hidden" id="order-details-modal">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                            <div class="absolute inset-0 bg-black/60"></div>
                        </div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
                        <div
                            class="inline-block align-bottom bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                        <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">Order
                                            Details - #ORD-001234</h3>
                                        <div class="mt-4">
                                            <div class="bg-gray-700 p-4 rounded-xl mb-4">
                                                <div class="flex justify-between mb-2">
                                                    <span class="text-sm font-medium text-gray-400">Customer:</span>
                                                    <span class="text-sm text-white">Jane Cooper</span>
                                                </div>
                                                <div class="flex justify-between mb-2">
                                                    <span class="text-sm font-medium text-gray-400">Restaurant:</span>
                                                    <span class="text-sm text-white">Burger King</span>
                                                </div>
                                                <div class="flex justify-between mb-2">
                                                    <span class="text-sm font-medium text-gray-400">Date:</span>
                                                    <span class="text-sm text-white">Mar 20, 2023 2:30 PM</span>
                                                </div>
                                                <div class="flex justify-between mb-2">
                                                    <span class="text-sm font-medium text-gray-400">Status:</span>
                                                    <span
                                                        class="px-2.5 py-1 rounded-full text-xs bg-green-900/30 text-green-400">Delivered</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-sm font-medium text-gray-400">Payment
                                                        Method:</span>
                                                    <span class="text-sm text-white">Credit Card</span>
                                                </div>
                                            </div>
                                            <h4 class="text-md font-medium text-white mb-2">Order Items</h4>
                                            <div class="border border-gray-700 rounded-xl overflow-hidden mb-4">
                                                <table class="min-w-full divide-y divide-gray-700">
                                                    <thead class="bg-gray-700">
                                                        <tr>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">
                                                                Item</th>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">
                                                                Qty</th>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">
                                                                Price</th>
                                                            <th
                                                                class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase">
                                                                Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                                                        <tr>
                                                            <td class="px-6 py-4 text-sm text-white">Whopper</td>
                                                            <td class="px-6 py-4 text-sm text-white">2</td>
                                                            <td class="px-6 py-4 text-sm text-white">$5.99</td>
                                                            <td class="px-6 py-4 text-sm text-white">$11.98</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-6 py-4 text-sm text-white">French Fries
                                                                (Large)</td>
                                                            <td class="px-6 py-4 text-sm text-white">2</td>
                                                            <td class="px-6 py-4 text-sm text-white">$3.99</td>
                                                            <td class="px-6 py-4 text-sm text-white">$7.98</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-6 py-4 text-sm text-white">Coca Cola (Medium)
                                                            </td>
                                                            <td class="px-6 py-4 text-sm text-white">2</td>
                                                            <td class="px-6 py-4 text-sm text-white">$2.49</td>
                                                            <td class="px-6 py-4 text-sm text-white">$4.98</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="bg-gray-700 p-4 rounded-xl">
                                                <div class="flex justify-between mb-2">
                                                    <span class="text-sm font-medium text-gray-400">Subtotal:</span>
                                                    <span class="text-sm text-white">$24.94</span>
                                                </div>
                                                <div class="flex justify-between mb-2">
                                                    <span class="text-sm font-medium text-gray-400">Tax:</span>
                                                    <span class="text-sm text-white">$1.05</span>
                                                </div>
                                                <div class="flex justify-between mb-2">
                                                    <span class="text-sm font-medium text-gray-400">Delivery Fee:</span>
                                                    <span class="text-sm text-white">$0.00</span>
                                                </div>
                                                <div class="flex justify-between pt-2 border-t border-gray-600">
                                                    <span class="text-base font-medium text-white">Total:</span>
                                                    <span class="text-base font-medium text-white">$25.99</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="button" onclick="closeOrderDetailsModal()"
                                    class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-600 px-4 py-2 bg-gray-700 text-base font-medium text-gray-300 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50">
        <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-accent">Edit Order</h1>
                <button onclick="closeModal()" class="text-gray-400 hover:text-accent transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form method="POST" action="updateOrder.php" class="space-y-6">
                <input type="hidden" id="user_id" name="order_id">

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-3">Status</label>
                    <div class="relative">
                        <select id="status" name="status"
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 appearance-none focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/50 transition-all">
                            <option value="pending" class="bg-gray-800 text-gray-300">Pending</option>
                            <option value="confirmed" class="bg-gray-800 text-gray-300">Confirmed</option>
                            <option value="preparing" class="bg-gray-800 text-gray-300">Preparing</option>
                            <option value="out_for_delivery" class="bg-gray-800 text-gray-300">Out for Delivery</option>
                            <option value="delivered" class="bg-gray-800 text-gray-300">Delivered</option>
                            <option value="cancelled" class="bg-gray-800 text-gray-300">Cancelled</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()"
                        class="px-6 py-2.5 border border-gray-600 rounded-xl text-gray-300 hover:bg-gray-700/30 hover:text-white transition-colors">
                        Cancel
                    </button>
                    <button type="submit" name="btnUpdate"
                        class="px-6 py-2.5 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">
                        Update Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div id="deleteModal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50">
        <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-accent">Delete Order</h1>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-accent transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form method="POST" action="deleteOrder.php" class="space-y-6">
                <input type="hidden" id="delete_user_id" name="order_id">

                <p class="text-gray-300">Are you sure you want to delete this Order?</p>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDeleteModal()"
                        class="px-6 py-2.5 border border-gray-600 rounded-xl text-gray-300 hover:bg-gray-700/30 hover:text-white transition-colors">
                        Cancel
                    </button>
                    <button type="submit" name="btnDelete"
                        class="px-6 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-500 font-medium transition-colors">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>