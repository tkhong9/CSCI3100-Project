<?php
require __DIR__.'/lib/db.inc.php';
// We need to use sessions, so you should always start sessions using the below code.
session_start();

global $db;
$db = unisched_DB();

// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}

// We don't have the password or email info stored in sessions so instead we can get the results from the database.
$stmt = $db->prepare('SELECT password, email, f_path FROM accounts WHERE id = ?');
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
        <form action="reset_username.php" method="post">
        Please enter your new username:<br>
        <input type="text" name="new_username" id="new_username" required><br><br>
        <input type="submit" name="reset_username" value="Submit">
        </form>

        <?php	
                // Reset the username 
                if(isset($_POST['reset_username'])) {
                   
                    $id = $_SESSION['id'];
                    $username = $_POST['new_username'];

                    $stmt = "UPDATE accounts SET username = '$username' WHERE id = $id";
                    if($db->query($stmt) === TRUE){
                         $message = "Your username is changed!";	
                           
                         if ($_POST['new_username'] === $_SESSION['name']){
                            $message = "Please enter a new username!";
                         }

                         $_SESSION['name'] = $username;  
                    }
                    else{
                        $message = "ERROR";		
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
