<?php
// Function to build the calendar for a given month and year
function build_calendar($month, $year, $practitioner_id) {
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
    $calendar = "<h2>$monthName $year</h2><br>";
    
    // Navigation buttons for previous month, current month, and next month
    $calendar .= "<a class='btn btn-primary btn-xs' href='?month=".$prev_month."&year=".$prev_year."&practitioner_id=$practitioner_id'>Prev Month</a> ";
    $calendar .= "<a class='btn btn-primary btn-xs' href='?month=".date('m')."&year=".date('Y')."&practitioner_id=$practitioner_id'>Current Month</a> ";
    $calendar .= "<a class='btn btn-primary btn-xs' href='?month=".$next_month."&year=".$next_year."&practitioner_id=$practitioner_id'>Next Month</a>";

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/calendar.css">
    <title>Calendar</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                    // Retrieve practitioner_id from GET parameter
                    if (!isset($_GET['practitioner_id'])) {
                        die("No practitioner selected.");
                    }
                    $practitioner_id = intval($_GET['practitioner_id']);
                    
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

                    // Display the calendar for the given month and year
                    echo build_calendar($month, $year, $practitioner_id);
                ?>
            </div>
        </div>
    </div>
</body>
</html>
