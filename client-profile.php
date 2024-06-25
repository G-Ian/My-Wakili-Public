<?php
error_reporting(E_ALL);
include "classes/dbh.classes.php";
include "classes/profileinfo.classes.php";
include "classes/profileinfo-view.classes.php";

$profileInfo = new ProfileInfoView();

// Retrieve user_id from GET parameter (assuming it's passed in the URL)
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/header.footer.css">
    <link rel="stylesheet" type="text/css" href="css/dash.css">
</head>
<body>

    <?php include 'includes/client_header.inc.php'; ?>

    <br><br><br>
    <div class="container">
        <div class="sidebar">
            <img src="images/profile-picture.jpg" alt="Profile Picture" class="profile-picture" id="profile-picture">
            <div class="fullname-overlay">
                <div class="info-item">
                    <p class="item-data2">
                        <?php
                            // Example method call using $user_id directly
                            $profileInfo->fetchProfession($user_id);
                        ?>
                    </p>
                </div>

                <h1 class="username2" id="fullname">
                    <?php
                        // Example method call using $user_id directly
                        $profileInfo->fetchFullname($user_id);
                    ?>
                </h1>
            </div>

            <br><br><br>
            <div class="social-media">
                <p class="item-label">Social Media Links:</p>
                <a href="#" class="social-link" id="twitter-link" target="_blank">Twitter</a>
                <a href="#" class="social-link" id="github-link" target="_blank">GitHub</a>
                <a href="#" class="social-link" id="linkedin-link" target="_blank">LinkedIn</a>
            </div>

            <!-- Form now uses user_id instead of practitioner_id -->
            <form action="book-calendar.php" method="get">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                <button type="submit" class="book-appointment-button">Book Appointment</button>
            </form>

        </div>


        <div class="content">
            <div class="profile-details">
                <div class="additional-info1">
                    <div class="detail-item">
                        <p class="item-label">Profession:</p>
                        <p class="item-data">
                            <?php
                                // Example method call using $user_id directly
                                $profileInfo->fetchProfession($user_id);
                            ?>
                        </p>
                    </div>

                    <div class="bio-data">
                        <p class="item-label">Professional Bio:</p>
                        <p class="bio-text">
                            <?php
                                // Example method call using $user_id directly
                                $profileInfo->fetchBioText($user_id);
                            ?>
                        </p>
                    </div>

                    <div class="detail-item">
                        <p class="item-label">Year of Admission:</p>
                        <p class="item-data">
                            <?php
                                // Example method call using $user_id directly
                                $profileInfo->fetchExperience($user_id);
                            ?>
                        </p>
                    </div>

                    <div class="detail-item">
                        <p class="item-label">Specializations:</p>
                        <p class="item-data">
                            <?php
                                // Example method call using $user_id directly
                                $profileInfo->fetchSpecializations($user_id);
                            ?>
                        </p>
                    </div>

                    <div class="detail-item">
                        <p class="item-label">Firm:</p>
                        <p class="item-data">
                            <?php
                                // Example method call using $user_id directly
                                $profileInfo->fetchFirm($user_id);
                            ?>
                        </p>
                    </div>
                </div>

                <div class="additional-info">
                    <div class="info-item">
                        <p class="item-label">Location:</p>
                        <p class="item-data">
                            <?php
                                // Example method call using $user_id directly
                                $profileInfo->fetchAddress($user_id);
                            ?>
                        </p>
                    </div>
                    <div class="info-item">
                        <p class="item-label">Email:</p>
                        <p class="item-data">
                            <?php
                                // Example method call using $user_id directly
                                $profileInfo->fetchEmail($user_id);
                            ?>
                        </p>
                    </div>
                    <div class="info-item">
                        <p class="item-label">Phone Number:</p>
                        <p class="item-data">
                            <?php
                                // Example method call using $user_id directly
                                $profileInfo->fetchPhoneNumber($user_id);
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
                            // Example method call using $user_id directly
                            $startHours = $profileInfo->fetchStartHours($user_id);
                            $endHours = $profileInfo->fetchEndHours($user_id);
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
