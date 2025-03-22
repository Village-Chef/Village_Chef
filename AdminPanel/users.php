<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}

require '../dbCon.php';
$obj = new Foodies();

$users = $obj->getAllUsers();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management | Food Ordering System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
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
    </script>
</head>

<body class="bg-primary text-gray-100">
    <div class="flex h-screen overflow-hidden">
        <?php include 'sidebar.php'; ?>

        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <?php include 'header.php'; ?>

            <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
                <!-- Search and Add User -->
                <div class="flex flex-col md:flex-row justify-between mb-6 gap-4">
                    <div class="w-full md:w-1/3">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-700 rounded-xl bg-gray-800 placeholder-gray-400 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent"
                                placeholder="Search users...">
                        </div>
                    </div>
                    <button type="button"
                        class="inline-flex items-center px-4 py-2 border border-accent text-sm font-medium rounded-xl shadow-sm text-accent hover:bg-accent/10 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Add User
                    </button>
                </div>

                <!-- Users Table -->
                <div class="bg-gray-800 rounded-xl shadow-xl border border-gray-700 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-800">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">
                                    User</th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">
                                    Email</th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">
                                    Phone</th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">
                                    Role</th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">
                                    Status</th>
                                <th scope="col" class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">
                                    Joined</th>
                                <th scope="col"
                                    class="px-6 py-4 text-right text-sm font-medium text-gray-300 uppercase">Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-gray-700/20 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full border-2 border-accent/30"
                                                    src="<?php echo !empty($user['profile_pic']) ? $user['profile_pic'] : 'assets/dp.png'; ?>"
                                                    alt="User avatar">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-white">
                                                    <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    @
                                                    <?php echo strtolower($user['first_name']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-300"><?php echo $user['email']; ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-300"><?php echo $user['phone']; ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-accent/20 text-accent">
                                            <?php echo ucfirst($user['role']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <span
                                                class="w-2 h-2 rounded-full mr-2 <?php echo $user['status'] == 'active' ? 'bg-green-500' : 'bg-red-500'; ?>"></span>
                                            <span class="text-sm text-gray-300">
                                                <?php echo ucfirst($user['status']); ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-300">
                                        <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-3">
                                            <button
                                                onclick="openModal('<?php echo $user['user_id']; ?>', '<?php echo $user['status']; ?>')"
                                                class="text-accent hover:text-accent/80 transition-colors">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="openDeleteModal('<?php echo $user['user_id']; ?>')"
                                                class="text-red-500 hover:text-red-400 transition-colors">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div
                    class="bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-700 mt-4 rounded-xl">
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-400">
                                Showing <span class="font-medium text-white">1</span> to
                                <span class="font-medium text-white">5</span> of
                                <span class="font-medium text-white">24</span> results
                            </p>
                        </div>
                        <nav class="relative z-0 inline-flex rounded-xl shadow-sm -space-x-px">
                            <a href="#"
                                class="px-3 py-2 rounded-l-xl border border-gray-700 bg-gray-800 text-gray-400 hover:bg-gray-700">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            <a href="#" aria-current="page"
                                class="px-4 py-2 border border-accent bg-accent/20 text-accent">
                                1
                            </a>
                            <a href="#" class="px-4 py-2 border border-gray-700 text-gray-400 hover:bg-gray-700">
                                2
                            </a>
                            <a href="#" class="px-4 py-2 border border-gray-700 text-gray-400 hover:bg-gray-700">
                                3
                            </a>
                            <span class="px-4 py-2 border border-gray-700 text-gray-400">...</span>
                            <a href="#" class="px-4 py-2 border border-gray-700 text-gray-400 hover:bg-gray-700">
                                8
                            </a>
                            <a href="#"
                                class="px-3 py-2 rounded-r-xl border border-gray-700 bg-gray-800 text-gray-400 hover:bg-gray-700">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </nav>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50">
        <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-accent">Edit User</h1>
                <button onclick="closeModal()" class="text-gray-400 hover:text-accent transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form method="POST" action="editUser.php" class="space-y-6">
                <input type="hidden" id="user_id" name="user_id">

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-3">Status</label>
                    <div class="relative">
                        <select id="status" name="status"
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 appearance-none focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/50 transition-all">
                            <option value="active" class="bg-gray-800 text-gray-300">Active</option>
                            <option value="inactive" class="bg-gray-800 text-gray-300">Inactive</option>
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
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50">
        <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-accent">Delete User</h1>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-accent transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form method="POST" action="deleteUser.php" class="space-y-6">
                <input type="hidden" id="delete_user_id" name="user_id">

                <p class="text-gray-300">Are you sure you want to delete this user?</p>

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