<?php
session_start();
require_once "classes/dbh.classes.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if booking_id is provided via GET or POST
if (!isset($_GET['booking_id']) && !isset($_POST['booking_id'])) {
    header("Location: client-appointments.php");
    exit();
}

// Sanitize the input to prevent SQL injection
$booking_id = isset($_GET['booking_id']) ? filter_input(INPUT_GET, 'booking_id', FILTER_SANITIZE_NUMBER_INT) : filter_input(INPUT_POST, 'booking_id', FILTER_SANITIZE_NUMBER_INT);

if (!$booking_id) {
    // Handle invalid booking ID
    header("Location: client-appointments.php");
    exit();
}

try {
    // Create DB connection
    $dbh = new Dbh();
    $pdo = $dbh->getPdo();

    // Fetch current booking details
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE booking_id = :booking_id");
    $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
    $stmt->execute();
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$booking) {
        // Handle booking not found
        header("Location: client-appointments.php");
        exit();
    }

    // Handle form submission for updating booking
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Extract and sanitize POST data
        $service_type = filter_input(INPUT_POST, 'service_type', FILTER_SANITIZE_STRING);
        $comments = filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_STRING);

        // Prepare update statement
        $updateStmt = $pdo->prepare("UPDATE bookings SET service_type = :service_type, comments = :comments WHERE booking_id = :booking_id");
        $updateStmt->bindParam(':service_type', $service_type, PDO::PARAM_STR);
        $updateStmt->bindParam(':comments', $comments, PDO::PARAM_STR);
        $updateStmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
        $updateStmt->execute();

        // Redirect after update
        header("Location: client-appointments.php");
        exit();
    }
} catch (PDOException $e) {
    // Handle database errors
    die('Database Error: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Booking</title>
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/header.footer.css">
    <link rel="stylesheet" type="text/css" href="css/update-appointments.css">
</head>
<body>

    <!-- Header -->
    <?php include 'includes/client_header.inc.php'; ?>

    <div class="container">

        <h1>Update Booking</h1>

        <form action="client-booking-update.php" method="POST">

            <!-- Hidden input for booking_id -->
            <input type="hidden" name="booking_id" value="<?= htmlspecialchars($booking['booking_id']) ?>">

            <label for="service_type">Service Type:</label>
            <input type="text" id="service_type" name="service_type" value="<?= htmlspecialchars($booking['service_type']) ?>" required>

            <label for="comments">Comments:</label>
            <textarea id="comments" name="comments"><?= htmlspecialchars($booking['comments']) ?></textarea>

            <button type="submit">Update Booking</button>
        </form>

    </div> <!-- End of .container -->


    <?php include 'includes/footer.inc.php'; ?>

</body>
</html>
