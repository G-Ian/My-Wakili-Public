<?php
// Database connection
$dsn = 'mysql:host=localhost:3307;dbname=mywakili2';
$username = 'root';
$password = '';

try {
    $db = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
    $practitioner_id = filter_input(INPUT_POST, 'practitioner_id', FILTER_VALIDATE_INT);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $time = filter_input(INPUT_POST, 'time', FILTER_SANITIZE_STRING);
    $service_type = filter_input(INPUT_POST, 'service_type', FILTER_SANITIZE_STRING);

    if ($user_id && $practitioner_id && $date && $time && $service_type) {
        // Insert booking into the database
        $sql = "INSERT INTO bookings (user_id, practitioner_id, date, time, service_type) VALUES (:user_id, :practitioner_id, :date, :time, :service_type)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':practitioner_id', $practitioner_id);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':service_type', $service_type);
        if ($stmt->execute()) {
            echo "Booking successfully created!";
        } else {
            echo "Failed to create booking.";
        }
    } else {
        echo "Invalid input.";
    }
} else {
    $date = $_GET['date'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
</head>
<body>
    <h2>Book an Appointment for <?php echo htmlspecialchars($date); ?></h2>
    <form action="book.php" method="POST">
        <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>">
        <label for="user_id">User ID:</label>
        <input type="number" id="user_id" name="user_id" required>
        <br>
        <label for="practitioner_id">Practitioner ID:</label>
        <input type="number" id="practitioner_id" name="practitioner_id" required>
        <br>
        <label for="time">Time:</label>
        <input type="time" id="time" name="time" required>
        <br>
        <label for="service_type">Service Type:</label>
        <input type="text" id="service_type" name="service_type" required>
        <br>
        <button type="submit">Book Appointment</button>
    </form>
</body>
</html>
<?php
}
?>
