<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}

require '../dbCon.php';
$obj = new Foodies();

$restaurants = $obj->getAllRestaurants();
$cuisines = $obj->getAllCuisines();
$tags = $obj->getAllTags();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnAddMenuItem'])) {
    $restaurant_id = $_POST['restaurant_id'];
    $cuisine_id = $_POST['cuisine_id'];
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image_url = $_FILES['image_url'];
    $is_available = isset($_POST['is_available']) ? 1 : 0;
    $selected_tags = [];
    if (isset($_POST['tags'])) {
        foreach ($_POST['tags'] as $tag_id) {
            foreach ($tags as $tag) {
                if ($tag['tag_id'] == $tag_id) {
                    $selected_tags[] = $tag['tag'];
                    break;
                }
            }
        }
    }
    $selected_tags_json = json_encode($selected_tags);

    try {
        $obj->addMenuItem($restaurant_id, $cuisine_id, $item_name, $description, $price, $image_url, $is_available, $selected_tags_json);
        header('location:menuItems.php');
        exit();
    } catch (Exception $e) {
        $error_message = "Failed to add menu item: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu Item | Food Ordering System</title>
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
    <style>
        .toggle-checkbox:checked {
            right: 0;
            border-color: #eab308;
        }

        .toggle-checkbox:checked+.toggle-label {
            background-color: #eab308;
        }

        .tag-item input:checked+label {
            background-color: #eab308;
            border-color: #eab308;
            color: #000;
        }

        .tag-item input:checked+label:hover {
            background-color: #d4a30c;
        }
    </style>
</head>

<body class="bg-primary text-gray-100">
    <div class="flex h-screen overflow-hidden">
        <?php include 'sidebar.php'; ?>

        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <?php include 'header.php'; ?>

            <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
                <div class="max-w-2xl mx-auto">
                    <h1 class="text-2xl font-bold text-accent mb-6 flex items-center">
                        <i class="fas fa-plus mr-2"></i> Add Menu Item
                    </h1>
                    <?php if (isset($error_message)): ?>
                        <div class="bg-red-500 text-white p-4 rounded mb-6">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>
                    <form method="post" action="addMenuItem.php" enctype="multipart/form-data"
                        class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-md">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="restaurant_id" class="block text-sm font-medium text-gray-300 mb-2">
                                    Restaurant <span class="text-accent">*</span>
                                </label>
                                <select id="restaurant_id" name="restaurant_id"
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent"
                                    required>
                                    <option value="">Select a restaurant</option>
                                    <?php foreach ($restaurants as $restaurant): ?>
                                        <option value="<?php echo $restaurant['restaurant_id']; ?>">
                                            <?php echo $restaurant['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="cuisine_id" class="block text-sm font-medium text-gray-300 mb-2">
                                    Cuisine <span class="text-accent">*</span>
                                </label>
                                <select id="cuisine_id" name="cuisine_id"
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent"
                                    required>
                                    <option value="">Select a cuisine</option>
                                    <?php foreach ($cuisines as $cuisine): ?>
                                        <option value="<?php echo $cuisine['cuisine_id']; ?>">
                                            <?php echo $cuisine['cuisine_name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label for="item_name" class="block text-sm font-medium text-gray-300 mb-2">
                                    Item Name <span class="text-accent">*</span>
                                </label>
                                <input type="text" id="item_name" name="item_name"
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent"
                                    placeholder="e.g., Margherita Pizza" required>
                            </div>
                            <div class="col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                                    Description
                                </label>
                                <textarea id="description" name="description" rows="4"
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent"
                                    placeholder="Describe the menu item"></textarea>
                            </div>
                            <div class="col-span-2">
                                <label for="price" class="block text-sm font-medium text-gray-300 mb-2">
                                    Price <span class="text-accent">*</span>
                                </label>
                                <input type="number" id="price" name="price" step="0.01"
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent"
                                    placeholder="e.g., 12.99" required>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-300 mb-2">Tags</label>
                                <div class="flex flex-wrap gap-3">
                                    <?php foreach ($tags as $tag): ?>
                                        <div class="tag-item mb-2">
                                            <input type="checkbox" name="tags[]" value="<?php echo $tag['tag_id']; ?>"
                                                id="tag-<?php echo $tag['tag_id']; ?>" class="hidden peer"
                                                data-tag-text="<?php echo htmlspecialchars($tag['tag']); ?>">
                                            <label for="tag-<?php echo $tag['tag_id']; ?>" class="px-5 py-2 text-sm font-medium rounded-full cursor-pointer transition-all whitespace-nowrap
                   bg-gray-700 text-gray-300 border border-gray-600
                   hover:bg-gray-600 hover:border-accent
                   peer-checked:bg-accent peer-checked:text-black peer-checked:border-accent
                   peer-checked:hover:bg-yellow-500">
                                                <?php echo htmlspecialchars($tag['tag']); ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="col-span-2">
                                <label for="image_url" class="block text-sm font-medium text-gray-300 mb-2">
                                    Item Image
                                </label>
                                <div class="flex items-center">
                                    <input type="file" id="image_url" name="image_url" class="hidden" accept="image/*">
                                    <button type="button"
                                        class="px-4 py-2 border border-gray-600 text-gray-300 rounded-xl hover:bg-gray-700/30 transition-colors"
                                        onclick="document.getElementById('image_url').click()">
                                        <i class="fas fa-upload mr-2"></i> Upload Image
                                    </button>
                                    <span id="file-name" class="ml-3 text-sm text-gray-400">No file chosen</span>
                                </div>
                                <div id="image-preview" class="mt-3 hidden">
                                    <img id="preview-img" src="#" alt="Image preview"
                                        class="h-32 w-32 object-cover rounded-lg border border-gray-600">
                                </div>
                            </div>
                            <div class="col-span-2">
                                <label for="is_available" class="block text-sm font-medium text-gray-300 mb-2">
                                    Availability
                                </label>
                                <div class="relative inline-block w-10 mr-2 align-middle select-none">
                                    <input type="checkbox" id="is_available" name="is_available" value="1" checked
                                        class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                                    <label for="is_available"
                                        class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-600 cursor-pointer"></label>
                                </div>
                                <span class="text-sm text-gray-300">Available</span>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3 mt-6">
                            <a href="menuItems.php"
                                class="px-6 py-2 border border-gray-600 text-gray-300 rounded-xl hover:bg-gray-700/30 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" name="btnAddMenuItem"
                                class="px-6 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors flex items-center">
                                <i class="fas fa-plus mr-2"></i> Add Menu Item
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
    <script>
        document.getElementById('image_url').addEventListener('change', function () {
            const fileName = this.files[0] ? this.files[0].name : 'No file chosen';
            document.getElementById('file-name').textContent = fileName;

            if (this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                document.getElementById('image-preview').classList.add('hidden');
            }
        });
    </script>
</body>

</html>