<?php
// This part is to create account and send the verification e-mail to user.
require __DIR__.'/lib/db.inc.php';
// We need to use sessions, so you should always start sessions using the below code.
session_start();

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

global $db;
$db = unisched_DB();

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['username'], $_POST['password']) ) {
    // Could not get the data that should have been sent.
    exit('Please fill both the username and password fields!');
}

$token = md5($_POST['email']).rand(10,9999);
$link = "<a href='http://ec2-54-209-201-97.compute-1.amazonaws.com:8081/verify_email_for_create_account.php?key=".$_POST['email']."&token=".$token."'>Click here to verify your account</a>";

// Reference: https://github.com/PHPMailer/PHPMailer
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'unisched000@gmail.com';                     //SMTP username
    $mail->Password   = 'csci3100project.';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                  

    //Recipients
    $mail->setFrom('unisched000@gmail.com', 'Unisched');
    $mail->addAddress($_POST['email']);     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = '[Unisched] Verification email for creating the account';
    $mail->Body    = ''.$link.'';

    $mail->send();
} catch (Exception $e) {
    $message = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

$stmt = $db->prepare('INSERT INTO accounts (username, password, email, token) VALUES(?, ?, ?, ?)');

$stmt->bind_param("ssss",$_POST['username'],$hash,$_POST['email'], $token);
$result = $stmt->execute();

$stmt->close();

if (!$result) {
      $message = "Query failed.{$stmt->error}";
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
        $message = "Query failed.{$stmt3->error}";
    } else {
        $message = "A verification email is sent to your email address!";
    }
}

echo "<SCRIPT> 
alert('$message')
window.location.replace('index.html');
</SCRIPT>";

?>

?>