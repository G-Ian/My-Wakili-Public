<?php
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) 
{

    // grabbing data from signup form
    $username = htmlspecialchars($_POST["username"], ENT_QUOTES, 'UTF-8');
    $user_email = htmlspecialchars($_POST["email"], ENT_QUOTES, 'UTF-8');
    $pwd = htmlspecialchars($_POST["pwd"], ENT_QUOTES, 'UTF-8');
    $pwdRepeat = htmlspecialchars($_POST["pwdRepeat"], ENT_QUOTES, 'UTF-8');
    $user_type = htmlspecialchars($_POST["user_type"], ENT_QUOTES, 'UTF-8');

    // // Process selected specializations
    // $law_specializations = isset($_POST['specialization']) ? $_POST['specialization'] : [];


    //Instantiate SignupContr class
    include "../classes/dbh.classes.php";
    include "../classes/signup.classes.php";
    include "../classes/signup-contr.classes.php";

    $signup = new SignupContr($username, $user_email, $pwd, $pwdRepeat, $user_type);

    //Running error handlers and user signup
    $signup->signupUser();

    // // Save specializations along with other profile information
    // $signup->saveSpecializations($law_specializations);


    // Redirect based on user type
    if ($user_type === 'legal_practitioner') {
        // Fetch user_id after signup
        $user_id = $signup->fetchuser_id($username);

        // Instantiate ProfileInfoContr class
        include "../classes/profileinfo.classes.php";
        include "../classes/profileinfo-contr.classes.php";

        // Define default specializations
        $defaultSpecializations = [["General Practice"]]; // Add more default specializations if needed


        // Provide required arguments when calling defaultProfileInfo
        $profileInfo = new ProfileInfoContr($user_id, $username, $user_type);
        
        // Call defaultProfileInfo with the appropriate arguments
        $profileInfo->defaultProfileInfo($user_id, $username, $user_email, $defaultSpecializations);
        
        // Redirect to profile page
        header("location: ../login.php?signup=success");
    } else {
        // Redirect to clients dashboard page
        header("location: ../login.php?signup=success");
    }
    
    exit();
}