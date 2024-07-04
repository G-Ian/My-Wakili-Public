<?php
session_start(); // Start the session

// Check if the user is logged in
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
} else {
    // Redirect to login page if not logged in
    header("location: login.php?signup=success");
    exit();
}
?>

<header class="header-main">
        <nav>
            <a href="index.php" class="logo">
                <img src="images/wlogo.png" alt="My Wakili">
            </a>
            <ul>
                <li><a href="pract-dashboard.php">Profile</a></li>
                <!-- <li><a href="#">Lawyers</a>
                    <ul>
                        <li><a href="#">Criminal Law</a></li>
                        <li><a href="#">Civil Law</a></li>
                        <li><a href="#">Convenyancing Law</a></li>
                        <li><a href="#">Employment and Labour Law</a></li>
                        <li><a href="#">Family Law</a></li>
                        <li><a href="#">Constitutional Law</a></li>
                    </ul>
                </li>
                <li><a href="documents.php">Downloads</a></li>
                <li><a href="#">Services</a>
                    <ul>
                        <li><a href="#">Court Processed Service</a></li>
                        <li><a href="#">Consultation</a></li>
                        <li><a href="#">Litigation</a></li>
                        <li><a href="#">Contract drafting and review</a></li>
                        <li><a href="#">Mediation</a></li>
                        <li><a href="#">Other</a></li>
                    </ul>
                </li> -->
                
                <li><a href="pract-appointments.php">Appointments</a></li>
                <li><a href="documents.php">Downloads</a></li>
                <li><a href="contact.php">Contact Us</a></li>

                <!-- <li><a href="#">Client Requests</a></li> -->
                <!-- <li><a href="#">Schedule</a></li> -->
            </ul>
        </nav>


        <!-- Begin: Added Username Display and Session Check -->
        <div class="logout">
            <a href="pract-profilesettings.php" class="username">Welcome, <?php echo htmlspecialchars($username); ?>!</a> <!-- Display the username as a link -->
            <form action="includes/logout.inc.php" method="post">
                <button class="logout-button">Logout</button>
            </form>
        </div>
        <!-- End -->


    </header>

