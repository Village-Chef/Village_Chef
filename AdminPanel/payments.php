<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}

require '../dbCon.php';
$obj = new Foodies();

$filters = [
    'status' => $_GET['status'] ?? '',
    'payment_method' => $_GET['payment_method'] ?? '',
    'date_from' => $_GET['date_from'] ?? '',
    'date_to' => $_GET['date_to'] ?? '',
    'amount_range' => $_GET['amount_range'] ?? '',
    'search' => $_GET['search'] ?? '' // Add the search parameter
];


$paymentDetails = null;
if (isset($_GET['payment_id'])) {
    try {
        $paymentDetails = $obj->getPaymentDetails($_GET['payment_id']);
        if ($paymentDetails) {
            $orderItems = $obj->getOrderItems($paymentDetails['order_id']);

            // print_r($orderItems);

            $grand_total = $paymentDetails['amount'];

            $platform_fee = 6;
            $delivery_charge = 22;
            $fixed_charges = $platform_fee + $delivery_charge;

            $actual_total = ($grand_total - $fixed_charges) / 1.09;
            $tax = $actual_total * 0.09;
        }
    } catch (Exception $e) {
        echo "<script>console.error('Error fetching payment details: " . $e->getMessage() . "');</script>";
        $paymentDetails = null;
    }
}
// Fetch payments
$payments = $obj->getAllPayments($filters);

// Fetch payment methods and statuses
$paymentMethods = $obj->getPaymentMethods();
$paymentStatuses = $obj->getPaymentStatuses();
$paymentSummary = $obj->getPaymentSummary();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments Management | Food Ordering System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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

        function closeModal() {
            document.getElementById('paymentDetailModal').classList.add('hidden');
            const url = new URL(window.location.href);
            url.searchParams.delete('payment_id');
            window.history.pushState({}, '', url);
        }

        function openPaymentModal(paymentId) {
            window.location.href = "payments.php?payment_id=" + paymentId;
        }

    </script>
</head>

