<?php
include "./includes/header.php";

// Unset and destroy the session
session_unset();
session_destroy();
// Restart the session
session_start();

// Set success message
$_SESSION['success_message'] = "You have successfully logged out.";

// Redirect to sign-in.php
header("Location: sign-in.php");
ob_flush();
exit();
?>

<?php
include "./includes/footer.php";
?>
