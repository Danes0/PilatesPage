<?php
// Start the session
session_start();

// Delete all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect user to login page
header("location: login.php");
exit;
?>
