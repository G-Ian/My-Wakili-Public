<?php
error_reporting(E_ALL);
require_once "profileinfo.classes.php"; // Include the file containing the ProfileInfo class definition

class ProfileInfoContr extends ProfileInfo {

    private $user_id;
    private $username;
    private $user_type;

    public function __construct($user_id, $username, $user_type) {
        $this->user_id = $user_id;
        $this->username = $username;
        $this->user_type = $user_type;
    }

    // Adding default profile Infomation to database and adding username,email & type
    public function defaultProfileInfo($user_id, $username, $user_email) {
        $full_name = "Enter your full name";
        $profession = "Tell clients your profession";
        $firm = "Which firm or company are you affiliated with?";
        $specializations = ["General practice"];
        $experience_years = "1999";
        $phone_number = "Enter a phone number where clients can reach you";
        $working_hours_start = "00:00:00";
        $working_hours_end = "00:00:00";
        $physical_address = "Where can clients find you";
        $profile_about = "Tell potential clients about yourself";

        // Call setProfileInfo with appropriate arguments
        if ($this->user_type === 'legal_practitioner') {
            // Set profile info
            $this->setProfileInfo($this->user_id, $username, $user_email, $full_name, $profession, $firm, $specializations, $experience_years, $phone_number, $working_hours_start, $working_hours_end, $physical_address, $profile_about);

            // Retrieve practitioner_id
            $practitionerId = $this->getPractitionerId($user_id);

        }
    }

    // Method to get practitioner_id from practitioners table
    private function getPractitionerId($user_id) {
        $stmt = $this->connect()->prepare('SELECT practitioner_id FROM practitioners WHERE user_id = ?');
        $stmt->execute([$user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['practitioner_id'];
    }


    public function updateProfileInfo($full_name, $profession, $firm, $specializations, $experience_years, $phone_number, $working_hours_start, $working_hours_end, $physical_address, $profile_about) {
        // Error Handlers
        $emptyFields = [];
        if (empty($full_name)) {
            $emptyFields[] = 'full_name';
        }
        if (empty($profession)) {
            $emptyFields[] = 'profession';
        }
        if (empty($firm)) {
            $emptyFields[] = 'firm';
        }
        if (empty($specializations)) {
            $emptyFields[] = 'specializations';
        }
        if (empty($experience_years)) {
            $emptyFields[] = 'experience_years';
        }
        if (empty($phone_number)) {
            $emptyFields[] = 'phone_number';
        }
        if (empty($working_hours_start)) {
            $emptyFields[] = 'working_hours_start';
        }
        if (empty($working_hours_end)) {
            $emptyFields[] = 'working_hours_end';
        }
        if (empty($physical_address)) {
            $emptyFields[] = 'physical_address';
        }
        if (empty($profile_about)) {
            $emptyFields[] = 'profile_about';
        }
    
        if (!empty($emptyFields)) {
            $errorString = implode(',', $emptyFields);
            header("location: ../profilesettings.php?error=emptyprofileinput&fields=$errorString");
            exit();
        }
    
        // Update profile Info
        $this->setNewProfileInfo($this->user_id, $full_name, $profession, $firm, $specializations, $experience_years, $phone_number, $working_hours_start, $working_hours_end, $physical_address, $profile_about);
    }

    private function emptyInputCheck($full_name, $profession, $firm, $specializations, $experience_years, $phone_number, $working_hours_start, $working_hours_end, $physical_address, $profile_about) {
        if (empty($full_name)) {
            echo "Full Name is empty<br>";
            return true;
        }
        if (empty($profession)) {
            echo "Profession is empty<br>";
            return true;
        }
        if (empty($firm)) {
            echo "Firm is empty<br>";
            return true;
        }
        if (empty($specialization)) {
            echo "Specialization is empty<br>";
            return true;
        }
        if (empty($experience_years)) {
            echo "Experience Years is empty<br>";
            return true;
        }
        if (empty($phone_number)) {
            echo "Phone Number is empty<br>";
            return true;
        }
        if (empty($working_hours_start)) {
            echo "Starting Hours is empty<br>";
            return true;
        }
        if (empty($working_hours_end)) {
            echo "Endin Hours is empty<br>";
            return true;
        }
        if (empty($physical_address)) {
            echo "Physical Address is empty<br>";
            return true;
        }
        if (empty($profile_about)) {
            echo "Profile About is empty<br>";
            return true;
        }
        return false;
    }

    // Method to set new profile information
    protected function setNewProfileInfo($user_id, $full_name, $profession, $firm, $specializations, $experience_years, $phone_number, $working_hours_start, $working_hours_end, $physical_address, $profile_about) {
        try {
            $stmt = $this->connect()->prepare('UPDATE profiles SET full_name = ?, profession = ?, firm = ?, specializations = ?, experience_years = ?, phone_number = ?, working_hours_start = ?, working_hours_end = ?, physical_address = ?, profile_about = ? WHERE user_id = ?;');
            
            if(!$stmt->execute(array($full_name, $profession, $firm, $specializations, $experience_years, $phone_number, $working_hours_start, $working_hours_end, $physical_address, $profile_about, $user_id))) {
                // If the execution fails, redirect to an error page
                header("location: /MY WAKILI/profilesettings.php?error=updateprofilefailed");
                exit();
            }
            
            // Close the statement
            $stmt = null;
            
            // Redirect to a success page or the profile settings page without an error
            header("location: /MY WAKILI/pract-dashboard.php?success=profileupdated");
            exit();
            
        } catch (PDOException $e) {
            // Handle any exceptions that occur during the database operations
            header("location: /MY WAKILI/profilesettings.php?error=dberror&message=" . $e->getMessage());
            exit();
        }
    }
    




    // public function updateSpecializations($user_id, $specializations) {
    //     // First, delete existing specializations for the user
    //     $this->deleteSpecializations($user_id);

    //     // Then, insert updated specializations
    //     foreach ($specializations as $specialization) {
    //         $this->insertSpecialization($user_id, $specialization);
    //     }
    // }

    // // Method to delete existing specializations for a user
    // private function deleteSpecializations($user_id) {
    //     $stmt = $this->connect()->prepare('DELETE FROM practitioner_specializations WHERE user_id = ?');

    //     if (!$stmt->execute([$user_id])) {
    //         // Handle deletion failure
    //         $stmt = null;
    //         header("location:../profilesettings.php?error=deletespecializationsfailed");
    //         exit();
    //     }

    //     $stmt = null;
    // }

    // // Method to insert a specialization for a user
    // private function insertSpecialization($user_id, $specialization) {
    //     $stmt = $this->connect()->prepare('INSERT INTO practitioner_specializations (user_id, specialization) VALUES (?, ?);');

    //     if (!$stmt->execute([$user_id, $specialization])) {
    //         // Handle insertion failure
    //         $stmt = null;
    //         header("location:../profilesettings.php?error=insertspecializationfailed");
    //         exit();
    //     }

    //     $stmt = null;
    // }

}






