<?php
session_start();
require_once "classes/dbh.classes.php";
require_once "classes/booking-view.classes.php";

// Check if user is logged in and is a legal practitioner
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'legal_practitioner') {
    header("Location: login.php");
    exit();
}

// Initialize variables
$user_id = $_SESSION['user_id'];
$limit = 10; // Number of entries to show in a page.
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$bookingView = new BookingView();
$appointments = $bookingView->getPractitionerBookings($user_id, $limit, $offset); // Adjust method name to fetch practitioner's bookings
$total_appointments = $bookingView->countPractitionerBookings($user_id); // Adjust method name for counting practitioner's bookings
$total_pages = ceil($total_appointments / $limit);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Appointments</title>
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/header.footer.css">
    <link rel="stylesheet" type="text/css" href="css/appointments.css">
</head>
<body>

    <!-- Header -->
    <?php include 'includes/pract_header.inc.php'; ?>

    <div class="container">

        <h1>My Appointments</h1>

        <!-- Appointments Table -->
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Client's Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Service Type</th>
                    <th>Comments</th>
                    <th>Date of Appointment</th>
                    <th>Time of Appointment</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?= htmlspecialchars($appointment['booking_id']) ?></td>
                        <td><?= htmlspecialchars($appointment['client_name']) ?></td>
                        <td><?= htmlspecialchars($appointment['client_email']) ?></td>
                        <td><?= htmlspecialchars($appointment['client_phone']) ?></td>
                        <td><?= htmlspecialchars($appointment['service_type']) ?></td>
                        <td><?= htmlspecialchars($appointment['comments']) ?></td>
                        <td><?= htmlspecialchars($appointment['date']) ?></td>
                        <td><?= htmlspecialchars($appointment['time']) ?></td>
                        <td><?= htmlspecialchars($appointment['updated_at']) ?></td>
                        <td>

                            <form action="classes/remove-booking.classes.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this booking?');" style="display: inline;">
                                <input type="hidden" name="booking_id" value="<?= htmlspecialchars($appointment['booking_id']) ?>">
                                <button type="submit" style="background-color: #dc3545;">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="practitionerappointments.php?page=<?= $i ?>" class="<?= ($page == $i) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>

    </div> <!-- End of .container -->

</body>
</html>
