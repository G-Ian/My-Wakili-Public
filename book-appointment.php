<?php
session_start();

// Include database connection class
include('classes/dbh.classes.php');

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Function to get database connection
function getDbConnection() {
    try {
        $db = new Dbh();
        $username = "root";
        $password = "";
        $conn = new PDO('mysql:host=localhost:3307;dbname=mywakili2', $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        die();
    }
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to book an appointment.");
}

// Retrieve user_id from session
$user_id = $_SESSION['user_id'];

// Ensure practitioner ID or date is provided
if (!isset($_GET['practitioner_id']) || !isset($_GET['date'])) {
    die("Missing required parameters.");
}

// Retrieve practitioner_id from GET parameter
$practitioner_id = intval($_GET['practitioner_id']);
$date = htmlspecialchars($_GET['date']);

// Initialize variables
$success_message = '';
$error_message = '';

// Time increments in multiples of 10 minutes
$timeIncrements = array(
    '00' => '0', '10' => '1', '20' => '2', '30' => '3', '40' => '4', '50' => '5'
);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize POST data
    $date = $_POST['date'];
    $time = $_POST['time'];
    $client_name = htmlspecialchars($_POST['client_name']);
    $client_email = htmlspecialchars($_POST['client_email']);
    $client_phone = htmlspecialchars($_POST['client_phone']);
    $service_type = htmlspecialchars($_POST['service_type']);
    $comments = htmlspecialchars($_POST['comments']);

    // Check if the selected time slot is available
    if (isTimeSlotAvailable($practitioner_id, $date, $time)) {
        // SQL query to insert or update the booking into the database
        $sql = "INSERT INTO bookings (user_id, practitioner_id, date, time, client_name, client_email, client_phone, service_type, comments, created_at, updated_at, appointment_time) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
                ON DUPLICATE KEY UPDATE 
                user_id = VALUES(user_id),
                practitioner_id = VALUES(practitioner_id),
                date = VALUES(date),
                time = VALUES(time),
                client_name = VALUES(client_name),
                client_email = VALUES(client_email),
                client_phone = VALUES(client_phone),
                service_type = VALUES(service_type),
                comments = VALUES(comments),
                updated_at = CURRENT_TIMESTAMP";
        
        // Prepare and execute the SQL statement
        try {
            $conn = getDbConnection(); // Call the function to get DB connection
            $stmt = $conn->prepare($sql);
            $stmt->execute([$user_id, $practitioner_id, $date, $time, $client_name, $client_email, $client_phone, $service_type, $comments]);
            $booking_id = $conn->lastInsertId(); // Get the last inserted booking ID
            $success_message = "Booking successfully made!"; // Set success message after booking

            // Get practitioner's email from the database
            $sql = "SELECT `user_email` FROM profiles WHERE practitioner_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$practitioner_id]);
            $practitioner_email = $stmt->fetchColumn();

            // Get practitioner's name from the database
            $sql = "SELECT `full_name` FROM profiles WHERE practitioner_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$practitioner_id]);
            $practitioner_name = $stmt->fetchColumn();

            // Send email to practitioner
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'mywakili.co.ke@gmail.com';
                $mail->Password = 'lpvaatlcfirrghjn';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Practitioner email
                $mail->setFrom('mywakili.co.ke@gmail.com', 'My Wakili');
                $mail->addAddress($practitioner_email);
                $mail->isHTML(true);
                $mail->Subject = 'New Appointment Booking';
                $mail->Body = "A new appointment has been booked.<br>
                               Client's Name: $client_name<br>
                               Client's Email: $client_email<br>
                               Client's Phone: $client_phone<br>
                               Service Type: $service_type<br>
                               Comments: $comments<br>
                               Date: $date<br>
                               Time: $time";

                // Disabled SSL verification for testing
                $mail->SMTPOptions = array(
                    'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                    )
                );
                            
                $mail->send();
            } catch (Exception $e) {
                echo "Message could not be sent to practitioner. Mailer Error: {$mail->ErrorInfo}";
            }

            // Send email to client
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'mywakili.co.ke@gmail.com';
                $mail->Password = 'lpvaatlcfirrghjn';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Client email
                $mail->setFrom('mywakili.co.ke@gmail.com', 'My Wakili');
                $mail->addAddress($client_email);
                $mail->isHTML(true);
                $mail->Subject = 'Appointment Confirmation';
                $mail->Body = "You have successfully booked an appointment.<br>
                               Booking ID: $booking_id<br>
                               Practitioner: $practitioner_name<br>
                               Service Type: $service_type<br>
                               Comments: $comments<br>
                               Date: $date<br>
                               Time: $time";
                               
                               
                // Disabled SSL verification for testing
                $mail->SMTPOptions = array(
                    'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                    )
                );

                $mail->send();
            } catch (Exception $e) {
                echo "Message could not be sent to client. Mailer Error: {$mail->ErrorInfo}";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        // Calculate next available time slot in multiples of 10 minutes
        $nextAvailableTime = calculateNextAvailableTime($practitioner_id, $date, $time);
        if ($nextAvailableTime) {
            $error_message = "The selected time slot is already booked. Next available time is: " . $nextAvailableTime;
        } else {
            $error_message = "The selected time slot is already booked. No available slots found.";
        }
    }
}

