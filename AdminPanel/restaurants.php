<?php
session_start();
require '../dbCon.php';
$obj = new Foodies();
$msg = '';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

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

$filters = [
    'search' => $_GET['search'] ?? '',
    'status' => $_GET['status'] ?? ''
];

$restaurants_data = $obj->getFilteredRestaurants($filters);

// $restaurants = $obj->getFilteredRestaurants($filters);
$statuses = $obj->getAllRestaurantStatuses();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Management | Food Ordering System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function openDeleteModal(userId) {
            document.getElementById('delete_user_id').value = userId;
            document.getElementById('deleteModal').classList.remove('hidden');
        }
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
</head>

<body class="bg-primary text-gray-100">
    <div class="flex h-screen overflow-hidden">
        <?php include 'sidebar.php'; ?>

        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <?php include 'header.php'; ?>

            <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
                <!-- Search and Add Restaurant -->
                <form action="restaurants.php" method="GET">
                    <div class="flex flex-col md:flex-row justify-between mb-6 gap-4 items-center">
                        <div class="flex flex-1 gap-4">
                            <div class="relative w-full md:w-1/3">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" name="search" value="<?= htmlspecialchars($filters['search']) ?>"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-700 rounded-xl bg-gray-800 placeholder-gray-400 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent"
                                    placeholder="Search restaurants...">
                            </div>
                            <div class="w-full md:w-1/4">
                                <select id="status-filter" name="status"
                                    class="block w-full pl-3 pr-3 py-2 border border-gray-700 rounded-xl bg-gray-800 placeholder-gray-400 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent">
                                    <option value="">All Statuses</option>
                                    <?php foreach ($statuses as $status): ?>
                                        <option value="<?= $status ?>" <?= $filters['status'] === $status ? 'selected' : '' ?>>
                                            <?= ucfirst($status) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit"
                                class="px-4 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">
                                Apply Filter
                            </button>
                            <a href="restaurants.php"
                                class="px-4 py-2 border border-gray-600 rounded-xl text-gray-300 hover:bg-gray-700/30 transition-colors">
                                Reset
                            </a>
                        </div>
                        <?php if (!empty($msg)): ?>
                            <script>
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: '<?php echo $icon; ?>', // Use the dynamic icon
                                    title: '<?php echo $msg; ?>',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            </script>
                        <?php endif; ?>
                        <a href="addRestaurant.php"
                            class="inline-flex items-center px-4 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">
                            <i class="fas fa-plus mr-2"></i> Add Restaurant
                        </a>
                    </div>
                </form>

                <!-- Restaurants Table -->
                <div class="bg-gray-800 rounded-xl shadow-xl border border-gray-700 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-800">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">
                                    Restaurant</th>
                                <!-- <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">
                                    Owner</th> -->
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">
                                    Contact</th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">
                                    Address</th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">
                                    Status</th>
                                <th scope="col"
                                    class="px-6 py-4 text-right text-sm font-medium text-gray-300 uppercase">Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            <!-- Restaurant Row -->
                            <?php if (empty($restaurants_data)): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-400">
                                        No records found.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php
                                foreach ($restaurants_data as $restaurant) {

                                    ?>
                                    <tr class="hover:bg-gray-700/20 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-12 w-12">
                                                    <img class="h-12 w-12 rounded-full border-2 border-accent/30 object-cover"
                                                        src="<?php echo $restaurant['restaurant_pic']; ?>" alt="Burger King">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-white">
                                                        <?php echo $restaurant['name']; ?>
                                                    </div>
                                                    <div class="text-xs text-gray-400">Fast Food</div>
                                                </div>
                                            </div>
                                        </td>
                                        <!-- <td class="px-6 py-4">
                                    <div class="text-sm text-gray-300">Michael Johnson</div>
                                    <div class="text-xs text-gray-500">michael@example.com</div>
                                </td> -->
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-300"><?php echo $restaurant['phone']; ?></div>
                                            <div class="text-xs text-gray-500"></div><?php echo $restaurant['city']; ?>
                        </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-gray-500"></div><?php echo $restaurant['address']; ?>
                </div>
                <div class="text-sm text-gray-300"><?php echo $restaurant['zip_code']; ?></div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <span class="w-2 h-2 rounded-full mr-2 <?php
                        if ($restaurant['status'] == "open"):
                            echo 'bg-green-500';
                        elseif ($restaurant['status'] == "closed"):
                            echo 'bg-red-500';
                        else:
                            echo 'bg-yellow-500';
                        endif;
                        ?>"></span>
                        <span class="text-sm text-gray-300"><?php echo $restaurant['status']; ?></span>
                    </div>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end space-x-3">
                        <a href="updateRestaurant.php?id=<?php echo $restaurant['restaurant_id'] ?>"
                            class="text-accent hover:text-accent/80 transition-colors">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="openDeleteModal('<?php echo $restaurant['restaurant_id']; ?>')"
                            class="text-red-500 hover:text-red-400 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                        <a href="viewRestaurant.php?id=<?php echo $restaurant['restaurant_id'] ?>"
                            class="text-red-500 hover:text-red-400 transition-colors">
                            <i class="fas fa-eye"></i>
                        </a>
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

    <!-- Pagination
    <div class="bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-700 mt-4 rounded-xl">
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-400">
                    Showing <span class="font-medium text-white">1</span> to
                    <span class="font-medium text-white">5</span> of
                    <span class="font-medium text-white">12</span> results
                </p>
            </div>
            <nav class="relative z-0 inline-flex rounded-xl shadow-sm -space-x-px">
                <a href="#"
                    class="px-3 py-2 rounded-l-xl border border-gray-700 bg-gray-800 text-gray-400 hover:bg-gray-700">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <a href="#" aria-current="page" class="px-4 py-2 border border-accent bg-accent/20 text-accent">
                    1
                </a>
                <a href="#" class="px-4 py-2 border border-gray-700 text-gray-400 hover:bg-gray-700">
                    2
                </a>
                <a href="#" class="px-4 py-2 border border-gray-700 text-gray-400 hover:bg-gray-700">
                    3
                </a>
                <a href="#"
                    class="px-3 py-2 rounded-r-xl border border-gray-700 bg-gray-800 text-gray-400 hover:bg-gray-700">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </nav>
        </div>
    </div> -->

    <div id="deleteModal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50">
        <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-accent">Delete User</h1>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-accent transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form method="POST" action="deleteRestaurant.php" class="space-y-6">
                <input type="hidden" id="delete_user_id" name="restaurant_id">

                <p class="text-gray-300">Are you sure you want to delete this Restaurant?</p>

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
    </main>
    </div>
    </div>
</body>

</html>