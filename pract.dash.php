<?php
error_reporting(E_ALL);
include "classes/dbh.classes.php";
include "classes/profileinfo.classes.php";
include "classes/profileinfo-view.classes.php";

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
</head>
<body>

    <?php include 'includes/pract_header.inc.php'; ?>

    <br><br><br>
    <div class="container">
        <div class="sidebar">
            <img src="images/profile-picture.jpg" alt="Profile Picture" class="profile-picture" id="profile-picture">
            
            <div class="fullname-overlay">
                <div class="info-item">
                    <p class="item-data2">
                        <?php
                            $profileInfo->fetchProfession($_SESSION["user_id"]);
                        ?>
                    </p>
                </div>
            
                <h1 class="username2" id="fullname">
                    <?php
                        $profileInfo->fetchFullname($_SESSION["user_id"]);
                    ?>
                </h1>


                <br><br><br>
                <div class="social-media">
                    <p class="item-label">Social Media Links:</p>
                    <a href="#" class="social-link" id="twitter-link" target="_blank">Twitter</a>
                    <a href="#" class="social-link" id="github-link" target="_blank">GitHub</a>
                    <a href="#" class="social-link" id="linkedin-link" target="_blank">LinkedIn</a>
                </div>
                
            </div>
        </div>

        <div class="content">
            <div class="blog-posts">
                <div class="additional-info1">
                    <div class="detail-item">
                        <p class="item-label">Profession:</p>
                        <p class="item-data">
                            <?php
                                $profileInfo->fetchProfession($_SESSION["user_id"]);
                            ?>
                        </p>
                    </div>
                    
                    <div class="bio-data">
                        <p class="item-label">Professional Bio:</p>
                        <p class="bio-text">
                            <?php
                                $profileInfo->fetchBioText($_SESSION["user_id"]);
                            ?>
                        </p>
                    </div>

                    <div class="detail-item">
                        <p class="item-label">Year of Admission:</p>
                        <p class="item-data">
                        <?php
                                $profileInfo->fetchExperience($_SESSION["user_id"]);
                            ?>
                        </p>
                    </div>

                    <div class="detail-item">
                        <p class="item-label">Firm:</p>
                        <p class="item-data">
                            <?php
                                $profileInfo->fetchFirm($_SESSION["user_id"]);
                            ?>
                        </p>
                    </div>
                </div>

                <div class="additional-info">
                    <div class="info-item">
                        <p class="item-label">Location:</p>
                        <p class="item-data">
                            <?php
                                $profileInfo->fetchAddress($_SESSION["user_id"]);
                            ?>
                        </p>
                    </div>

                    <div class="info-item">
                        <p class="item-label">Email:</p>
                        <p class="item-data">
                            <?php
                                $profileInfo->fetchEmail($_SESSION["user_id"]);
                            ?>
                        </p>
                    </div>

                    <div class="info-item">
                        <p class="item-label">Phone Number:</p>
                        <p class="item-data">
                            <?php
                                $profileInfo->fetchPhoneNumber($_SESSION["user_id"]);
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
                            $startHours = $profileInfo->fetchStartHours($_SESSION["user_id"]);
                            $endHours = $profileInfo->fetchEndHours($_SESSION["user_id"]);
                            echo "$startHours to $endHours";
                        ?>
                    </p>
                </div> 

                <br>
                <form action="profilesettings.php" method="post">
                    <input type="hidden" name="user_id" value="<?= $userID ?>">
                    <button class="book-appointment-button">Update Profile</button>
                </form>

            </div>

        </div>
    </div>

</body>
</html>
