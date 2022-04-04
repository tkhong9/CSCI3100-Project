<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
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
    <h2>Profile Page</h2>
    <div>
        <p>Your account details are below:</p>
        <table>
            <tr>
                <td>Profile Pic:</td>
                <form action="profile.php" method="post" enctype="multipart/form-data">
                    <td><label>
                    <?php	
                        if ($f_path == NULL) {
                            $f_path = "/image/uploadImage.jpg";
                        }
                    ?> 
                    
                    <img id="output" src="<?php echo $f_path ?>" width="200" height="200" class="image"/>
                    <br><input name="Filename" type="file" accept="image/*" onchange="document.getElementById('output').src = window.URL.createObjectURL(this.files[0])" style="display:none;">
                    <br><input TYPE="submit" name="upload" value="Submit"/></label></td>
                </form>
                <?php	
                    // Upload the profile pic 
                    if(isset($_POST['upload'])) {
                        // We need to use sessions, so you should always start sessions using the below code.
                        $id = $_SESSION['id'];

                        $fileName = $_FILES['Filename']['name'];

                        $target = "profile pic/";		
                        $fileTarget = $target.$fileName;	
                        $tempFileName = $_FILES["Filename"]["tmp_name"];
                        $result = move_uploaded_file($tempFileName,$fileTarget);

                        $fileCheck = basename($_FILES['Filename']['name']);
                        $fileType = strtolower(substr($fileCheck, strrpos($fileCheck, '.') + 1));        
                        if(!($fileType == "jpg" || $fileType == "png")) {
                            echo '<script>alert("Please upload a jpg/png file!")</script>';	
                        }else{
                            if($result) { 
                                echo '<script>alert("Your profile picture has been successfully uploaded!")</script>';		
                                $query = "UPDATE accounts SET f_path = '$fileTarget' WHERE id = $id";;
                                $con->query($query) or die("Error : ".mysqli_error($con));	
                            }
                            else {		
                                echo '<script>alert("ERROR")</script>';		
                            }
                        }

                        mysqli_close($con);
                        // Refresh the page to show the new profile pic
                        $page = $_SERVER['PHP_SELF'];
                        echo '<meta http-equiv="Refresh" content="0;' . $page . '">';
                    } 
                ?> 
            </tr>
            <tr>
                <td>Username:</td>
                <td><?=$_SESSION['name']?></td>
                <form action="reset_username.php" method="post" enctype="multipart/form-data">
                    <td><input TYPE="submit" name="upload" value="Reset Username"/></td>
                </form>


            </tr>
            <tr>
                <td>Password:</td>
                <td>*******************</td>
                <form action="reset_password.php" method="post" enctype="multipart/form-data">
                    <td><input TYPE="submit" name="upload" value="Reset Password"/></td>
                </form>
            </tr>
            <tr>
                <td>Email:</td>
                <td><?=$email?></td>
            </tr>
        </table>
    </div>
</div>

</body>


</html>
