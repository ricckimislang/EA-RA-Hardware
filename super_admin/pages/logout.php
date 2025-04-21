<?php
// Start the session if not already started
session_start();

// Clear all session data
$_SESSION = array();

// Destroy the session cookie if it exists
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Finally, destroy the session
session_unset();
session_destroy();

// Redirect to login page
header('Location:../../index.php');
exit();
