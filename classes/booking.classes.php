<?php
require_once "dbh.classes.php";

class Booking extends Dbh {

    protected function getClientBookings($user_id, $limit, $offset) {
        $sql = "SELECT b.booking_id, b.service_type, b.comments, b.date, b.time, b.updated_at, 
                       p.full_name AS practitioner_name, 
                       p.user_id AS practitioner_user_id
                FROM bookings b
                JOIN practitioners pr ON b.practitioner_id = pr.practitioner_id
                JOIN profiles p ON pr.user_id = p.user_id
                WHERE b.user_id = ?
                ORDER BY b.appointment_time DESC
                LIMIT ? OFFSET ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function countClientBookings($user_id) {
        $sql = "SELECT COUNT(*) FROM bookings WHERE user_id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn();
    }

    protected function getPractitionerBookings($practitioner_id, $limit, $offset) {
        $sql = "SELECT b.booking_id, b.client_name, b.client_email , b.client_phone, b.service_type, b.comments, b.date, b.time, b.updated_at, 
                       p.full_name AS practitioner_name, 
                       p.user_id AS practitioner_user_id
                FROM bookings b
                JOIN practitioners pr ON b.practitioner_id = pr.practitioner_id
                JOIN profiles p ON pr.user_id = p.user_id
                WHERE pr.user_id = ?
                ORDER BY b.date ASC, b.time ASC
                LIMIT ? OFFSET ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindValue(1, $practitioner_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function countPractitionerBookings($practitioner_id) {
        $sql = "SELECT COUNT(*) FROM bookings b
                JOIN practitioners pr ON b.practitioner_id = pr.practitioner_id
                WHERE pr.user_id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$practitioner_id]);
        return $stmt->fetchColumn();
    }
}
?>
