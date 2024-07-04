<?php
require_once '../includes/config_session.inc.php';
require_once 'dbh.classes.php';

if (isset($_GET['document_id'])) {
    try {
        $document_id = $_GET['document_id'];

        $dbh = new Dbh();
        $pdo = $dbh->getPdo();

        $stmt = $pdo->prepare('SELECT * FROM documents WHERE document_id = ?');
        $stmt->execute([$document_id]);
        $document = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($document) {
            // Set headers for force-download
            header('Content-Type: ' . $document['document_type']);
            header('Content-Disposition: attachment; filename="' . $document['document_name'] . '"');
            header('Content-Length: ' . $document['document_size']);
            header('Pragma: public');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Expires: 0');
            echo $document['document_data'];
            exit;
        } else {
            echo "Document not found.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid document ID.";
}
?>
