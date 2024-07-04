<?php
require_once 'includes/config_session.inc.php';
require_once 'classes/dbh.classes.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("location: login.php");
    exit();
}

$dbh = new Dbh();
$pdo = $dbh->connect();

// Fetch users
$usersStmt = $pdo->prepare('SELECT * FROM users');
$usersStmt->execute();
$users = $usersStmt->fetchAll();

// Fetch appointments
$appointmentsStmt = $pdo->prepare('SELECT * FROM appointments');
$appointmentsStmt->execute();
$appointments = $appointmentsStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>
    <h2>Manage Users</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Type</th>
            <th>Admin</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user['user_id']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['user_email']; ?></td>
            <td><?php echo $user['user_type']; ?></td>
            <td><?php echo $user['is_admin'] ? 'Yes' : 'No'; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <h2>Manage Appointments</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Client</th>
            <th>Practitioner</th>
            <th>Date</th>
            <th>Time</th>
        </tr>
        <?php foreach ($appointments as $appointment): ?>
        <tr>
            <td><?php echo $appointment['appointment_id']; ?></td>
            <td><?php echo $appointment['client_id']; ?></td>
            <td><?php echo $appointment['practitioner_id']; ?></td>
            <td><?php echo $appointment['date']; ?></td>
            <td><?php echo $appointment['time']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
