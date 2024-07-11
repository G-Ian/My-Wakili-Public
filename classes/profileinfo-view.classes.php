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

    public function fetchDocumentsByKeyword($keyword) {
        $stmt = $this->connect()->prepare("SELECT * FROM documents WHERE document_name LIKE ? OR document_type LIKE ? OR document_data LIKE ?");
        $search = "%$keyword%";
        $stmt->execute([$search, $search, $search]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }
    
    public function fetchAllProfiles() {
        return $this->getAllProfiles();
    }

    public function fetchFullname($practitioner_id) {
        $profileInfo = $this->getProfileInfo($practitioner_id);

        if ($profileInfo !== null) {
            echo htmlspecialchars($profileInfo[0]["full_name"], ENT_QUOTES, 'UTF-8');
        } else {
            echo "N/A";
        }
    }

    public function fetchEmail($practitioner_id) {
        $profileInfo = $this->getProfileInfo($practitioner_id);

        if ($profileInfo !== null) {
            echo htmlspecialchars($profileInfo[0]["user_email"], ENT_QUOTES, 'UTF-8');
        } else {
            echo "N/A";
        }
    }

    public function fetchPhoneNumber($practitioner_id) {
        $profileInfo = $this->getProfileInfo($practitioner_id);

        if ($profileInfo !== null) {
            echo htmlspecialchars($profileInfo[0]["phone_number"], ENT_QUOTES, 'UTF-8');
        } else {
            echo "N/A";
        }
    }

    public function fetchProfession($practitioner_id) {
        $profileInfo = $this->getProfileInfo($practitioner_id);

        if ($profileInfo !== null) {
            echo htmlspecialchars($profileInfo[0]["profession"], ENT_QUOTES, 'UTF-8');
        } else {
            echo "N/A";
        }
    }

    public function fetchFirm($practitioner_id) {
        $profileInfo = $this->getProfileInfo($practitioner_id);

        if ($profileInfo !== null) {
            echo htmlspecialchars($profileInfo[0]["firm"], ENT_QUOTES, 'UTF-8');
        } else {
            echo "N/A";
        }
    }

    public function fetchExperience($practitioner_id) {
        $profileInfo = $this->getProfileInfo($practitioner_id);

        if ($profileInfo !== null) {
            echo htmlspecialchars($profileInfo[0]["experience_years"], ENT_QUOTES, 'UTF-8');
        } else {
            echo "N/A";
        }
    }

    public function fetchSpecializations($practitioner_id) {
        $profileInfo = $this->getProfileInfo($practitioner_id);

        if ($profileInfo !== null) {
            echo htmlspecialchars($profileInfo[0]["specializations"], ENT_QUOTES, 'UTF-8');
        } else {
            echo "N/A";
        }
    }

    public function fetchAddress($practitioner_id) {
        $profileInfo = $this->getProfileInfo($practitioner_id);

        if ($profileInfo !== null) {
            echo htmlspecialchars($profileInfo[0]["physical_address"], ENT_QUOTES, 'UTF-8');
        } else {
            echo "N/A";
        }
    }

    public function fetchBioText($practitioner_id) {
        $profileInfo = $this->getProfileInfo($practitioner_id);

        if ($profileInfo !== null) {
            echo htmlspecialchars($profileInfo[0]["profile_about"], ENT_QUOTES, 'UTF-8');
        } else {
            echo "N/A";
        }
    }

    public function fetchStartHours($practitioner_id) {
        $profileInfo = $this->getProfileInfo($practitioner_id);

        if ($profileInfo !== null) {
            return htmlspecialchars($profileInfo[0]["working_hours_start"], ENT_QUOTES, 'UTF-8');
        } else {
            return "N/A";
        }
    }

    public function fetchEndHours($practitioner_id) {
        $profileInfo = $this->getProfileInfo($practitioner_id);

        if ($profileInfo !== null) {
            return htmlspecialchars($profileInfo[0]["working_hours_end"], ENT_QUOTES, 'UTF-8');
        } else {
            return "N/A";
        }
    }
}
?>
