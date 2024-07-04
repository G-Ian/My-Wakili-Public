<?php
require_once 'admin.classes.php'; // Adjust the path as per your file structure

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];

    $admin = new Admin();
    $removed = $admin->removeBooking($booking_id);

    if ($removed) {
        // Handle success (redirect, display success message, etc.)
        echo "Appointment removed successfully.";
    } else {
        // Handle failure (redirect, display error message, etc.)
        echo "Failed to remove appointment.";
    }
} else {
    // Handle invalid requests or direct access attempts
    echo "Invalid request.";
}
?>