<body class="bg-primary text-gray-100">
    <div class="flex h-screen overflow-hidden">
        <?php include 'sidebar.php'; ?>

        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <?php include 'header.php'; ?>

            <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
                <form method="GET" action="payments.php">
                    <!-- Search and Export -->
                    <div class="flex flex-col md:flex-row justify-between mb-6 gap-4">
                        <div class="w-full md:w-1/3">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" name="search"
                                    value="<?= htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
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
                        <!-- Total Payments -->
                        <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-900/30 rounded-full p-3">
                                    <i class="fas fa-dollar-sign text-green-400 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-400">Total Payments</p>
                                    <h3 class="text-xl font-semibold text-white">
                                        ₹<?php echo number_format($paymentSummary['total_amount'], 2); ?>
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <!-- Successful Payments -->
                        <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-900/30 rounded-full p-3">
                                    <i class="fas fa-check-circle text-blue-400 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-400">Successful</p>
                                    <h3 class="text-xl font-semibold text-white">
                                        <?php echo $paymentSummary['successful_payments']; ?>
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <!-- Failed Payments -->
                        <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-red-900/30 rounded-full p-3">
                                    <i class="fas fa-times-circle text-red-400 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-400">Failed</p>
                                    <h3 class="text-xl font-semibold text-white">
                                        <?php echo $paymentSummary['failed_payments']; ?>
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <!-- Refunded Payments -->
                        <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-yellow-900/30 rounded-full p-3">
                                    <i class="fas fa-undo text-yellow-400 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-400">Refunded</p>
                                    <h3 class="text-xl font-semibold text-white">
                                        <?php echo $paymentSummary['refunded_payments']; ?>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Options -->
                    <div class="bg-gray-800 p-4 rounded-xl border border-gray-700 mb-6">

                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <!-- Status Filter -->
                            <div>
                                <label for="status-filter"
                                    class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                                <select id="status-filter" name="status"
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                                    <option value="">All Statuses</option>
                                    <?php foreach ($paymentStatuses as $status): ?>
                                        <option value="<?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?>"
                                            <?= isset($_GET['status']) && $_GET['status'] === $status ? 'selected' : '' ?>>
                                            <?= ucfirst($status) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Payment Method Filter -->
                            <div>
                                <label for="payment-method-filter"
                                    class="block text-sm font-medium text-gray-300 mb-2">Payment Method</label>
                                <select id="payment-method-filter" name="payment_method"
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                                    <option value="">All Methods</option>
                                    <?php foreach ($paymentMethods as $method): ?>
                                        <option value="<?= htmlspecialchars($method, ENT_QUOTES, 'UTF-8') ?>"
                                            <?= isset($_GET['payment_method']) && $_GET['payment_method'] === $method ? 'selected' : '' ?>>
                                            <?= ucfirst($method) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Date From Filter -->
                            <div>
                                <label for="date-from" class="block text-sm font-medium text-gray-300 mb-2">Date
                                    From</label>
                                <input type="date" id="date-from" name="date_from"
                                    value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>"
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                            </div>

                            <!-- Date To Filter -->
                            <div>
                                <label for="date-to" class="block text-sm font-medium text-gray-300 mb-2">Date
                                    To</label>
                                <input type="date" id="date-to" name="date_to"
                                    value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>"
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                            </div>

                            <!-- Amount Range Filter -->
                            <div>
                                <label for="amount-filter" class="block text-sm font-medium text-gray-300 mb-2">Amount
                                    Range</label>
                                <select id="amount-filter" name="amount_range"
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent">
                                    <option value="">All Amounts</option>
                                    <option value="99-199" <?= isset($_GET['amount_range']) && $_GET['amount_range'] === '99-199' ? 'selected' : '' ?>>₹99 - ₹199</option>
                                    <option value="200-299" <?= isset($_GET['amount_range']) && $_GET['amount_range'] === '200-299' ? 'selected' : '' ?>>₹200 - ₹299</option>
                                    <option value="300-399" <?= isset($_GET['amount_range']) && $_GET['amount_range'] === '300-399' ? 'selected' : '' ?>>₹300 - ₹399</option>
                                    <option value="400-599" <?= isset($_GET['amount_range']) && $_GET['amount_range'] === '400-599' ? 'selected' : '' ?>>₹400 - ₹599</option>
                                    <option value="600+" <?= isset($_GET['amount_range']) && $_GET['amount_range'] === '600+' ? 'selected' : '' ?>>₹600+</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end space-x-3">
                            <button type="submit"
                                class="px-6 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">Apply
                                Filters</button>
                            <a href="payments.php"
                                class="px-6 py-2 border border-gray-600 text-gray-300 rounded-xl hover:bg-gray-700/30 transition-colors">Reset</a>
                        </div>
                </form>
        </div>

        <!-- Payments Table -->
        <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Payment ID
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Order ID
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Customer
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Amount</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Payment
                            Method</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Date</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-gray-300 uppercase">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php if (!empty($payments)): ?>
                        <?php foreach ($payments as $payment): ?>
                            <tr class="hover:bg-gray-700/20 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-white">
                                        #PAY-<?php echo htmlspecialchars($payment['payment_id']); ?></div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">
                                    <?php echo htmlspecialchars($payment['order_id']); ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-white">
                                        <?php echo htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']); ?>
                                    </div>
                                    <div class="text-sm text-gray-400">
                                        <?php echo htmlspecialchars($payment['email']); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-accent font-medium">
                                    ₹ <?php echo number_format($payment['amount'], 2); ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <?php if (strpos($payment['payment_method'], 'card') !== false): ?>
                                            <i class="fas fa-credit-card text-gray-400"></i>
                                        <?php elseif ($payment['payment_method'] === 'paypal'): ?>
                                            <i class="fab fa-paypal text-blue-400"></i>
                                        <?php elseif ($payment['payment_method'] === 'apple-pay'): ?>
                                            <i class="fab fa-apple text-gray-400"></i>
                                        <?php elseif ($payment['payment_method'] === 'wallet'): ?>
                                            <i class="bi bi-wallet2 text-red-400"></i>
                                        <?php else: ?>
                                            <i class="bi bi-cash-coin text-green-400"></i>
                                        <?php endif; ?>
                                        <span class="text-sm text-gray-300">
                                            <?php echo htmlspecialchars(ucfirst(str_replace('-', ' ', $payment['payment_method']))); ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs 
                                <?php echo $payment['payment_status'] === 'successful' ? 'bg-green-900/30 text-green-400' : ($payment['payment_status'] === 'failed' ? 'bg-red-900/30 text-red-400' : 'bg-yellow-900/30 text-yellow-400'); ?>">
                                        <?php echo ucfirst($payment['payment_status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">
                                    <?php echo date('M d, Y', strtotime($payment['payment_date'])); ?><br>
                                    <span
                                        class="text-xs"><?php echo date('h:i A', strtotime($payment['payment_date'])); ?></span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end space-x-3">
                                        <button
                                            onclick="openPaymentModal('<?= htmlspecialchars($payment['payment_id'], ENT_QUOTES, 'UTF-8') ?>')"
                                            class="text-blue-400 hover:text-blue-300">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="text-yellow-400 hover:text-yellow-300"><i
                                                class="fas fa-undo"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-400">No payments found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        </main>
    </div>
    </div>

    <div id="paymentDetailModal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center <?= isset($_GET['payment_id']) && $paymentDetails ? '' : 'hidden' ?> z-50">
        <?php if ($paymentDetails): ?>
            <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl w-full max-w-2xl mx-4">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-accent">Payment Details</h1>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-accent transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="space-y-5">
                    <!-- Payment Overview -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-700/30 p-4 rounded-xl">
                        <div>
                            <p class="text-sm text-gray-400">Payment ID</p>
                            <p class="text-white font-medium">#PAY-<?= htmlspecialchars($paymentDetails['payment_id']) ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Order ID</p>
                            <p class="text-white font-medium"><?= htmlspecialchars($paymentDetails['order_id']) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Date & Time</p>
                            <p class="text-white font-medium">
                                <?= date('M d, Y h:i A', strtotime($paymentDetails['payment_date'])) ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Payment Method</p>
                            <p class="text-white font-medium">
                                <?= ucfirst(str_replace('-', ' ', $paymentDetails['payment_method'])) ?>
                            </p>
                        </div>
                    </div>

                    <!-- Customer Details -->
                    <div class="bg-gray-700/30 p-4 rounded-xl">
                        <h3 class="text-lg font-semibold text-accent mb-3">Customer Information</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-400">Name</p>
                                <p class="text-white">
                                    <?= htmlspecialchars($paymentDetails['first_name'] . ' ' . $paymentDetails['last_name']) ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Email</p>
                                <p class="text-white"><?= htmlspecialchars($paymentDetails['email']) ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Phone</p>
                                <p class="text-white"><?= htmlspecialchars($paymentDetails['phone'] ?? '-') ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400">Address</p>
                                <p class="text-white"><?= htmlspecialchars($paymentDetails['delivery_address'] ?? '-') ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Breakdown -->
                    <div class="bg-gray-700/30 p-4 rounded-xl">
                        <h3 class="text-lg font-semibold text-accent mb-3">Payment Breakdown</h3>
                        <div class="space-y-2">


                            




                            <div class="flex justify-between border-t border-gray-600 pt-2">
                                <span class="text-gray-400">Actual Total</span>
                                <span class="text-white">₹<?= number_format($actual_total, 2); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Platform Fee</span>
                                <span class="text-white">₹<?= number_format($platform_fee, 2); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Delivery Charge</span>
                                <span class="text-white">₹<?= number_format($delivery_charge, 2); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Tax (0.09%)</span>
                                <span class="text-white">₹<?= number_format($tax, 2); ?></span>
                            </div>
                            <div class="flex justify-between border-t border-gray-600 pt-2">
                                <span class="text-gray-400 font-medium">Grand Total</span>
                                <span class="text-accent font-medium">₹<?= number_format($grand_total, 2); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Status & Actions -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-gray-400">Payment Status:</span>
                            <span class="px-2.5 py-1 rounded-full text-xs <?=
                                $paymentDetails['payment_status'] === 'successful' ? 'bg-green-900/30 text-green-400' :
                                ($paymentDetails['payment_status'] === 'failed' ? 'bg-red-900/30 text-red-400' :
                                    'bg-yellow-900/30 text-yellow-400')
                                ?>">
                                <?= ucfirst($paymentDetails['payment_status']) ?>
                            </span>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button onclick="closeModal()"
                                class="px-6 py-2 border border-gray-600 text-gray-300 rounded-xl hover:bg-gray-700/30 transition-colors">
                                Close
                            </button>
                            <a href="print_receipt.php?payment_id=<?= htmlspecialchars($paymentDetails['payment_id'], ENT_QUOTES, 'UTF-8') ?>"
                                class="px-6 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">
                                Print Receipt
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl w-full max-w-2xl mx-4">
                <h1 class="text-xl font-bold text-red-500">Payment details not found.</h1>
                <button onclick="closeModal()"
                    class="mt-4 px-6 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">
                    Close
                </button>
            </div>
        <?php endif; ?>
    </div>

</body>

</html>