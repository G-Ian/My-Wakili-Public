<?php
require_once 'includes/config_session.inc.php';
require_once 'includes/signup_view.inc.php';
require_once 'includes/login_view.inc.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Signup - My Wakili</title>
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/header.footer.css">
    <link rel="stylesheet" type="text/css" href="css/login_signup.css">   
    
   
</head>
<body>
    
    <section class="login-signup">
        <div >
            <form action="includes/login.inc.php" method="post" class="login-signup-form">
                <h2>Login</h2>
                <input type="username" name="username" placeholder="Username" required>
                <input type="password" name="pwd" placeholder="Password" required>
                <button type="submit" name="submit">Login</button>
            </form>

            <br><br><br>
            <?php
            check_signup_errors();
            ?>

            <?php
            check_login_errors();
            ?>

        </div>

        
    </section>

    <br><br>
    <div class="instruction-text">
        <p class="instruction-text">Don't have an account?</p>
        <br>
        <a href="signup.php">Sign Up</a>
    </div>
    


    <?php include 'includes/footer.inc.php'; ?>


</body>
</html>