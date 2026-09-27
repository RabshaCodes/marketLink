<?php
session_start();

// Clear all session variables.
$_SESSION = array();

// Delete the session cookie so the browser stops sending the old session id.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destroy the session data on the server.
session_destroy();

header("Location: index.php");
exit;
?>
