<?php
require_once 'includes/config_session.inc.php';
require_once 'classes/dbh.classes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"])) {
    $username = htmlspecialchars($_POST["username"], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST["password"], ENT_QUOTES, 'UTF-8');

    $dbh = new Dbh();
    $pdo = $dbh->connect();
    
    $stmt = $pdo->prepare('SELECT user_pwd FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['user_pwd'])) {
        $stmt = $pdo->prepare('UPDATE users SET is_admin = 1 WHERE username = ?');
        $stmt->execute([$username]);
        echo "User promoted to admin.";
    } else {
        echo "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Promotion</title>
</head>
<body>
    <form action="admin.php" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Promote to Admin</button>
    </form>
</body>
</html>
