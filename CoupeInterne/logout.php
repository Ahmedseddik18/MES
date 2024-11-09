<?php
// Start the session to access session variables
session_start();

// Unset all of the session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the login page (you can change this to any other page)
header("Location: ../../index.php");
exit();
?>
