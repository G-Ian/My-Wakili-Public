<?php
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: MY WAKILI/login.php");
    exit();
}

include "classes/dbh.classes.php";
include "classes/profileinfo.classes.php";
include "classes/profileinfo-contr.classes.php";
include "classes/profileinfo-view.classes.php";

$profileInfo = new ProfileInfoView();
$profileData = $profileInfo->fetchProfileInfo($_SESSION["user_id"]);

if ($profileData === null) {
    // Handle the case where the profile is not found
    echo "<p>Profile not found. Please create your profile.</p>";
} else {
    $fullName = $profileData[0]['full_name'];
    $userEmail = $profileData[0]['user_email'];
    $profession = $profileData[0]['profession'];
    $firm = $profileData[0]['firm'];
    $specializations = $profileData[0]['specializations'];
    $experience = $profileData[0]['experience_years'];
    $phoneNumber = $profileData[0]['phone_number'];
    $working_hours_start = $profileData[0]['working_hours_start'];
    $working_hours_end = $profileData[0]['working_hours_end'];
    $physicalAddress = $profileData[0]['physical_address'];
    $profileAbout = $profileData[0]['profile_about'];
    // Other profile fields...
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practitioner Profile</title>
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/header.footer.css">
    <link rel="stylesheet" type="text/css" href="css/profile.css">

</head>
<body>

    <header>

    </header>

    <h1>Update Your Profile</h1>
    <form action="includes/profileinfo.inc.php" method="post" enctype="multipart/form-data" class="body-form"> <!-- Add enctype attribute for file upload -->
        <!-- <label for="profile_picture">Profile Picture:</label><br>
        <input type="file" id="profile_picture" name="profile_picture" accept="image/*"><br> Add file input for profile picture -->
        <label for="full_name">Full Name:</label><br>
        <input type="text" id="full_name" name="full_name" placeholder="Enter your Full Name" value="<?php echo $fullName ?? ''; ?>" required><br>
        <label for="profile_about">Profile About:</label><br>
        <textarea id="profile_about" name="profile_about" placeholder="Tell potential clients about yourself" required><?php echo $profileAbout ?? ''; ?></textarea><br>
        
        <label for="profession">Profession:</label><br>
            <select id="profession" name="profession" required onchange="showSpecializations()">
                <option value="">Select Profession</option>
                <?php
                $professions = array(
                    "Advocate", "Prosecutor", "Paralegal", "Court Process Server",
                    "Court Clerk", "Investigator", "Mediator", "Legal Assistant",
                    "Clerk", "Solicitor", "Commissioner of Oaths", "Judge", "Other"
                );
                foreach ($professions as $prof) {
                    $selected = ($prof == $profession) ? 'selected' : '';
                    echo '<option value="' . $prof . '" ' . $selected . '>' . $prof . '</option>';
                }
                ?>
            </select><br>
            <input type="text" id="other_profession" name="other_profession" style="display: <?php echo ($profession == 'Other') ? 'block' : 'none'; ?>;" placeholder="Enter your profession" value="<?php echo $profession == 'Other' ? htmlspecialchars($otherProfession) : ''; ?>">

                    
      
        <div id="specializations" class="specializations" style="display: <?php echo ($profession == 'Advocate') ? 'block' : 'none'; ?>;">
            <fieldset> <!-- Wrap specialization options in a fieldset for styling -->
                <legend>Law Specializations:</legend> <!-- Add a legend for the fieldset -->
                    <label for="law_specializations">Which type(s) of law do you practise?</label><br>
                    <div class="specialization-grid">
                        <input type="checkbox" id="specialization1" name="law_specializations[]" value="Admiralty Law">
                        <label for="specialization1">Admirilty Law</label><br>
                        <input type="checkbox" id="specialization2" name="law_specializations[]" value="Bankruptcy Law">
                        <label for="specialization2">Bankruptcy Law</label><br>
                        <input type="checkbox" id="specialization3" name="law_specializations[]" value="Business Law">
                        <label for="specialization3">Business Law</label><br>
                        <input type="checkbox" id="specialization4" name="law_specializations[]" value="Civil Law">
                        <label for="specialization4">Civil Law</label><br>
                        <input type="checkbox" id="specialization5" name="law_specializations[]" value="Constitutional Law">
                        <label for="specialization5">Constitutional Law</label><br>
                        <input type="checkbox" id="specialization6" name="law_specializations[]" value="Contract Law">
                        <label for="specialization6">Contract Law</label><br>
                        <input type="checkbox" id="specialization7" name="law_specializations[]" value="Criminal Law">
                        <label for="specialization7">Criminal Law</label><br>
                        <input type="checkbox" id="specialization8" name="law_specializations[]" value="Environmental Law">
                        <label for="specialization8">Environmental Law</label><br>
                        <input type="checkbox" id="specialization9" name="law_specializations[]" value="Family Law">
                        <label for="specialization9">Family Law</label><br>
                        <input type="checkbox" id="specialization10" name="law_specializations[]" value="Immigration Law">
                        <label for="specialization10">Immigration Law</label><br>
                        <input type="checkbox" id="specialization_other" name="law_specializations[]" value="other" onchange="handleOtherSpecialization()">
                        <label for="specialization_other">Other</label>
                        <input type="text" id="other_specialization" name="other_specialization" style="display: none;" placeholder="Enter your specialization">
                        <!-- Add more checkboxes as needed -->
                    </div>
            </fieldset>
        </div>

        <label for="firm">Law Firm:</label><br>
        <input type="text" id="firm" name="firm"  placeholder="Which firm or company are you associated with?"  value="<?php echo $firm ?? ''; ?>" required><br>
        
        <label for="experience_years">Year of Admission:</label><br>
        <input type="number" id="experience_years" name="experience_years"  placeholder="When did you get admitted into the LSK"  value="<?php echo $experience ?? ''; ?>" required><br>
        
        <label for="physical address">Physical Address:</label><br>
        <input type="text" id="physical_address" name="physical_address"  placeholder="Where can clients find you?"  value="<?php echo $physicalAddress ?? ''; ?>" required><br>
        
        <label for="phone_number">Phone Number:</label><br>
        <input type="text" id="phone_number" name="phone_number"  placeholder="How can clients reach you?"  value="<?php echo $phoneNumber ?? ''; ?>" required><br>
       
        <label for="working_hours">Working Hours:</label><br>
        <div class="time-container">
            <label for="working_hours_start">Start:</label>
            <input type="time" id="working_hours_start" name="working_hours_start" value="<?php echo $working_hours_start ?? ''; ?>" required><br>
            
            <label for="working_hours_end">End:</label>
            <input type="time" id="working_hours_end" name="working_hours_end" value="<?php echo $working_hours_end ?? ''; ?>" required><br>
        </div>
        
        <button type="submit">Submit</button>
    </form>

    <script>
        function showSpecializations() {
            var profession = document.getElementById("profession").value;
            var specializationsDiv = document.getElementById("specializations");
            if (profession === "Advocate") {
                specializationsDiv.style.display = "block";
            } else {
                specializationsDiv.style.display = "none";
            }
        }

        function handleOtherSpecialization() {
            var otherCheckbox = document.getElementById("specialization_other");
            var otherSpecializationInput = document.getElementById("other_specialization");
            if (otherCheckbox.checked) {
                otherSpecializationInput.style.display = "block";
            } else {
                otherSpecializationInput.style.display = "none";
            }
        }
    </script>

    </form>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Gallant Media. All rights reserved.</p>
    </footer>
    
</body>
</html>