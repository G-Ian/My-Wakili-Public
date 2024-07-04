<?php

ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_set_cookie_params([
    'lifetime' => 1800,
    'domain' => 'localhost',
    'path' => '/',
    'secure' => false, // For local development, set to true for production
    'httponly' => true
]);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_SESSION["user_id"])) { 
    if (!isset($_SESSION["last_regeneration"])) {
        regenerate_session_id_loggedin();
    } else {
        $interval = 60 * 30;
        if (time() - $_SESSION["last_regeneration"] >= $interval) {
            regenerate_session_id_loggedin();
        }
    }
} else {
    if (!isset($_SESSION["last_regeneration"])) {
        regenerate_session_id();
    } else {
        $interval = 60 * 30;
        if (time() - $_SESSION["last_regeneration"] >= $interval) {
            regenerate_session_id();
        }
    }
}

function regenerate_session_id_loggedin() 
{
    session_regenerate_id(true);

    $user_id = $_SESSION["user_id"];
    $newSessionId = session_create_id();
    $sessionId = $newSessionId . "_" . $user_id;
    session_id($sessionId);

    $_SESSION["last_regeneration"] = time();
}

function regenerate_session_id() 
{
    session_regenerate_id(true);
    $_SESSION["last_regeneration"] = time();
}
?>
