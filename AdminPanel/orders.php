<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}

$msg = '';

if (isset($_SESSION['success'])) {
    $msg = $_SESSION['success'];
    $icon = 'success';
    unset($_SESSION['success']);
} elseif (isset($_SESSION['error'])) {
    $msg = $_SESSION['error'];
    $icon = 'error';
    unset($_SESSION['error']);
} else {
    $msg = '';
    $icon = '';
}


require '../dbCon.php';

$obj = new Foodies();

function formatStatus($status)
{
    return ucfirst(str_replace('_', ' ', $status));
}

$filters = [
    'status' => $_GET['status'] ?? '',
    'restaurant' => $_GET['restaurant'] ?? '',
    'date_from' => $_GET['date_from'] ?? '',
    'date_to' => $_GET['date_to'] ?? '',
    'search' => $_GET['search'] ?? ''
];

// Fetch filtered orders
$result = $obj->getAllOrders($filters);
$restaurants = $obj->getAllRestaurants();
$orderStatuses = $obj->getAllOrderStatuses();
// $singleOrder = $obj->getOrderById();
$orderDetails = null;

if (isset($_GET['view_order']) && !empty($_GET['view_order'])) {
    $order_id = $_GET['view_order'];
    // echo "Order ID: " . htmlspecialchars($order_id); // Debugging
    try {
        $orderDetails = $obj->getOrderById($order_id);
    } catch (Exception $e) {
        $error_message = "Failed to fetch order details: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management | Food Ordering System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                        <form method="GET" action="orders.php">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <?php if (!empty($msg)): ?>
                                    <script>
                                        Swal.fire({
                                            toast: true,
                                            position: 'top-end',
                                            icon: '<?php echo $icon; ?>',
                                            title: '<?php echo $msg; ?>',
                                            showConfirmButton: false,
                                            timer: 3000
                                        });
                                    </script>
                                <?php endif; ?>
                                <input type="text" name="search"
                                    value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                    class="block w-full pl-10 pr-3 py-2 bg-gray-800 border border-gray-700 rounded-xl placeholder-gray-400 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent"
                                    placeholder="Search orders...">
                            </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="export_orders.php"
                            class="inline-flex items-center px-4 py-2 border border-gray-600 text-gray-300 rounded-xl hover:bg-gray-700/30 transition-colors">
                            <i class="fas fa-file-export mr-2"></i> Export
                        </a>
                        <!-- <button type="button"
                            class="inline-flex items-center px-4 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">
                            <i class="fas fa-plus mr-2"></i> Create Order
                        </button> -->
                    </div>
                </div>

                <!-- Filter Options -->
                <div class="bg-gray-800 p-4 rounded-xl border border-gray-700 mb-6">

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Status Filter -->
                        <div>
                            <label for="status-filter"
                                class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                            <select id="status-filter" name="status"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                                <option value="">All Statuses</option>
                                <?php foreach ($orderStatuses as $status): ?>
                                    <option value="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>"
                                        <?= (isset($_GET['status']) && $_GET['status'] === $status) ? 'selected' : '' ?>>
                                        <?= formatStatus($status) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Restaurant Filter -->
                        <div>
                            <label for="restaurant-filter"
                                class="block text-sm font-medium text-gray-300 mb-2">Restaurant</label>
                            <select id="restaurant-filter" name="restaurant"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                                <option value="">All Restaurants</option>
                                <?php foreach ($restaurants as $restaurant): ?>
                                    <option
                                        value="<?php echo htmlspecialchars($restaurant['name'], ENT_QUOTES, 'UTF-8'); ?>"
                                        <?php echo isset($_GET['restaurant']) && htmlspecialchars($_GET['restaurant']) == $restaurant['name'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($restaurant['name'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="date-from" class="block text-sm font-medium text-gray-300 mb-2">Date
                                From</label>
                            <input type="date" id="date-from" name="date_from"
                                value="<?php echo $_GET['date_from'] ?? ''; ?>"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                        </div>
                        <div>
                            <label for="date-to" class="block text-sm font-medium text-gray-300 mb-2">Date
                                To</label>
                            <input type="date" id="date-to" name="date_to" value="<?php echo $_GET['date_to'] ?? ''; ?>"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-3">
                        <button type="submit"
                            class="px-6 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">Apply
                            Filters</button>
                        <a href="orders.php"
                            class="px-6 py-2 border border-gray-600 text-gray-300 rounded-xl hover:bg-gray-700/30 transition-colors">Reset</a>
                    </div>
                    </form>
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
                            <?php if (empty($result)): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-400">
                                        No records found.
                                    </td>
                                </tr>
                            <?php else: ?>
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
                                                <button
                                                    onclick="window.location.href='orders.php?view_order=<?php echo urlencode($row['order_id']); ?>'"
                                                    class="text-blue-400 hover:text-blue-300">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button
                                                    onclick="openModal('<?php echo $row['order_id']; ?>', '<?php echo $row['status']; ?>')"
                                                    class="text-accent hover:text-accent/80 transition-colors">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <!-- <button onclick="openDeleteModal('<?php echo $row['order_id']; ?>')"
                                                class="text-red-500 hover:text-red-400 transition-colors">
                                                <i class="fas fa-trash"></i>
                                            </button> -->
                                            </div>
                                        </td>
                                    </tr>

                                    <?php
                                }
                                ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <!-- <div
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
                </div> -->

                <!-- Order Details Modal -->
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center <?php echo isset($orderDetails) ? '' : 'hidden'; ?> z-50"
                    id="order-details-modal">
                    <div
                        class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto custom-scrollbar">
                        <div class="flex justify-between items-center mb-6">
                            <h1 class="text-2xl font-bold text-accent">
                                Order Details - <?php echo htmlspecialchars($orderDetails['order_id'] ?? ''); ?>
                            </h1>
                            <button onclick="window.location.href='orders.php'"
                                class="text-gray-400 hover:text-accent transition-colors">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>

                        <div class="space-y-5">
                            <!-- Order Overview -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-700/30 p-4 rounded-xl">
                                <div>
                                    <p class="text-sm text-gray-400">Customer</p>
                                    <p class="text-white font-medium">
                                        <?php echo htmlspecialchars($orderDetails['customer_first_name'] . ' ' . $orderDetails['customer_last_name']); ?>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Restaurant</p>
                                    <p class="text-white font-medium">
                                        <?php echo htmlspecialchars($orderDetails['restaurant_name']); ?>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Order Date</p>
                                    <p class="text-white font-medium">
                                        <?php echo date('M d, Y h:i A', strtotime($orderDetails['order_date'])); ?>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Payment Method</p>
                                    <p class="text-white font-medium">
                                        <?php echo htmlspecialchars($orderDetails['payment_method']); ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Status & Delivery -->
                            <div class="bg-gray-700/30 p-4 rounded-xl">
                                <h3 class="text-lg font-semibold text-accent mb-3">Order Status</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-400">Order Status</p>
                                        <span
                                            class="px-2.5 py-1 rounded-full text-xs <?= $statusClasses[$orderDetails['order_status']] ?? 'bg-gray-600' ?>">
                                            <?= formatStatus($orderDetails['order_status']) ?>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-400">Delivery Address</p>
                                        <p class="text-white">
                                            <?= htmlspecialchars($orderDetails['delivery_address']) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <div class="bg-gray-700/30 p-4 rounded-xl">
                                <h3 class="text-lg font-semibold text-accent mb-3">Order Items</h3>
                                <div class="border border-gray-600 rounded-lg overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-600">
                                        <thead class="bg-gray-700">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Item
                                                </th>
                                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Qty
                                                </th>
                                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Price
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-600">
                                            <?php
                                            $items = explode(', ', $orderDetails['items']);
                                            $quantities = explode(', ', $orderDetails['quantities']);
                                            $prices = explode(', ', $orderDetails['prices']);

                                            for ($i = 0; $i < count($items); $i++) {
                                                echo '<tr class="hover:bg-gray-600/20">';
                                                echo '<td class="px-4 py-3 text-sm text-white">' . htmlspecialchars($items[$i]) . '</td>';
                                                echo '<td class="px-4 py-3 text-sm text-white">' . htmlspecialchars($quantities[$i]) . '</td>';
                                                echo '<td class="px-4 py-3 text-sm text-white">₹' . htmlspecialchars($prices[$i]) . '</td>';
                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Payment Summary -->
                            <div class="bg-gray-700/30 p-4 rounded-xl">
                                <h3 class="text-lg font-semibold text-accent mb-3">Payment Summary</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-400">Items Total</span>
                                        <span
                                            class="text-white">₹<?= htmlspecialchars($orderDetails['total_amount']) ?></span>
                                    </div>
                                    <div class="flex justify-between border-t border-gray-600 pt-2">
                                        <span class="text-gray-400 font-medium">Total Amount</span>
                                        <span
                                            class="text-accent font-medium">₹<?= htmlspecialchars($orderDetails['total_amount']) ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4">
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm text-gray-400">Need help with this order?</span>
                                    <a href="#" class="text-accent hover:text-accent/80 text-sm">Contact Support</a>
                                </div>
                                <div class="flex justify-end space-x-3">
                                    <button onclick="window.location.href='orders.php'"
                                        class="px-6 py-2 border border-gray-600 text-gray-300 rounded-xl hover:bg-gray-700/30 transition-colors">
                                        Close
                                    </button>
                                    <a href="print_receipt.php?payment_id=<?= htmlspecialchars($orderDetails['payment_id'], ENT_QUOTES, 'UTF-8') ?>"
                                        class="px-6 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">
                                        Print Invoice
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    /* Custom Scrollbar */
                    .custom-scrollbar::-webkit-scrollbar {
                        width: 8px;
                    }

                    .custom-scrollbar::-webkit-scrollbar-track {
                        background: #2d3748;
                        border-radius: 10px;
                    }

                    .custom-scrollbar::-webkit-scrollbar-thumb {
                        background: #eab308;
                        /* yellow-500 */
                        border-radius: 10px;
                    }

                    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                        background: #d97706;
                        /* Slightly darker yellow */
                    }
                </style>
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