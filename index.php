<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wakili</title>
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/header.footer.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body>

     
    <br><br><br><br>
    <div class="mywakili">   
            <div class="medium-text">MY</div>
            <div class="large-text">WAKILI</div>
    </div>

    <p class="small-text">For all your legal needs</p><br>

    <div class="instruction-text">
        <p class="instruction-text">LOG INTO YOUR ACCOUNT USING ONE OF THE BUTTONS BELOW</p>
    </div>
    <br>

    <div class="button-container">
        <a href="login.php" type="default">Find a Lawyer</a>
        <a href="login.php" type="default">I am a Lawyer</a>
    </div>
    <br><br><br>


    <div class="instruction-text">
        <p class="instruction-text">Don't have an account?</p>
        <br>
        <a href="signup.php">Sign Up</a>
    </div>

   

    <?php include 'includes/footer.inc.php'; ?>

</body>

</html>