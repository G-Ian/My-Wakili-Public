<?php
session_start();
// Include the database connection class
require_once 'classes/dbh.classes.php';

// Check if user_id is set in the session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    die("User ID not found."); // Handle this error more gracefully in your application
}

// Function to build the calendar for a given month and year
function build_calendar($month, $year, $user_id, $practitioner_id) {
    // Array of days of the week
    $daysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
    
    // Timestamp for the first day of the given month and year
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    
    // Number of days in the given month
    $numberDays = date('t', $firstDayOfMonth);
    
    // Array of date components for the first day of the month
    $dateComponents = getdate($firstDayOfMonth);
    
    // Name of the month
    $monthName = $dateComponents['month'];
    
    // Index of the first day of the month (0-6)
    $dayOfWeek = $dateComponents['wday'];
    
    // Current date in 'Y-m-d' format
    $dateToday = date('Y-m-d');

    // Calculate previous and next month/year values for navigation
    $prev_month = date('m', mktime(0, 0, 0, $month-1, 1, $year));
    $prev_year = date('Y', mktime(0, 0, 0, $month-1, 1, $year));
    $next_month = date('m', mktime(0, 0, 0, $month+1, 1, $year));
    $next_year = date('Y', mktime(0, 0, 0, $month+1, 1, $year));

    // Start building the HTML for the calendar
    // $calendar = "<h2>$monthName $year</h2><br>";
    // Display the calendar for the given month and year
    $monthName = $dateComponents['month']; // Fetches the month name
    $calendar = "<h2 class='small-text'>$monthName $year</h2><br>";

    
    // Navigation buttons for previous month, current month, and next month
    $calendar .= "<a class='btn btn-primary btn-xs' href='?month=".$prev_month."&year=".$prev_year."&practitioner_id=$practitioner_id'><button type='default'>Prev Month</button></a> ";
    $calendar .= "<a class='btn btn-primary btn-xs' href='?month=".date('m')."&year=".date('Y')."&practitioner_id=$practitioner_id'><button type='default'>Current Month</button></a> ";
    $calendar .= "<a class='btn btn-primary btn-xs' href='?month=".$next_month."&year=".$next_year."&practitioner_id=$practitioner_id'><button type='default'>Next Month</button></a>";
    
    // Add some space and start the table for the calendar
    $calendar .= "<br><br><table class='table table-bordered'><tr>";

    // Create the headers for the days of the week
    foreach($daysOfWeek as $day){
        $calendar .= "<th class='header'>$day</th>";
    }

    // End the header row
    $calendar .= "</tr><tr>";

    // Create empty cells if the first day of the month is not Sunday
    if($dayOfWeek > 0){
        for($k = 0; $k < $dayOfWeek; $k++){
            $calendar .= "<td class='empty'></td>";
        }
    }

    // Initialize the current day counter
    $currentDay = 1;

    // Pad the month with leading zero if necessary
    $month = str_pad($month, 2, "0", STR_PAD_LEFT);

    // Loop through all the days in the month
    while($currentDay <= $numberDays){
        // Reset to a new row after Saturday
        if($dayOfWeek == 7){
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }

        // Pad the current day with leading zero if necessary
        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        
        // Format the date in 'Y-m-d' format
        $date = "$year-$month-$currentDayRel";
        
        // Check if the current day is today
        $today = $date == date('Y-m-d') ? 'today' : '';

        // Create the cell for the current day with a clickable link
        $calendar .= "<td class='$today'><h3><a href='book-appointment.php?date=$date&practitioner_id=$practitioner_id'>$currentDayRel</a></h3></td>";

        // Increment the day counter and day of the week
        $currentDay++;
        $dayOfWeek++;
    }

    // Add empty cells if the last row is not complete
    if($dayOfWeek < 7){
        $remainingDays = 7 - $dayOfWeek;
        for($i = 0; $i < $remainingDays; $i++){
            $calendar .= "<td class='empty'></td>";
        }
    }

    // End the last row and the table
    $calendar .= "</tr></table>";

    return $calendar;
}

// Function to get practitioner's name based on practitioner_id
function get_practitioner_name($practitioner_id) {
    // Create an instance of the database connection class
    $dbh = new Dbh();
    $pdo = $dbh->getPdo();    

    // SQL query to fetch practitioner's name
    $sql = "SELECT full_name FROM practitioners WHERE practitioner_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$practitioner_id]);
    $result = $stmt->fetch();
    return $result['full_name'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/header.footer.css">
    <link rel="stylesheet" href="css/calendar.css">
    <link rel="stylesheet" href="css/main.css">
    <title>Calendar</title>
</head>
<body>
<?php include 'includes/client_header.inc.php'; ?><br>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                    // Retrieve practitioner_id from GET parameter
                    if (!isset($_GET['practitioner_id'])) {
                        die("No user selected.");
                    }
                    $practitioner_id = intval($_GET['practitioner_id']);
                    
                    // Get practitioner's name
                    $practitioner_name = get_practitioner_name($practitioner_id);
                    
                    // Display the practitioner's name
                    echo "<h3 class='small-text'>You are booking an appointment with $practitioner_name</h3><br>";

                    echo "<p class='instruction-text'>Click on one of the dates to make an appointment</p><br>";

                    // Get current month and year
                    $dateComponents = getdate();
                    
                    // Check if month and year are passed via GET parameters
                    if(isset($_GET['month']) && isset($_GET['year'])) {
                        $month = $_GET['month'];
                        $year = $_GET['year'];
                    } else {
                        $month = $dateComponents['mon'];
                        $year = $dateComponents['year'];
                    }

                    // // Display the calendar heading with formatted text
                    // $monthName = date('F', mktime(0, 0, 0, $month, 1, $year));
                    // echo "<h2 class='calendar-heading'>$monthName $year</h2>";

                    // Display the calendar for the given month and year
                    echo build_calendar($month, $year, $user_id, $practitioner_id);
                ?>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.inc.php'; ?>
</body>
</html>
