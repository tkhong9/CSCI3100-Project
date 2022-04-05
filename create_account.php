<?php
require __DIR__.'/lib/db.inc.php';
// We need to use sessions, so you should always start sessions using the below code.
session_start();

global $db;
$db = unisched_DB();

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['username'], $_POST['password']) ) {
    // Could not get the data that should have been sent.
    exit('Please fill both the username and password fields!');
}

$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

$stmt = $db->prepare('INSERT INTO accounts (username, password, email) VALUES(?, ?, ?)');

$stmt->bind_param("sss",$_POST['username'],$hash,$_POST['email']);
$result = $stmt->execute();

$stmt->close();

if (!$result) {
    echo 'Query failed. '.$stmt->error;
} else {
    exit ('Account created, you may go back to login try it');
}

?>