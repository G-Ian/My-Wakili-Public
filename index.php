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
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/header.footer.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
</head>

<body>
 
    <!-- <header>
        <nav>
            <ul>
                <li><a href="#lawyers">Find a lawyer</a></li>
                <li><a href="#documents">Quick Downloads</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#appointments">Appointments</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </header> -->

    
    <br><br><br><br>
    <div class="mywakili">   
            <div class="medium-text">MY</div>
            <div class="large-text">WAKILI</div>
    </div>

    <div class="instruction-text">
        <p class="instruction-text">LOG INTO YOUR ACCOUNT USING ONE OF THE BUTTONS BELOW</p>
    </div>
    <br>

    <div class="button-container">
        <a href="login.php" class="find-lawyer-button">Find a Lawyer</a>
        <a href="login.php" class="iam-lawyer-button">I am a Lawyer</a>
    </div>
    <br><br><br>

    <div class="instruction-text">
        <p class="instruction-text">Don't have an account?</p>
        <br>
        <a href="signup.php">Sign Up</a>
    </div>

   

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Gallant Media. All rights reserved.</p>
    </footer>

</body>

</html>