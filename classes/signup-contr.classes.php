<?php

class SignupContr extends Signup {

    private $username;
    private $user_email;
    private $pwd;
    private $pwdRepeat;
    private $user_type;
    
    public function __construct($username, $user_email, $pwd, $pwdRepeat, $user_type) {
        $this->username = $username;
        $this->user_email = $user_email;
        $this->pwd = $pwd;
        $this->pwdRepeat = $pwdRepeat;
        $this->user_type = $user_type;
    }

    public function signupUser () {
        if($this->emptyInput() == false) {
            //echo "Empty input!";
            header("location: ../signup.php?error=emptyinput");
            exit();
        }

        if($this->invalidUsername() == false) {
            //echo "Invalid username!";
            header("location: ../signup.php?error=invalidusername");
            exit();
        }

        if($this->invalidEmail() == false) {
            //echo "Invalid email!";
            header("location: ../signup.php?error=invalidemail");
            exit();
        }

        if($this->pwdMatch() == false) {
            //echo "Passwords dont match!";
            header("location: ../signup.php?error=passwordsdontmatch");
            exit();
        }

        if($this->usernameTakenCheck() == false) {
            //echo "Username or Email taken";
            header("location: ../signup.php?error=useroremailtaken");
            exit();
        }

        $this->setUser($this->username, $this->user_email, $this->pwd, $this->user_type);
    }

    
    private function emptyInput () {
        $result;
        if (empty($this->username) || empty($this->user_email) || empty($this->pwd) || empty($this->pwdRepeat)) 
        {
            $result = false;
        }
        else {
            $result = true;
        }
        return $result;
    }

    
    private function invalidUsername () {
        $result;
        if (!preg_match("/^[a-zA-Z0-9]/", $this->username)) 
        {
            $result = false;
        }
        else {
            $result = true;
        }
        return $result;
    }


    private function invalidEmail () {
        $result;
        if (!filter_var($this->user_email, FILTER_VALIDATE_EMAIL)) 
        {
            $result = false;
        }
        else {
            $result = true;
        }
        return $result;
    }


    private function pwdMatch () {
        $result;
        if ($this->pwd !== $this->pwdRepeat)
        {
            $result = false;
        }
        else {
            $result = true;
        }
        return $result;
    }


    private function usernameTakenCheck () {
        $result;
        if (!$this->checkUser($this->username, $this->user_email))
        {
            $result = false;
        }
        else {
            $result = true;
        }
        return $result;
    }


    public function fetchuser_id($username) {
        $user_id = $this->getuser_id($username);
        return $user_id[0]["user_id"];
    }

    private function getLastuser_id() {
        $stmt = $this->connect()->prepare("SELECT user_id FROM users WHERE username = ? AND user_email = ?;");
        $stmt->execute([$this->username, $this->user_email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['user_id'];
    }

    private function setProfileInfo($user_id, $username, $user_email) {
        $stmt = $this->connect()->prepare('INSERT INTO profiles (user_id, username, user_email, full_name, profession, firm, experience_years, phone_number, working_hours_start, working_hours_end, physical_address, profile_about) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);');

        if (!$stmt->execute([$user_id, $username, $user_email, 'Enter your full name', 'Tell clients your profession', 'Which firm or company are you affiliated with?', '1900', 'Enter a phone number', 'When are you available to meet with clients?', 'Where can clients find you', 'Tell potential clients about yourself'])) {
            $stmt = null;
            header("location:../profile.php?error=setprofileinfofailed");
            exit();
        }

        $stmt = null;
    }

    public function saveSpecializations($specializations) {
    // Fetch user ID
    $user_id = $this->fetchuser_id($this->username);

    // Check if user is a practitioner and has selected specializations
    if ($this->user_type === 'legal_practitioner' && !empty($specializations)) {
        // Prepare and execute SQL statement to save specializations
        $stmt = $this->connect()->prepare("INSERT INTO practitioner_specializations (user_id, practitioner_id, specialization) VALUES (?, ?, ?)");

        foreach ($specializations as $specialization) {
            $stmt->execute([$user_id, $specialization]);
        }
    }
}


}