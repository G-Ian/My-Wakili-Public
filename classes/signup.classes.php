<?php

class Signup extends Dbh {

    protected function setUser($username, $user_email, $pwd, $user_type) {
        // Insert user into users table
        $stmt = $this->connect()->prepare('INSERT INTO users (username, user_email, user_pwd, user_type) VALUES(?, ?, ?, ?);');

        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

        if(!$stmt->execute(array($username, $user_email, $hashedPwd, $user_type))){
            $stmt = null;
            header ("location: ../signup.php?error=setuserfailed");
            exit();
        }

        $stmt = null;

        // Check if user_type is 'legal practitioner'
        if ($user_type === 'legal practitioner') {
            // Fetch user ID
            $user_idArray = $this->getuser_id($username);
            $user_id = $user_idArray[0]["user_id"];
    
            // Set default profile info
            $this->defaultProfileInfo($user_id, $username, $user_email);
        }

    }
    
    protected function checkUser($username, $user_email) {
        $stmt = $this->connect()->prepare('SELECT username FROM users WHERE username = ? OR user_email = ?;');

        if(!$stmt->execute(array($username, $user_email))){
            $stmt = null;
            header ("location: ../signup.php?error=usernameTakenCheckfailed");
            exit();
        }

        $resultCheck;
        if($stmt->rowCount() > 0) {
            $resultCheck = false;
        }
        else {
            $resultCheck = true;
        }

        return $resultCheck;
    }

    protected function getuser_id($username) {
        $stmt = $this->connect()->prepare('SELECT user_id FROM users WHERE username = ?;');
        if(!$stmt->execute(array($username))) {
            $stmt = null;
            header("location:../signup.php?error=getuser_idfailed(newuser)");
            exit();
        }

        if($stmt->rowCount() == 0) {
            $stmt = null;
            header("location:../signup.php?error=profilenotfound");
            exit();
        }

        $profileData = $stmt->fetchALL(PDO::FETCH_ASSOC);

        return $profileData;
    }

    // protected function checkuser_type($username, $user_type) {
    //     $stmt = $this->connect()->prepare('SELECT user_id FROM users WHERE username = ?;');

    //     if($stmt->execute(array($username))) {
    //         $stmt = null;
    //         header("location:../profile.php?error=stmtfailed");
    //         exit();
    //     }

    //     if($stmt->rowCount() == 0) {
    //         $stmt = null;
    //         header("location:../profile.php?error=profilenotfound");
    //         exit();
    //     }

    //     $profileData = $stmt->fetchALL(PDO::FETCH_ASSOC);

    //     return $profileData;
    // } 
    // (FINISH CHECK user_type TO SEND USERS TO PROFILE CREATION PAGE ONLY IF...)

}