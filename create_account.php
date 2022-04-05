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
}

$stmt2 = $db->prepare("SELECT id FROM accounts WHERE username = ?");
$stmt2->bind_param('s', $_POST['username']);
$stmt2->execute();
$resultSet2 = $stmt2->get_result();
$res2 = $resultSet2->fetch_all();

foreach($res2 as $row){
    $user_id = $row[0];
    $zero = 0;
    $stmt3 = $db->prepare('INSERT INTO mycourses (user_id, shared, courseID1, courseID2, courseID3, courseID4, courseID5, courseID6) VALUES(?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt3->bind_param("iiiiiiii", $user_id, $zero, $zero, $zero, $zero, $zero, $zero, $zero);
    $result3 = $stmt3->execute();
    $stmt3->close();
    if (!$result3) {
        echo 'Query failed. '.$stmt3->error;
    } else {
        exit ('Account created, you may go back to login try it');
    }
}

?>