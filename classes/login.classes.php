<?php

class Login extends Dbh {

    protected function getUser($username, $pwd) {
        $stmt = $this->connect()->prepare('SELECT user_pwd FROM users WHERE username = ? OR user_email = ?;');

        if(!$stmt->execute(array($username, $pwd))){
            $stmt = null;
            header ("location: ../login.php?error=stmtfailed");
            exit();
        }

        if($stmt->rowCount() == 0) 
        {
            $stmt = null;
            header ("location: ../login.php?error=usernotfound");
            exit();
        }

        $pwdHashed = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $checkPwd = password_verify($pwd, $pwdHashed[0]["user_pwd"]);

        if($checkPwd == false) 
        {
            $stmt = null;
            header ("location: ../login.php?error=wrongpassword");
            exit();
        }
        elseif($checkPwd == true) {
            $stmt = $this->connect()->prepare('SELECT * FROM users WHERE username = ? OR user_email = ? AND user_pwd = ?;');

            if(!$stmt->execute(array($username, $username, $pwd))){
                $stmt = null;
                header ("location: ../login.php?error=stmtfailed");
                exit();
            }

            if($stmt->rowCount() == 0) 
            {
                $stmt = null;
                header ("location: ../login.php?error=usernotfound");
                exit();
            }

            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);

            session_start();
            $_SESSION["user_id"] = $user[0]["user_id"];
            $_SESSION["username"] = $user[0]["username"];
            $_SESSION["user_type"] = $user[0]["user_type"];

            $stmt = null;
        }


    }

}