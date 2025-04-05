<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}
require '../dbCon.php';
$obj = new Foodies();
$admin = $_SESSION['admin'];
$error = $success = '';

// Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    try {
        $id = $admin['user_id'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $profile_pic = $_FILES['profile_pic'];

        if (empty($fname)) {
            $error = "First name is required.";
        }

        if (empty($lname)) {
            $error = "Last name is required.";
        }

        if (empty($email)) {
            $error = "Email is required.";
        }

        if (empty($phone)) {
            $error = "Phone number is required.";
        }

        if (!preg_match('/^\d{10}$/', $phone)) {
            $error = "Phone number is required and must be 10 digits.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        }

        if (!empty($fname) && !empty($lname) && !empty($email) && !empty($phone) && preg_match('/^\d{10}$/', $phone) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($obj->updateProfile($id, $profile_pic, $fname, $lname, $email, $phone)) {
                // Refresh admin data
                $_SESSION['admin'] = $obj->getUserById($admin['user_id']);
                $admin = $_SESSION['admin'];
                $success = "Profile updated successfully!";
            }
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}


// Handle Password Change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    try {
        $id = $admin['user_id'];
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

       
        if (empty($current_password)) {
            $error = "Current password is required.";
        } elseif (empty($new_password)) {
            $error = "New password is required.";
        } elseif (strlen($new_password) < 8) {
            $error = "New password must be at least 8 characters long.";
        } elseif ($new_password !== $confirm_password) {
            $error = "New password and confirm password do not match.";
        } else {
            // Call the changePassword method
            if ($obj->changePassword($id, $current_password, $new_password, $confirm_password)) {
                $success = "Password changed successfully!";
            } else {
                $error = "Failed to change password. Please try again.";
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body class="bg-primary text-gray-100">
    <div class="flex h-screen overflow-hidden">
        <?php include 'sidebar.php'; ?>

        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <?php include 'header.php'; ?>

            <main class="flex-1 relative overflow-y-auto focus:outline-none p-6">
                <div class="max-w-4xl mx-auto">
                    <!-- Notifications -->
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

                    <!-- Profile Header -->
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-2xl font-bold text-accent">Profile Settings</h2>
                            <p class="text-gray-400">Manage your account information and security settings</p>
                        </div>
                    </div>

                    <!-- Profile Update Form -->
                    <form method="POST" enctype="multipart/form-data"
                        class="bg-gray-800 rounded-2xl border border-gray-700 p-6 space-y-8">
                        <!-- Avatar Section -->
                        <div class="text-center">
                            <div class="relative inline-block">
                                <img class="h-32 w-32 rounded-full border-4 border-accent/30 object-cover mx-auto"
                                    src="<?php echo htmlspecialchars($admin['profile_pic'] ?? 'https://via.placeholder.com/150'); ?>"
                                    alt="Profile photo">
                                <button type="button"
                                    class="absolute bottom-0 right-0 bg-accent p-2 rounded-full hover:bg-accent/90 transition-colors">
                                    <i class="fas fa-camera text-black text-lg"></i>
                                </button>
                                <input type="file" name="profile_pic" id="profile_pic" class="hidden" accept="image/*">
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold border-b border-gray-700 pb-2 text-accent">
                                <i class="fas fa-user-circle mr-2"></i>Personal Information
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">First Name</label>
                                    <input type="text" name="fname"
                                        value="<?php echo htmlspecialchars($admin['first_name']); ?>"
                                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-accent focus:ring-1 focus:ring-accent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Last Name</label>
                                    <input type="text" name="lname"
                                        value="<?php echo htmlspecialchars($admin['last_name']); ?>"
                                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-accent focus:ring-1 focus:ring-accent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                                    <input type="email" name="email"
                                        value="<?php echo htmlspecialchars($admin['email']); ?>"
                                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-accent focus:ring-1 focus:ring-accent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Phone Number</label>
                                    <input type="tel" name="phone"
                                        value="<?php echo htmlspecialchars($admin['phone']); ?>"
                                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-accent focus:ring-1 focus:ring-accent">
                                </div>
                            </div>
                        </div>

                        <!-- Profile Form Actions -->
                        <div class="flex justify-end space-x-4 mt-8">
                            <button type="submit" name="update_profile"
                                class="px-6 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 transition-colors font-semibold">
                                Update Profile
                            </button>
                        </div>
                    </form>

                    <!-- Password Update Form -->
                    <form method="POST" class="bg-gray-800 rounded-2xl border border-gray-700 p-6 space-y-8 mt-8">
                        <!-- Security Settings -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold border-b border-gray-700 pb-2 text-accent">
                                <i class="fas fa-shield-alt mr-2"></i>Change Password
                            </h3>

                            <div class="bg-red-900/20 border border-red-800 rounded-xl p-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Current Password</label>
                                    <input type="password" name="current_password" required
                                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-accent focus:ring-1 focus:ring-accent">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-2">New Password</label>
                                        <input type="password" name="new_password" required minlength="8"
                                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-accent focus:ring-1 focus:ring-accent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-2">Confirm
                                            Password</label>
                                        <input type="password" name="confirm_password" required minlength="8"
                                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-accent focus:ring-1 focus:ring-accent">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Password Form Actions -->
                        <div class="flex justify-end space-x-4 mt-8">
                            <button type="submit" name="change_password"
                                class="px-6 py-2 bg-accent text-black rounded-xl hover:bg-accent/90 transition-colors font-semibold">
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Profile Picture Upload
        const profileInput = document.getElementById('profile_pic');
        document.querySelector('.fa-camera').addEventListener('click', () => profileInput.click());

        profileInput.addEventListener('change', function (e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.querySelector('img[alt="Profile photo"]').src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
</body>

</html>