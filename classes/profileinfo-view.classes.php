<?php
error_reporting(E_ALL);
require_once "profileinfo.classes.php";


class ProfileInfoView extends ProfileInfo {

    public function fetchProfilesByKeyword($keyword) {
        $stmt = $this->connect()->prepare("SELECT * FROM profiles WHERE full_name LIKE ? OR profession LIKE ? OR firm LIKE ? OR specializations LIKE ? OR physical_address LIKE ?");
        $search = "%$keyword%";
        $stmt->execute([$search, $search, $search, $search, $search]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }
    
    public function fetchAllProfiles() {
        return $this->getAllProfiles();
    }

    public function fetchFullname($user_id) {
        $profileInfo = $this->getProfileInfo($user_id);

        echo $profileInfo[0]["full_name"];
    }

    public function fetchEmail($user_id) {
        $profileInfo = $this->getProfileInfo($user_id);

        echo $profileInfo[0]["user_email"];
    }

    public function fetchPhoneNumber($user_id) {
        $profileInfo = $this->getProfileInfo($user_id);

        echo $profileInfo[0]["phone_number"];
    }

    public function fetchProfession($user_id) {
        $profileInfo = $this->getProfileInfo($user_id);

        echo $profileInfo[0]["profession"];
    }

    public function fetchFirm($user_id) {
        $profileInfo = $this->getProfileInfo($user_id);

        echo $profileInfo[0]["firm"];
    }

    public function fetchExperience($user_id) {
        $profileInfo = $this->getProfileInfo($user_id);

        echo $profileInfo[0]["experience_years"];
    }

    public function fetchStartHoursPrint($user_id) {
        $profileInfo = $this->getProfileInfo($user_id);

        echo $profileInfo[0]["working_hours_start"];
    }

    public function fetchEndHoursPrint($user_id) {
        $profileInfo = $this->getProfileInfo($user_id);

        echo $profileInfo[0]["working_hours_end"];
    }

    public function fetchStartHours($user_id) {
        $profileInfo = $this->getProfileInfo($user_id);
        return $profileInfo[0]["working_hours_start"];
    }
    
    public function fetchEndHours($user_id) {
        $profileInfo = $this->getProfileInfo($user_id);
        return $profileInfo[0]["working_hours_end"];
    }

    public function fetchAddress($user_id) {
        $profileInfo = $this->getProfileInfo($user_id);

        echo $profileInfo[0]["physical_address"];
    }
    
    public function fetchBioText($user_id) {
        $profileInfo = $this->getProfileInfo($user_id);

        echo $profileInfo[0]["profile_about"];
    }


    public function fetchSpecializations($user_id) {
        $profileInfo = $this->getProfileInfo($user_id);
        $specializations = json_decode($profileInfo[0]["specializations"], true); // Decode JSON string into an associative array
    
        // Check if specializations exist and are not empty
        if (!empty($specializations)) {
            // Output each specialization as a list item
            foreach ($specializations as $specialization) {
                echo "<li>$specialization</li>";
            }
        } else {
            echo "No specializations available.";
        }
    }
    
}