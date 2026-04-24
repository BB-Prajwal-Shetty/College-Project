<?php
session_start();

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Remove remember me cookie if it exists
if (isset($_COOKIE['remember_admin'])) {
    setcookie('remember_admin', '', time() - 3600, '/');
}

// Start a new session for the logout message
session_start();
$_SESSION['success_message'] = "You have been successfully logged out from admin panel.";

// Redirect to login page
header("Location: login.php");
exit();
?>
