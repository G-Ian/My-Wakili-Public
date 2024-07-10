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

// Handle file upload if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["document"])) {
    $documentName = $_FILES["document"]["name"];
    $documentType = $_FILES["document"]["type"];
    $documentSize = $_FILES["document"]["size"];
    $documentTmpName = $_FILES["document"]["tmp_name"];

    // Check if file is a PDF
    $allowedTypes = ['application/pdf'];
    if (!in_array($documentType, $allowedTypes)) {
        echo "Only PDF files are allowed.";
        exit();
    }

    // Read file data
    $documentData = file_get_contents($documentTmpName);

    // Save file to database
    try {
        $dbh = new Dbh();
        $pdo = $dbh->getPdo();

        $stmt = $pdo->prepare('INSERT INTO documents (document_name, document_type, document_size, document_data) VALUES (?, ?, ?, ?)');
        $stmt->bindParam(1, $documentName);
        $stmt->bindParam(2, $documentType);
        $stmt->bindParam(3, $documentSize);
        $stmt->bindParam(4, $documentData, PDO::PARAM_LOB);
        $stmt->execute();

        echo "Document uploaded successfully.";
    } catch (PDOException $e) {
        echo "Error uploading document: " . $e->getMessage();
    }
}

// Fetch documents from database
function getDocuments() {
    $dbh = new Dbh();
    $pdo = $dbh->getPdo();

    $stmt = $pdo->prepare('SELECT * FROM documents');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$admin = new Admin();
$users = $admin->getUsers();
$appointments = $admin->getAppointments();
$documents = getDocuments();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        h1 {
            font-size: 2em; /* Adjust as needed for larger size */
            font-weight: bold;
        }

        h2 {
            font-weight: bold;
        }
    </style>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/header.footer.css">
    <link rel="stylesheet" type="text/css" href="css/appointments.css">

</head>
<body>

<?php include 'includes/client_header.inc.php'; ?>

<br>
<h1>Admin Panel</h1>

<!-- Upload Documents Section -->

<h2>Upload Documents</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <label for="document">Select PDF Document:</label>
    <input type="file" name="document" id="document" accept=".pdf" required>
    <button type="submit">Upload Document</button>
</form>

<!-- List of Uploaded Documents -->

<h2>Uploaded Documents</h2>
<table border="1">
    <tr>
        <th>Document ID</th>
        <th>Name</th>
        <th>Type</th>
        <th>Size (bytes)</th>
        <th>Upload Date</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($documents as $document): ?>
        <tr>
            <td><?php echo $document['document_id']; ?></td>
            <td><?php echo $document['document_name']; ?></td>
            <td><?php echo $document['document_type']; ?></td>
            <td><?php echo $document['document_size']; ?></td>
            <td><?php echo $document['upload_date']; ?></td>
            <td>
                <form action="classes/download.classes.php" method="GET">
                    <input type="hidden" name="document_id" value="<?php echo $document['document_id']; ?>">
                    <button type="submit">Download</button>
                </form>
                <form action="classes/delete-document.classes.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this document?');">
                    <input type="hidden" name="document_id" value="<?php echo $document['document_id']; ?>">
                    <button type="submit" style="background-color: #dc3545;">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>


<!-- Manage Users Table -->

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

<!-- Manage Appointments Table -->

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
