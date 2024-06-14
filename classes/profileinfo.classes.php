<?php
error_reporting(E_ALL);
require_once "dbh.classes.php";

class ProfileInfo extends Dbh {

    
    // Fetch profile information for all users
    protected function getAllProfiles() {
        $stmt = $this->connect()->prepare('SELECT * FROM profiles');
        if (!$stmt) {
            exit("Failed to prepare SQL statement.");
        }

        if (!$stmt->execute()) {
            exit("Failed to execute SQL statement.");
        }

        if ($stmt->rowCount() == 0) {
            return null;
        }

        $profilesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;
        return $profilesData;
    }
    
    // Fetch profile information for a given user_id
    public function fetchProfileInfo($user_id) {
        return $this->getProfileInfo($user_id);
    }

    // Protected method to get profile information from the database
    protected function getProfileInfo($user_id) {
        // Prepare the SQL statement
        $stmt = $this->connect()->prepare('SELECT * FROM profiles WHERE user_id = ?');

        // Check if the SQL statement was prepared successfully
        if (!$stmt) {
            // Handle the case where the SQL statement preparation failed
            exit("Failed to prepare SQL statement.");
        }

        // Execute the SQL statement with the provided $user_id
        $success = $stmt->execute([$user_id]);

        // Check if the SQL statement execution was successful
        if (!$success) {
            // Handle the case where the SQL statement execution failed
            exit("Failed to execute SQL statement.");
        }

        // Check if any rows were returned by the query
        if ($stmt->rowCount() == 0) {
            // Return null if no profile found
            return null;
        }

        // Fetch all rows returned by the query into an associative array
        $profileData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Close the statement to release resources
        $stmt = null;

        // Return the fetched profile data
        return $profileData;
    }

    // Protected method to update existing profile information in the database
    protected function setNewProfileInfo($user_id, $full_name, $profession, $firm, $specializations, $experience_years, $phone_number, $working_hours_start, $working_hours_end, $physical_address, $profile_about) {
        // Prepare the SQL statement
        $stmt = $this->connect()->prepare('UPDATE profiles SET full_name = ?, profession = ?, firm = ?, specializations = ?, experience_years = ?, phone_number = ?, working_hours_start = ?, $working_hours_end = ?, physical_address = ?, profile_about = ? WHERE user_id = ?;');

        // Convert the specializations array to a JSON string
        $specializationsJson = json_encode($specializations);

        // Execute the SQL statement with the provided data
        if (!$stmt->execute(array($full_name, $profession, $firm, $specializationsJson, $experience_years, $phone_number, $working_hours_start, $working_hours_end, $physical_address, $profile_about, $user_id))) {
            // Handle the case where the SQL statement execution failed
            $stmt = null;
            header("location: /MY WAKILI/profilesettings.php?error=updateprofilefailed");
            exit();
        }

        // Close the statement to release resources
        $stmt = null;
    }

    // Protected method to insert new profile information into the database
    protected function setProfileInfo($user_id, $username, $user_email, $full_name, $profession, $firm, $specializations, $experience_years, $phone_number, $working_hours_start, $working_hours_end, $physical_address, $profile_about) {
        // Prepare the SQL statement
        $stmt = $this->connect()->prepare('INSERT INTO profiles (user_id, username, user_email, full_name, profession, firm, specializations, experience_years, phone_number, working_hours_start, working_hours_end, physical_address, profile_about) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);');

        // Convert the specializations array to a JSON string
        $specializationsJson = json_encode($specializations);

        // Execute the SQL statement with the provided data
        if (!$stmt->execute(array($user_id, $username, $user_email, $full_name, $profession, $firm, $specializationsJson, $experience_years, $phone_number, $working_hours_start, $working_hours_end, $physical_address, $profile_about))) {
            // Handle the case where the SQL statement execution failed
            $stmt = null;
            header("location:/MY WAKILI/profilesettings.php?error=setprofileinfofailed");
            exit();
        }

        // Close the statement to release resources
        $stmt = null;
    }
}
