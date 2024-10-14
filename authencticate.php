<?php


// Check if user is authenticated
if (!isset($_SESSION['auth'])) {
    $_SESSION['message'] = 'Login to continue'; // Set a session message
    header("Location: login.php"); // Redirect to login
    exit; // Don't forget to call exit after header redirection
}
?>