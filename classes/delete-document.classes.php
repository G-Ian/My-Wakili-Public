<?php
require_once '../includes/config_session.inc.php';
require_once 'dbh.classes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['document_id'])) {
    try {
        $document_id = $_POST['document_id'];

        $dbh = new Dbh();
        $pdo = $dbh->getPdo();

        // Delete document
        $stmt = $pdo->prepare('DELETE FROM documents WHERE document_id = ?');
        $stmt->execute([$document_id]);

        // Redirect back to admin panel or any other appropriate page
        header("Location: ../admin-panel.php");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
