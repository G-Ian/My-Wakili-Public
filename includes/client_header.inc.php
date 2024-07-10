<?php
session_start();
error_reporting(0); // Suppress PHP warnings

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
        <input type="checkbox" id="menu-toggle">
        <label for="menu-toggle" class="menu-icon">&#9776;</label>
        <ul class="menu">
            <!-- <li><a href="client-profiles.php">Home</a></li> -->
            <li><a href="client-profiles.php">Lawyers</a>
                <ul>
                    <li><a href="client-profiles.php?search=Criminal%20Law">Criminal Law</a></li>
                    <li><a href="client-profiles.php?search=Civil%20Law">Civil Law</a></li>
                    <li><a href="client-profiles.php?search=Convenyancing%20Law">Convenyancing Law</a></li>
                    <li><a href="client-profiles.php?search=Employment%20and%20Labour%20Law">Employment and Labour Law</a></li>
                    <li><a href="client-profiles.php?search=Family%20Law">Family Law</a></li>
                    <li><a href="client-profiles.php?search=Constitutional%20Law">Constitutional Law</a></li>
                </ul>
            </li>
            <li><a href="client-appointments.php">Appointments</a></li>
            <li><a href="documents.php">Downloads</a></li>
            <!-- <li><a href="client-profiles.php">Services</a>
                <ul>
                    <li><a href="client-profiles.php?search=Court%20Process%20Server">Court Process Server</a></li>
                    <li><a href="client-profiles.php?search=Consultation">Consultation</a></li>
                    <li><a href="client-profiles.php?search=Litigation">Litigation</a></li>
                    <li><a href="client-profiles.php?search=Contract%20drafting%20and%20review">Contract drafting and review</a></li>
                    <li><a href="client-profiles.php?search=Mediation">Mediation</a></li>
                    <li><a href="client-profiles.php?search=Other">Other</a></li>
                </ul>
            </li> -->
            <li><a href="contact.php">Contact Us</a></li>
        </ul>

        <!-- Begin: Added Username Display and Session Check -->
        <div class="logout">
            <a href="pract-dashboard.php" class="username">Welcome, <?php echo htmlspecialchars($username); ?>!</a> <!-- Display the username as a link -->
            <form action="includes/logout.inc.php" method="post">
                <button type="default">Logout</button>
            </form>
        </div>

        <!-- End: Added Username Display and Session Check -->
    </nav>
</header>
