<?php
session_start();
require_once "classes/dbh.classes.php";
require_once "classes/booking-view.classes.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$limit = 10; // Number of entries to show in a page.
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$bookingView = new BookingView();
$appointments = $bookingView->getClientBookings($user_id, $limit, $offset);
$total_appointments = $bookingView->countClientBookings($user_id);
$total_pages = ceil($total_appointments / $limit);

// Debug memory usage
echo 'Memory usage: ' . memory_get_usage() . ' bytes';
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
    <?php include 'includes/client_header.inc.php'; ?>

    <div class="container">

        <h1>My Appointments</h1>

        <!-- Appointments Table -->
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Legal Practitioner</th>
                    <th>Service Type</th>
                    <th>Comments</th>
                    <th>Date of Appointment</th>
                    <th>Time of Appointment</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($appointments): ?>
                    <?php foreach ($appointments as $appointment): ?>
                        <tr>
                            <td><?= htmlspecialchars($appointment['booking_id']) ?></td>
                            <td><a href="profile.php?user_id=<?= htmlspecialchars($appointment['practitioner_user_id']) ?>"><?= htmlspecialchars($appointment['practitioner_name']) ?></a></td>
                            <td><?= htmlspecialchars($appointment['service_type']) ?></td>
                            <td><?= htmlspecialchars($appointment['comments']) ?></td>
                            <td><?= htmlspecialchars($appointment['date']) ?></td>
                            <td><?= htmlspecialchars($appointment['time']) ?></td>
                            <td><?= htmlspecialchars($appointment['updated_at']) ?></td>
                            <td>
                                <form action="update-booking.php" method="GET" style="display: inline;">
                                    <input type="hidden" name="booking_id" value="<?= htmlspecialchars($appointment['booking_id']) ?>">
                                    <button type="submit">Update</button>
                                </form>
                                <form action="remove-booking.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this booking?');" style="display: inline;">
                                    <input type="hidden" name="booking_id" value="<?= htmlspecialchars($appointment['booking_id']) ?>">
                                    <button type="submit" style="background-color: #dc3545;">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No appointments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="client-appointments.php?page=<?= $i ?>" class="<?= ($page == $i) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>

    </div> <!-- End of .container -->

</body>


</html>
