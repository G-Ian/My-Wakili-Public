<?php
require_once 'admin.classes.php'; // Adjust the path as per your file structure

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    $admin = new Admin();
    $removed = $admin->removeUser($user_id);

    if ($removed) {
        // Handle success (redirect, display success message, etc.)
        echo "User removed successfully.";
    } else {
        // Handle failure (redirect, display error message, etc.)
        echo "Failed to remove user.";
    }
} else {
    // Handle invalid requests or direct access attempts
    echo "Invalid request.";
}
?>
