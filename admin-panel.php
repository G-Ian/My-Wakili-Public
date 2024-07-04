<?php
require_once 'includes/config_session.inc.php';
require_once 'classes/dbh.classes.php';

// Ensure session is active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("location: login.php");
    exit();
}

class Admin extends Dbh {
    public function getUsers() {
        $stmt = $this->connect()->prepare('SELECT * FROM users');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAppointments() {
        $stmt = $this->connect()->prepare('
            SELECT b.booking_id, b.user_id, b.client_name, b.practitioner_id, p.full_name AS practitioner_name, b.date, b.time
            FROM bookings b
            INNER JOIN practitioners p ON b.practitioner_id = p.practitioner_id
        ');
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
}

$admin = new Admin();
$users = $admin->getUsers();
$appointments = $admin->getAppointments();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/header.footer.css">
    <link rel="stylesheet" type="text/css" href="css/appointments.css">

</head>
<body>

<?php include 'includes/pract_header.inc.php'; ?>


    <h1>Admin Panel</h1>
    <h2>Manage Users</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Type</th>
            <th>Admin</th>
            <th>Actions</th> <!-- New column for actions -->
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['user_id']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['user_email']; ?></td>
                <td><?php echo $user['user_type']; ?></td>
                <td><?php echo $user['is_admin'] ? 'Yes' : 'No'; ?></td>
                <td>
                    <form action="classes/remove-user.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                        <button type="submit" style="background-color: #dc3545;">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    
    <h2>Manage Appointments</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Client ID</th>
            <th>Client Name</th>
            <th>Practitioner ID</th>
            <th>Practitioner Name</th> <!-- Updated column header -->
            <th>Date</th>
            <th>Time</th>
            <th>Actions</th> <!-- New column for actions -->
        </tr>
        <?php foreach ($appointments as $appointment): ?>
            <tr>
                <td><?php echo $appointment['booking_id']; ?></td>
                <td><?php echo $appointment['user_id']; ?></td>
                <td><?php echo $appointment['client_name']; ?></td>
                <td><?php echo $appointment['practitioner_id']; ?></td>
                <td><?php echo $appointment['practitioner_name']; ?></td> <!-- Display practitioner's full name -->
                <td><?php echo $appointment['date']; ?></td>
                <td><?php echo $appointment['time']; ?></td>
                <td>
                    <form action="classes/remove-appointment.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                        <input type="hidden" name="booking_id" value="<?php echo $appointment['booking_id']; ?>">
                        <button type="submit" style="background-color: #dc3545;">Remove</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>


</body>
</html>
