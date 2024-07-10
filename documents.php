<?php
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION["user_id"]) && !isset($_SESSION["user_type"])) {
    header("Location: /login.php");
    exit();
}



require_once 'classes/dbh.classes.php';

// Fetch documents from the database based on the search keyword
function getDocuments($keyword = '') {
    $dbh = new Dbh();
    $pdo = $dbh->getPdo();

    $sql = 'SELECT * FROM documents';
    if ($keyword) {
        $sql .= ' WHERE document_name LIKE :keyword';
    }
    
    $stmt = $pdo->prepare($sql);

    if ($keyword) {
        $stmt->bindValue(':keyword', '%' . $keyword . '%');
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get the search keyword if it exists
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$documents = getDocuments($keyword);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreeDownloads</title>
    <link rel="stylesheet" type="text/css" href="css/appointments.css">
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/header.footer.css">
    <link rel="stylesheet" type="text/css" href="css/documents.css">

</head>
<body>

<?php include 'includes/client_header.inc.php'; ?>


<div>
    <br><br>
        <p class="medium-text">Free Downloads</p>
        <br>
    
    <!-- Search Form
    <div class="search-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="search" placeholder="Search documents by keyword">
            <button type="submit" class="">Search</button>
        </form> -->
    </div>
    
    

    <p class="small-text">Find a wide array of legal documents for free download</p>


    <table border="1">
        <tr>
            <th>Document ID</th>
            <th>Document Name</th>
            <th>Download</th>
        </tr>
        <?php if (count($documents) > 0): ?>
            <?php foreach ($documents as $document): ?>
                <tr>
                    <td><?php echo $document['document_id']; ?></td>
                    <td><?php echo $document['document_name']; ?></td>
                    <td>
                        <form action="classes/download.classes.php" method="GET">
                            <input type="hidden" name="document_id" value="<?php echo $document['document_id']; ?>">
                            <button type="submit">Download</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No documents found.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

</body>
</html>
