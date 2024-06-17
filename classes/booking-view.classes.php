<?php
require_once "booking.classes.php";

class BookingView extends Booking {

    public function getClientBookings($user_id, $limit, $offset) {
        return parent::getClientBookings($user_id, $limit, $offset); // Call parent method correctly
    }

    public function countClientBookings($user_id) {
        return parent::countClientBookings($user_id); // Call parent method correctly
    }

    public function getPractitionerBookings($user_id, $limit, $offset) {
        return parent::getPractitionerBookings($user_id, $limit, $offset); // Call parent method correctly
    }

    public function countPractitionerBookings($user_id) {
        return parent::countPractitionerBookings($user_id); // Call parent method correctly
    }
}

?>