// Function to check if the selected time slot is available and calculate next available time
function isTimeSlotAvailable($practitioner_id, $date, $time) {
    $conn = getDbConnection();
    $sql = "SELECT COUNT(*) FROM bookings WHERE practitioner_id = ? AND date = ? AND time = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$practitioner_id, $date, $time]);
    $count = $stmt->fetchColumn();
    return $count == 0; // Return true if no bookings exist for the given time slot
}

// Function to calculate the next available time slot in multiples of 10 minutes
function calculateNextAvailableTime($practitioner_id, $date, $time) {
    global $timeIncrements;
    $conn = getDbConnection();
    
    // Get the minute part of the selected time
    $minute = date('i', strtotime($time));
    
    // Loop through increments to find the next available time slot
    foreach ($timeIncrements as $increment => $index) {
        // Calculate the next time slot in multiples of 10 minutes
        $nextTime = date('H:i', strtotime("+$increment minutes", strtotime($time)));
        
        // Check if the calculated time slot is available
        $sql = "SELECT COUNT(*) FROM bookings WHERE practitioner_id = ? AND date = ? AND time = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$practitioner_id, $date, $nextTime]);
        $count = $stmt->fetchColumn();
        
        if ($count == 0) {
            return $nextTime;
        }
    }
    
    return false; // Return false if no available slots are found
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/header.footer.css">
    <title>Book Appointment</title>
</head>
<body>
    <?php include 'includes/client_header.inc.php'; ?>

    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php if (!empty($success_message)) : ?>
                    <!-- Display success message and provide navigation links -->
                    <div class="alert alert-success" role="alert">
                        <?php echo $success_message; ?>
                    </div>
                    <a href="client-profiles.php" class="btn btn-primary">Back to profiles</a>

                <?php elseif (!empty($error_message)) : ?>
                    <!-- Display error message with suggested next available time -->
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <!-- Display booking form if no success message -->
                <h2>Book an Appointment for <?php echo htmlspecialchars($date); ?></h2>
                <form method="POST" action="book-appointment.php?practitioner_id=<?php echo htmlspecialchars($practitioner_id); ?>&date=<?php echo htmlspecialchars($date); ?>">
                    <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>">
                    <div class="form-group">
                        <label for="time">Time</label>
                        <input type="time" class="form-control" id="time" name="time" required>
                    </div>
                    <div class="form-group">
                        <label for="client_name">Client Name</label>
                        <input type="text" class="form-control" id="client_name" name="client_name" placeholder="Enter your full name" required>
                    </div>
                    <div class="form-group">
                        <label for="client_email">Client Email</label>
                        <input type="email" class="form-control" id="client_email" name="client_email" placeholder="Enter your email address" required>
                    </div>
                    <div class="form-group">
                        <label for="client_phone">Client Phone</label>
                        <input type="text" class="form-control" id="client_phone" name="client_phone" placeholder="Enter your phone number" required>
                    </div>
                    <div class="form-group">
                        <label for="service_type">Service Type</label>
                        <input type="text" class="form-control" id="service_type" name="service_type" placeholder="What type of service will you require? e.g Litigation, Court Process Server, Mediation, Commissioning of Oaths .etc" required>
                    </div>
                    <div class="form-group">
                        <label for="comments">Comments</label>
                        <textarea class="form-control" id="comments" name="comments" rows="3" placeholder="Give some additional information about your case or the service you need (Optional)"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Book Appointment</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
