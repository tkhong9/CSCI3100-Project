<?php
session_start();

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['selectedID']) ) {
	// Could not get the data that should have been sent.
	unset($_SESSION['searchID']);
  header('Location: profile.php');
}

if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}


$_SESSION['searchID'] = $_POST['selectedID'];
header('Location: profile.php');

?> 