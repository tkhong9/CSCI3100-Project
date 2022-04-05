<?php
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

// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'unisched';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// We don't have the password or email info stored in sessions so instead we can get the results from the database.
$stmt = $con->prepare('SELECT password, email, f_path FROM accounts WHERE id = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email, $f_path);
$stmt->fetch();
$stmt->close();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Profile Page</title>
    <link href="css/home.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link class="jsbin" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta charset=utf-8 />
</head>
<style>
  article, aside, figure, footer, header, hgroup, 
  menu, nav, section { display: block; }
</style>
<body class="loggedin">
<nav class="navtop">
    <div>
        <h1>Unisched</h1>
        <a href="home.php">Home</a>
        <a href="#" onclick="showPage('timetable.php')">Timetable</a>
        <a href="mycourselist.php" onclick="showPage('mycourselist.php')">My Course List</a>
        <a href="courselist.php" onclick="showPage('courselist.php')">Course List</a>
        <a href="profile.php" onclick="showPage('profile.php')"><i class="fas fa-user-circle"></i>Profile</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
    </div>
</nav>
<div class="content">
    <h2>Reset Username Page</h2>
    <div>
        <form action="reset_password.php" method="post">
        Please enter your original password:<br>
        <input type="text" name="original_password" id="original_password" required><br><br>
        Please enter your new password:<br>
        <input type="text" name="new_password" id="new_password" required><br><br>
        <input type="submit" name="reset_password" value="Submit">
        </form>

        <?php	
                // Reset the username 
                if(isset($_POST['reset_password'])) {
                   
                    $id = $_SESSION['id'];
                    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

                    if(password_verify($_POST['original_password'], $password)){
                        if (password_verify($_POST['new_password'], $password)){
                            $message = "Please enter a new password!";
                         }else {

                            $stmt = "UPDATE accounts SET buffer_password = '$new_password' WHERE id = $id";
                            if($con->query($stmt) === TRUE){
                                 $message = "A verification email is sent to your email address!";	
    
                                $token = md5($email).rand(10,9999);
                                $stmt = "UPDATE accounts SET token = '$token' WHERE id = $id";
                                $con->query($stmt); 
                                $link = "<a href='http://ec2-54-209-201-97.compute-1.amazonaws.com:8081/verify_email.php?key=".$email."&token=".$token."'>Click here to verify your password</a>";
                                $mail = new PHPMailer(true);
    
                                try {
                                    //Server settings
                                    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                                    $mail->isSMTP();                                            //Send using SMTP
                                    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                                    $mail->Username   = 'unisched000@gmail.com';                     //SMTP username
                                    $mail->Password   = 'csci3100project.';                               //SMTP password
                                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                                    $mail->Port       = 465;                                  
                                
                                    //Recipients
                                    $mail->setFrom('unisched000@gmail.com', 'Unisched');
                                    $mail->addAddress($email);     //Add a recipient
                                
                                    //Content
                                    $mail->isHTML(true);                                  //Set email format to HTML
                                    $mail->Subject = '[Unisched] Verification email for password';
                                    $mail->Body    = ''.$link.'';
                                
                                    $mail->send();
                                } catch (Exception $e) {
                                    $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                }
                            }
                        }
                    }
                    else{
                        $message = "Your original password is wrong!";	
                    }

                    echo "<SCRIPT> 
                    alert('$message')
                    window.location.replace('profile.php');
                    </SCRIPT>";
                } 

        ?> 
    </div>
</div>

</body>

</html>
