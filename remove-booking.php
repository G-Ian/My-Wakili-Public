<?php
session_start();
require_once "classes/dbh.classes.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if booking_id is provided via POST
if (!isset($_POST['booking_id'])) {
    header("Location: clientappointments.php");
    exit();
}

// Sanitize the input to prevent SQL injection
$booking_id = filter_input(INPUT_POST, 'booking_id', FILTER_SANITIZE_NUMBER_INT);

// Create DB connection
$dbh = new Dbh();
$pdo = $dbh->getPdo();

try {
    // Prepare SQL statement
    $stmt = $pdo->prepare("DELETE FROM bookings WHERE booking_id = :booking_id");
    // Bind parameters
    $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
    // Execute SQL statement
    $stmt->execute();

    // Redirect to appointments page after successful deletion
    header("Location: clientappointments.php");
    exit();
} catch (PDOException $e) {
    // Handle database errors gracefully (you might want to log these errors)
    die('Error: ' . $e->getMessage());
}
?>
