<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
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

require_once '../dbCon.php';
$foodies = new Foodies();

// Initialize variables
$filters = [
    'rating' => $_GET['rating'] ?? '',
    'restaurant' => $_GET['restaurant'] ?? '',
    'status' => $_GET['status'] ?? '',
    'date_from' => $_GET['date_from'] ?? '',
    'date_to' => $_GET['date_to'] ?? '',
    'search' => $_GET['search'] ?? ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_review'])) {
    try {
        $reviewId = $_POST['review_id'];
        $newStatus = $_POST['status'];
        $foodies->updateReviewStatus($reviewId, $newStatus);
        $_SESSION['success'] = "Review status updated successfully!";
    } catch (Exception $e) {
        $_SESSION['error'] = "Error updating review: " . $e->getMessage();
    }
    header("Location: reviews.php");
    exit();
}

try {
    $restaurants = $foodies->getAllRestaurants();
    $reviews = $foodies->getFilteredReviews($filters);
    $allReviews = $foodies->getAllReviewsWithDetails();
} catch (Exception $e) {
    die("Error loading reviews: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
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


        function openModal(userId, status) {
            document.getElementById('review_id').value = userId;
            document.getElementById('status').value = status;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #2d3748;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #eab308;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #d97706;
        }
    </style>
</head>

<body class="bg-primary text-gray-100">
    <div class="flex h-screen overflow-hidden">
        <?php include 'sidebar.php'; ?>

        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <?php include 'header.php'; ?>

            <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
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
                <form action="reviews.php" method="GET">
                    <!-- Search and Filters -->
                    <div class="flex flex-col md:flex-row justify-between mb-6 gap-4">
                        <div class="w-full md:w-1/3">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" name="search" value="<?= htmlspecialchars($filters['search']) ?>"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-700 rounded-xl bg-gray-800 placeholder-gray-400 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent"
                                    placeholder="Search reviews...">
                            </div>
                        </div>
                        <!-- <div class="flex space-x-2">
                            <button type="submit" name="export"
                                class="inline-flex items-center px-4 py-2 border border-gray-700 rounded-xl text-gray-300 bg-gray-800 hover:bg-gray-700/30 font-medium transition-colors">
                                <i class="fas fa-file-export mr-2"></i> Export
                            </button>
                            <a href="add_review.php"
                                class="inline-flex items-center px-4 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">
                                <i class="fas fa-plus mr-2"></i> Add Review
                            </a>
                        </div> -->
                    </div>

                    <!-- Filter Form -->
                    <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-xl mb-6">
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full md:w-1/4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">Rating</label>
                                <select name="rating"
                                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-xl text-gray-300 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/50">
                                    <option value="">All Ratings</option>
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <option value="<?= $i ?>" <?= $filters['rating'] == $i ? 'selected' : '' ?>>
                                            <?= str_repeat('★ ', $i) ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <div class="w-full md:w-1/4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">Restaurant</label>
                                <select name="restaurant"
                                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-xl text-gray-300 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/50">
                                    <option value="">All Restaurants</option>
                                    <?php foreach ($restaurants as $restaurant): ?>
                                        <option value="<?= $restaurant['restaurant_id'] ?>"
                                            <?= $filters['restaurant'] == $restaurant['restaurant_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($restaurant['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="w-full md:w-1/4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                                <select name="status"
                                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-xl text-gray-300 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/50">
                                    <option value="">All Statuses</option>
                                    <option value="published" <?= $filters['status'] == 'published' ? 'selected' : '' ?>>
                                        Published</option>
                                    <option value="archived" <?= $filters['status'] == 'archived' ? 'selected' : '' ?>>
                                        Archived
                                    </option>
                                </select>
                            </div>

                            <div class="w-full md:w-1/4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">Date From</label>
                                <input type="date" name="date_from" value="<?= $filters['date_from'] ?>"
                                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-xl text-gray-300 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/50" />
                            </div>
                            <div class="w-full md:w-1/4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">Date To</label>
                                <input type="date" name="date_to" value="<?= $filters['date_to'] ?>"
                                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-xl text-gray-300 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/50"
                                    placeholder="End Date" />
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end gap-3">
                            <a href="reviews.php"
                                class="px-4 py-2 border border-gray-600 rounded-xl text-gray-300 hover:bg-gray-700/30 transition-colors">
                                Reset
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Reviews Table -->
                <div class="bg-gray-800 rounded-xl shadow-xl border border-gray-700 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Customer
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Restaurant
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Rating</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Review</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Date</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-300 uppercase">Status</th>
                                <th class="px-6 py-4 text-right text-sm font-medium text-gray-300 uppercase">Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            <?php if (empty($reviews)): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-400">
                                        No records found.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($reviews as $review): ?>
                                    <tr class="hover:bg-gray-700/20 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full border-2 border-accent/30"
                                                        src="<?= $review['profile_pic'] ?: 'https://via.placeholder.com/40' ?>"
                                                        alt="User avatar">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-white">
                                                        <?= htmlspecialchars($review['first_name'] . ' ' . $review['last_name']) ?>
                                                    </div>
                                                    <div class="text-xs text-gray-400">
                                                        <?= htmlspecialchars($review['email']) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-300">
                                            <?= htmlspecialchars($review['restaurant_name']) ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-accent">
                                                <?= str_repeat('<i class="fas fa-star"></i>', $review['rating']) ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-300 max-w-xs truncate">
                                            <?= htmlspecialchars($review['review_text']) ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-300">
                                            <?= date('M j, Y', strtotime($review['created_at'])) ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full <?= $review['status'] === 'published' ? 'bg-green-500/20 text-green-500' : 'bg-gray-500/20 text-gray-400' ?>">
                                                <?= ucfirst($review['status']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end space-x-3">
                                                <button class="text-blue-500 hover:text-accent/80 view-btn"
                                                    data-id="<?= $review['review_id'] ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button
                                                    onclick="openModal('<?php echo $review['review_id']; ?>', '<?php echo $review['status']; ?>')"
                                                    class="text-accent hover:text-accent/80 transition-colors">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button onclick="openDeleteModal('<?php echo $review['review_id']; ?>')"
                                                    class="text-red-500 hover:text-red-400 transition-colors">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
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
                                Showing <span class="font-medium text-white"><?= $offset + 1 ?></span> to
                                <span
                                    class="font-medium text-white"><?= min($offset + $perPage, $totalReviews) ?></span>
                                of
                                <span class="font-medium text-white"><?= $totalReviews ?></span> results
                            </p>
                        </div>
                        <nav class="relative z-0 inline-flex rounded-xl shadow-sm -space-x-px">
                            <?php if ($currentPage > 1): ?>
                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $currentPage - 1])) ?>"
                                    class="px-3 py-2 rounded-l-xl border border-gray-700 bg-gray-800 text-gray-400 hover:bg-gray-700">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"
                                    class="<?= $i == $currentPage ? 'bg-accent/20 text-accent' : 'text-gray-400 hover:bg-gray-700' ?> px-4 py-2 border border-gray-700">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($currentPage < $totalPages): ?>
                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $currentPage + 1])) ?>"
                                    class="px-3 py-2 rounded-r-xl border border-gray-700 bg-gray-800 text-gray-400 hover:bg-gray-700">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php endif; ?>
                        </nav>
                    </div>
                </div> -->

                <!-- Review Details Modal -->
                <div id="reviewModal"
                    class="fixed flex inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50">
                    <?php foreach ($reviews as $review): ?>
                        <div id="reviewModal-<?= $review['review_id'] ?>"
                            class="hidden bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl w-full max-w-2xl mx-auto  max-h-[90vh] overflow-y-auto custom-scrollbar">
                            <div class="flex justify-between items-center mb-6">
                                <h1 class="text-2xl font-bold text-accent">Review Details</h1>
                                <button onclick="closeReviewModal()" class="text-gray-400 hover:text-accent">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>

                            <div class="space-y-5">
                                <!-- Customer Profile Section -->
                                <div class="flex flex-col items-center text-center mb-6">
                                    <img class="w-24 h-24 rounded-full border-4 border-accent/30 mb-4"
                                        src="<?= htmlspecialchars($review['profile_pic'] ?: 'https://via.placeholder.com/100') ?>"
                                        alt="User avatar">
                                    <div>
                                        <h2 class="text-xl font-semibold text-white">
                                            <?= htmlspecialchars($review['first_name'] . ' ' . $review['last_name']) ?>
                                        </h2>
                                        <p class="text-sm text-gray-400">
                                            <?= htmlspecialchars($review['email']) ?>
                                        </p>
                                    </div>
                                </div>

                                <!-- Review Overview -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-700/30 p-4 rounded-xl">
                                    <div>
                                        <p class="text-sm text-gray-400">Restaurant</p>
                                        <p class="text-white font-medium">
                                            <?= htmlspecialchars($review['restaurant_name']) ?>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-400">Rating</p>
                                        <p class="text-accent text-lg">
                                            <?= str_repeat('★', $review['rating']) ?>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-400">Review Date</p>
                                        <p class="text-white font-medium">
                                            <?= date('M d, Y', strtotime($review['created_at'])) ?>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-400">Status</p>
                                        <span
                                            class="px-2.5 py-1 rounded-full text-xs <?= $review['status'] === 'published' ? 'bg-green-900/30 text-green-400' : 'bg-gray-600/30 text-gray-400' ?>">
                                            <?= ucfirst($review['status']) ?>
                                        </span>
                                    </div>
                                </div>

                                <!-- Review Content -->
                                <div class="bg-gray-700/30 p-4 rounded-xl">
                                    <h3 class="text-lg font-semibold text-accent mb-3">Review Content</h3>
                                    <p class="text-gray-300 ">
                                        <?= htmlspecialchars($review['review_text']) ?>
                                    </p>
                                </div>

                                <!-- Actions -->
                                <div class="flex justify-end space-x-3 pt-4">
                                    <button onclick="closeReviewModal()"
                                        class="px-6 py-2 border border-gray-600 text-gray-300 rounded-xl hover:bg-gray-700/30">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <script>
                    // Add this script section
                    function openReviewModal(reviewId) {
                        document.getElementById('reviewModal').classList.remove('hidden');
                        document.getElementById(`reviewModal-${reviewId}`).classList.remove('hidden');
                    }

                    function closeReviewModal() {
                        document.getElementById('reviewModal').classList.add('hidden');
                        document.querySelectorAll('[id^="reviewModal-"]').forEach(modal => {
                            modal.classList.add('hidden');
                        });
                    }

                    // Update your eye button in the table row
                    <?php foreach ($reviews as $review): ?>
                        document.querySelector(`button[data-id="<?= $review['review_id'] ?>"]`).addEventListener('click', () => {
                            openReviewModal(<?= $review['review_id'] ?>);
                        });
                    <?php endforeach; ?>

                    // Close modal when clicking outside
                    document.getElementById('reviewModal').addEventListener('click', (e) => {
                        if (e.target === document.getElementById('reviewModal')) {
                            closeReviewModal();
                        }
                    });
                </script>

                <div id="deleteModal"
                    class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50">
                    <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl w-full max-w-md mx-4">
                        <div class="flex justify-between items-center mb-6">
                            <h1 class="text-2xl font-bold text-accent">Delete Review</h1>
                            <button onclick="closeDeleteModal()"
                                class="text-gray-400 hover:text-accent transition-colors">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>

                        <form method="POST" action="delete_review.php" class="space-y-6">
                            <input type="hidden" id="delete_user_id" name="review_id">

                            <p class="text-gray-300">Are you sure you want to delete this Review?</p>

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

                <!-- Edit reviews Modal -->
                <div id="editModal"
                    class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50">
                    <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl w-full max-w-md mx-4">
                        <div class="flex justify-between items-center mb-6">
                            <h1 class="text-2xl font-bold text-accent">Edit Review Status</h1>
                            <button onclick="closeModal()" class="text-gray-400 hover:text-accent transition-colors">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>

                        <form method="POST" action="updateReview.php" class="space-y-6">
                            <input type="hidden" id="review_id" name="review_id">

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-3">Status</label>
                                <div class="relative">
                                    <select id="status" name="status"
                                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 appearance-none focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/50 transition-all">
                                        <option value="published" class="bg-gray-800 text-gray-300">Published</option>
                                        <option value="archived" class="bg-gray-800 text-gray-300">Archived</option>
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
                                    Update Review
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // View Review Modal
            document.querySelectorAll('.view-btn').forEach(button => {
                button.addEventListener('click', async () => {
                    const reviewId = button.dataset.id;
                    try {
                        const response = await fetch(`get_review.php?id=${reviewId}`);
                        const data = await response.json();

                        const modalContent = document.getElementById('modal-content');
                        modalContent.innerHTML = `
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4">
                            <img src="${data.profile_pic || 'https://via.placeholder.com/40'}" 
                                 class="w-12 h-12 rounded-full border-2 border-accent/30">
                            <div>
                                <h2 class="text-lg font-semibold">${data.first_name} ${data.last_name}</h2>
                                <p class="text-gray-400 text-sm">${data.email}</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <p class="font-medium">Restaurant: ${data.restaurant_name}</p>
                            <div class="text-accent">
                                ${'<i class="fas fa-star"></i>'.repeat(data.rating)}
                            </div>
                            <p class="text-gray-300">${data.review_text}</p>
                            <p class="text-sm text-gray-400">Date: ${new Date(data.created_at).toLocaleDateString()}</p>
                        </div>
                    </div>
                `;
                        document.getElementById('review-details-modal').classList.remove('hidden');
                    } catch (error) {
                        console.error('Error fetching review:', error);
                    }
                });
            });

            // Close Modal
            document.querySelectorAll('.close-modal').forEach(button => {
                button.addEventListener('click', () => {
                    document.getElementById('review-details-modal').classList.add('hidden');
                });
            });

            // Delete Review
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', async () => {
                    if (confirm('Are you sure you want to delete this review?')) {
                        const reviewId = button.dataset.id;
                        try {
                            const response = await fetch(`delete_review.php?id=${reviewId}`, {
                                method: 'DELETE'
                            });

                            if (response.ok) {
                                button.closest('tr').remove();
                            } else {
                                alert('Error deleting review');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>