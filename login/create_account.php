<?php
session_start();
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'login_account';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
    // If there is an error with the connection, stop the script and display the error.
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['username'], $_POST['password']) ) {
    // Could not get the data that should have been sent.
    exit('Please fill both the username and password fields!');
}

$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

$stmt = $con->prepare('INSERT INTO accounts (username, password, email) VALUES(?, ?, ?)');

$stmt->bind_param("sss",$_POST['username'],$hash,$_POST['email']);
$result = $stmt->execute();

$stmt->close();

if (!$result) {
    echo 'Query failed. '.$stmt->error;
} else {
    exit ('Account created, you may go back to login try it');
}

?>