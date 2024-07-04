<?php

require_once 'dbh.classes.php'; // Adjust as per your file structure

class Admin extends Dbh {

    // Method to retrieve all users
    public function getUsers() {
        $stmt = $this->connect()->prepare('SELECT * FROM users');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Method to remove a user
    public function removeUser($user_id) {
        try {
            $stmt = $this->connect()->prepare("DELETE FROM users WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            return true; // Return true on success
        } catch (PDOException $e) {
            // Handle errors here if needed
            return false; // Return false on failure
        }
    }

    // Method to retrieve all appointments
    public function getAppointments() {
        $stmt = $this->connect()->prepare('
            SELECT b.booking_id, b.user_id, b.client_name, b.practitioner_id, p.full_name AS practitioner_name, b.date, b.time
            FROM bookings b
            INNER JOIN practitioners p ON b.practitioner_id = p.practitioner_id
        ');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Method to remove an appointment
    public function removeBooking($booking_id) {
        try {
            $stmt = $this->connect()->prepare("DELETE FROM bookings WHERE booking_id = :booking_id");
            $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
            $stmt->execute();
            return true; // Return true on success
        } catch (PDOException $e) {
            // Handle errors here if needed
            return false; // Return false on failure
        }
    }
}
?>
