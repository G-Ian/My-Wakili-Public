<?php

// session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {

    // grabbing data from login form
    $username = htmlspecialchars($_POST["username"], ENT_QUOTES, 'UTF-8');
    $pwd = htmlspecialchars($_POST["pwd"], ENT_QUOTES, 'UTF-8');

    //Call(Instantiate) LoginContr class
    include "../classes/dbh.classes.php";
    include "../classes/login.classes.php";
    include "../classes/login-contr.classes.php";

    $login = new LoginContr($username, $pwd);

    //Running error handlers and user login
    $login->loginUser();

    // Redirect user based on user type and profile completion
    $user_type = $login->getuser_type($username);
    if ($user_type === 'client') {
        header("location:../client-profiles.php?login=success");
    } else {
        $profileComplete = $login->profileIncomplete($username);
        if ($profileComplete) {
            header("location:../dash.php?login=success");
        } else {
            header("location:../pract-dashboard.php?error=incomplete_profile");
        }
    }
    exit();
} else {
    header("location:../index.php?error=invalid_access");
    exit();
}
