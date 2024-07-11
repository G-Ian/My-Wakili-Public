<?php
require_once 'includes/config_session.inc.php';
require_once 'includes/signup_view.inc.php';

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


    <section class="login-signup">

        <div>
            <form action="includes/signup.inc.php" method="post" class="login-signup-form">
            <h2>Sign Up</h2>

            <?php 
            signup_inputs();
            ?>

            <label for="user_type">User Type:</label>
                    <select name="user_type" id="user_type">
                        <option value="client">Client</option>
                        <option value="legal_practitioner">Legal practitioner</option>
                    </select>

                <br>
                <button type="submit" name="submit">Sign Up</button>
            </form>

            <br><br>
            <div class="instruction-text">
                <p class="instruction-text">Already have an account?</p>
                <br>
                <a href="Login.php">Login</a>
            </div>

            <?php
            check_signup_errors();
            ?>

        </div>
    </section>
    


    <?php include 'includes/footer.inc.php'; ?>


</body>
</html>