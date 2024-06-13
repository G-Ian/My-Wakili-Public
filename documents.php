
<?php
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: /login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreeDownloads</title>
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/documents.css">

</head>
<body>


<div>
    <table >
        <h1>FREE DOWNLOADS</h1>
        <h2>Find a wide array of legal documents for free donwload</h2>

        <th>Document Name</th>
        <th>Document Type(Class/Where it can be used)</th>
        <th>Document downloadlink</th>
        <th>Description</th>
    </table>
</div>
    
</body>
</html>