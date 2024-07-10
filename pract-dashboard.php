<?php
error_reporting(E_ALL);

// Start or resume the session
session_start();

// Check if practitioner_id is set in the session
if (!isset($_SESSION["practitioner_id"])) {
    // Redirect to login or appropriate page if practitioner_id is not set
    header("location: index.php?error=not_logged_in");
    exit();
}

// Include necessary files
include "classes/dbh.classes.php";
include "classes/profileinfo.classes.php";
include "classes/profileinfo-view.classes.php";

// Assuming you have the practitioner_id stored in the session
$practitioner_id = $_SESSION["practitioner_id"];

$profileInfo = new ProfileInfoView();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practitioners Profile Page</title>
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/header.footer.css">
    <link rel="stylesheet" type="text/css" href="css/dash.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>

    <?php include 'includes/pract_header.inc.php'; ?>

    
    <div class="container">
        <div class="sidebar">
            <img src="images/profile-picture.jpg" alt="Profile Picture" class="profile-picture" id="profile-picture">
            
            <div class="fullname-overlay">
                <div class="info-item">
                    <p class="item-data2">
                        <?php
                            $profileInfo->fetchProfession($practitioner_id);
                        ?>
                    </p>
                </div>
            
                <h1 class="username2" id="fullname">
                    <?php
                        $profileInfo->fetchFullname($practitioner_id);
                    ?>
                </h1>

                <br><br><br>
                <div class="social-media">
                    <p class="item-label">Social Media Links:</p>
                    <a href="#" class="social-link" id="twitter-link" target="_blank">Twitter</a>
                    <a href="#" class="social-link" id="github-link" target="_blank">GitHub</a>
                    <a href="#" class="social-link" id="linkedin-link" target="_blank">LinkedIn</a>
                </div>

                <form action="pract-profilesettings.php" method="post">
                    <input type="hidden" name="practitioner_id" value="<?= $practitioner_id ?>">
                    <button class="book-appointment-button">Update Profile</button>
                </form>
                
            </div>
        </div>

        <div class="content">
            <div class="blog-posts">
                <div class="additional-info1">
                    <div class="detail-item">
                        <p class="item-label">Profession:</p>
                        <p class="item-data">
                            <?php
                                $profileInfo->fetchProfession($practitioner_id);
                            ?>
                        </p>
                    </div>
                    
                    <div class="bio-data">
                        <p class="item-label">Professional Bio:</p>
                        <p class="bio-text">
                            <?php
                                $profileInfo->fetchBioText($practitioner_id);
                            ?>
                        </p>
                    </div>

                    <div class="detail-item">
                        <p class="item-label">Year of Admission:</p>
                        <p class="item-data">
                        <?php
                                $profileInfo->fetchExperience($practitioner_id);
                            ?>
                        </p>
                    </div>

                    <div class="detail-item">
                        <p class="item-label">Specializations:</p>
                        <p class="item-data">
                        <?php
                                $profileInfo->fetchSpecializations($practitioner_id);
                            ?>
                        </p>
                    </div>

                    <div class="detail-item">
                        <p class="item-label">Firm:</p>
                        <p class="item-data">
                            <?php
                                $profileInfo->fetchFirm($practitioner_id);
                            ?>
                        </p>
                    </div>
                </div>

                <div class="additional-info">
                    <div class="info-item">
                        <p class="item-label">Location:</p>
                        <p class="item-data">
                            <?php
                                $profileInfo->fetchAddress($practitioner_id);
                            ?>
                        </p>
                    </div>

                    <div class="info-item">
                        <p class="item-label">Email:</p>
                        <p class="item-data">
                            <?php
                                $profileInfo->fetchEmail($practitioner_id);
                            ?>
                        </p>
                    </div>

                    <div class="info-item">
                        <p class="item-label">Phone Number:</p>
                        <p class="item-data">
                            <?php
                                $profileInfo->fetchPhoneNumber($practitioner_id);
                            ?>
                        </p>
                    </div>
                </div>

            </div>

            <div class="appointment-info">
                <div class="detail-item">
                    <p class="item-label">Working Hours:</p>
                    <p class="item-data">
                        <?php
                            $startHours = $profileInfo->fetchStartHours($practitioner_id);
                            $endHours = $profileInfo->fetchEndHours($practitioner_id);
                            echo "$startHours to $endHours";
                        ?>
                    </p>
                </div> 

                <br>


            </div>

        </div>
    </div>

</body>
</html>
