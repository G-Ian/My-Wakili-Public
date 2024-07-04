<?php
require_once 'includes/config_session.inc.php';
require_once 'classes/dbh.classes.php';

// Ensure session is active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id'])) {
    header("location: login.php"); // Redirect to login page if not authenticated
    exit();
}

// Define your admin password (replace with your actual secure password)
$adminPassword = '123';

// Initialize admin access session variable if not set
if (!isset($_SESSION['admin_access_granted'])) {
    $_SESSION['admin_access_granted'] = false;
}

// Process password submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["password"])) {
    $enteredPassword = htmlspecialchars($_POST["password"], ENT_QUOTES, 'UTF-8');

    if ($enteredPassword === $adminPassword) {
        // Password correct, allow access to promotion functionality
        $_SESSION['admin_access_granted'] = true;
    } else {
        // Password incorrect, show error message
        $error = "Invalid password. Please try again.";
    }
}

// If admin access is granted, process promotion request
if ($_SESSION['admin_access_granted'] && isset($_POST["username"])) {
    $username = htmlspecialchars($_POST["username"], ENT_QUOTES, 'UTF-8');

    $dbh = new Dbh();
    $pdo = $dbh->getPdo(); // Use getPdo() to get the PDO object

    try {
        // Perform the database operation using $pdo
        $stmt = $pdo->prepare('UPDATE users SET is_admin = 1 WHERE username = ?');
        $stmt->execute([$username]);
        echo "User promoted to admin.";
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error promoting user: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Promote User to Admin</title>
</head>
<body>
    <?php if (!$_SESSION['admin_access_granted']): ?>
        <h2>Admin Password Required</h2>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="password">Enter Admin Password:</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Submit</button>
        </form>
    <?php else: ?>
        <h2>Promote User to Admin</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <button type="submit">Promote to Admin</button>
        </form>
        <br>
        <a href="admin-panel.php">Back to Admin Panel</a>
    <?php endif; ?>
</body>
</html>
