<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}

require '../dbCon.php';
$obj = new Foodies();

$id = $_GET['id'];
$cuisine = $obj->getCuisineById($id);
$error = "";
$success = "";

if (isset($_POST['btnSubmit'])) {
    try {
        $cuisine_name = $_POST['cuisine_name'];
        $description = $_POST['description'];

        if (empty($cuisine_name)) {
            throw new Exception("Cuisine name is required.");
        }

        if (strlen($cuisine_name) < 3 || strlen($cuisine_name) > 50) {
            throw new Exception("Cuisine name must be between 3 and 50 characters.");
        }

        if (!empty($description) && strlen($description) > 255) {
            throw new Exception("Description cannot exceed 255 characters.");
        }

        if ($obj->updateCuisine($id, $cuisine_name, $description)) {
            $_SESSION['success'] = "Cuisine updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update Cuisine. Please try again.";
        }
        header('location:cuisine.php');
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Cuisine | Food Ordering System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#000000',  // Black background
                        accent: '#eab308',   // Golden yellow accent
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
                <div class="max-w-2xl mx-auto">

                    <h1 class="text-2xl font-bold text-accent mb-6 flex items-center">
                        <i class="fas fa-edit mr-2"></i> Update Cuisine
                    </h1>
                    <?php if ($error): ?>
                        <div class="mb-6 p-4 bg-red-900/30 border border-red-800 rounded-xl text-red-400">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="mb-6 p-4 bg-green-900/30 border border-green-800 rounded-xl text-green-400">
                            <?php echo $success; ?>
                        </div>
                    <?php endif; ?>
                    <form method="post" class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-md">

                        <div class="mb-4">
                            <label for="cuisine_name" class="block text-sm font-medium text-gray-300 mb-2">
                                Cuisine Name <span class="text-accent">*</span>
                            </label>
                            <input type="text" id="cuisine_name" name="cuisine_name"
                                value="<?php echo $cuisine['cuisine_name']; ?>"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent"
                                placeholder="e.g., Italian, Chinese, Mexican">
                        </div>
                        <!-- Description Field -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                                Description
                            </label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-gray-300 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent"
                                placeholder="Brief description of the cuisine"><?php echo $cuisine['description']; ?>
                            </textarea>
                        </div>
                        <!-- Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="cuisine.php"
                                class="px-6 py-2 border border-gray-600 text-gray-300 rounded-xl hover:bg-gray-700/30 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" name="btnSubmit"
                                class="px-6 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 font-medium transition-colors flex items-center">
                                <i class="fas fa-edit mr-2"></i> Update Cuisine
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>

</html>