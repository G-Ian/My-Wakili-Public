<?php

class Practitioner {
    // Method to retrieve all practitioners from the database
    public function getAllPractitioners() {
        // Create a new instance of the Database class
        $db = new Database();
        // Get the database connection
        $conn = $db->getConnection();

        // Prepare SQL statement to select all practitioners
        $stmt = $conn->prepare("SELECT * FROM practitioners");
        // Execute the SQL statement
        $stmt->execute();
        // Fetch all practitioners as associative array
        $practitioners = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return the array of practitioners
        return $practitioners;
    }
}
?>
