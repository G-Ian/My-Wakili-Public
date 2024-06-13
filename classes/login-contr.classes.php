<?php

class loginContr extends Login {

    private $username;
    private $pwd;

    
    
    public function __construct($username, $pwd) {
        $this->username = $username;
        $this->pwd = $pwd;
    }

    public function loginUser () {
        if($this->emptyInput() == false) {
            //echo "Empty input!";
            header("location: ../login.php?error=emptyinput");
            exit();
        }


        $this->getUser($this->username, $this->pwd);

    }

    
    private function emptyInput () {
        $result;
        if (empty($this->username) || empty($this->pwd)) 
        {
            $result = false;
        }
        else {
            $result = true;
        }
        return $result;
    }


    public function getuser_type($username) {
        $stmt = $this->connect()->prepare('SELECT user_type FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $result = $stmt->fetchColumn();
        return $result;
    }

    public function profileIncomplete($username) {
        $stmt = $this->connect()->prepare('SELECT COUNT(*) FROM profiles WHERE username = ? AND username IS NULL');
        $stmt->execute([$username]);
        $result = $stmt->fetchColumn();
        return $result > 0;
    }

    
}