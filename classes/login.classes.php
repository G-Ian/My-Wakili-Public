<?php

class Login extends Dbh {

    protected function getUser($username, $pwd) {
        $stmt = $this->connect()->prepare('SELECT user_pwd FROM users WHERE username = ? OR user_email = ?;');

        if(!$stmt->execute(array($username, $username))){
            $stmt = null;
            header("location: ../login.php?error=stmtfailed");
            exit();
        }

        if($stmt->rowCount() == 0) {
            $stmt = null;
            header("location: ../login.php?error=usernotfound");
            exit();
        }

        $pwdHashed = $stmt->fetch(PDO::FETCH_ASSOC);
        $checkPwd = password_verify($pwd, $pwdHashed["user_pwd"]);

        if($checkPwd == false) {
            $stmt = null;
            header("location: ../login.php?error=wrongpassword");
            exit();
        }

        $stmt = $this->connect()->prepare('SELECT * FROM users WHERE (username = ? OR user_email = ?) AND user_pwd = ?;');

        if(!$stmt->execute(array($username, $username, $pwdHashed["user_pwd"]))){
            $stmt = null;
            header("location: ../login.php?error=stmtfailed");
            exit();
        }

        if($stmt->rowCount() == 0) {
            $stmt = null;
            header("location: ../login.php?error=usernotfound");
            exit();
        }

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        session_start();
        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["user_type"] = $user["user_type"];
        $_SESSION["is_admin"] = $user["is_admin"];

        if ($user["is_admin"] == 1) {
            header("location: ../admin-panel.php");
        } else {
            if ($user["user_type"] == 'client') {
                header("location: ../client-dashboard.php");
            } elseif ($user["user_type"] == 'practitioner') {
                header("location: ../practitioner-dashboard.php");
            } else {
                header("location: ../profile.php");
            }
        }

        $stmt = null;
    }
}
?>
