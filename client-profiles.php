<?php
session_start();
include "classes/profileinfo-view.classes.php";
include "classes/profileinfo-contr.classes.php";

// Handle search query
$searchQuery = isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : '';
$profileView = new ProfileInfoView();
$profiles = $profileView->fetchProfilesByKeyword($searchQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legal Practitioners</title>
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/header.footer.css">
    <link rel="stylesheet" type="text/css" href="css/client.css">

</head>
<body>
    <?php include 'includes/client_header.inc.php'; ?>

    <header>
        <br><br>
        <h1>Legal Practitioners</h1>
        <!--search Bar -->
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search by keyword..." value="<?= $searchQuery ?>">
            <button type="submit">Search</button>
        </form>
    </header>
    
    <main>
    <div class="profile-container">
        <?php if ($profiles): ?>
            <?php foreach ($profiles as $profile): ?>
                <div class="profile-box">
                    <?php 
                    $initials = strtoupper(substr($profile['full_name'], 0, 1)); 
                    if (strpos($profile['full_name'], ' ') !== false) {
                        $initials .= strtoupper(substr($profile['full_name'], strpos($profile['full_name'], ' ') + 1, 1));
                    }
                    ?>
                    <!-- Check if profile picture exists and is not empty -->
                    <?php if (isset($profile['profile_picture']) && !empty($profile['profile_picture'])): ?>
                        <a href="client-profile.php?practitioner_id=<?= $profile['practitioner_id'] ?>"><img src="<?= htmlspecialchars($profile['profile_picture'], ENT_QUOTES, 'UTF-8') ?>" alt="Profile Picture" class="profile-picture"></a>
                    <?php else: ?>
                        <a href="client-profile.php?practitioner_id=<?= $profile['practitioner_id'] ?>"><div class="profile-picture initials"><?= $initials ?></div></a>
                    <?php endif; ?>
                    
                    <!-- Linking to the lawyer's profile & showing profile info -->
                    <h2><a href="client-profile.php?practitioner_id=<?= $profile['practitioner_id'] ?>"><?= htmlspecialchars($profile['full_name'], ENT_QUOTES, 'UTF-8') ?></h2>

                    <p class="profession"><?= htmlspecialchars($profile['profession'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="firm"><?= htmlspecialchars($profile['firm'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p>Recognized since <?= htmlspecialchars($profile['experience_years'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="areas-of-expertise">Areas of expertise:</p>
                    <?php 
                    $specializations = json_decode($profile['specializations'], true); 
                    $specializationsString = is_array($specializations) ? implode(', ', $specializations) : '';
                    ?>
                    <p class="specializations"><?= htmlspecialchars($specializationsString, ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="areas-of-expertise">Location:</p>
                    <p class="physical-address"><?= htmlspecialchars($profile['physical_address'], ENT_QUOTES, 'UTF-8') ?></p>
                    
                    </a> 
                    <!-- End of linking anchor tag -->

                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <p>No practitioners found.</p>
        <?php endif; ?>
    </div>
    </main>





    <!-- Add any necessary JavaScript here -->
</body>
</html>
