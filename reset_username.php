<?php
// This part is to reset the username.
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
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'unisched';

// We don't have the password or email info stored in sessions so instead we can get the results from the database.
$stmt = $db->prepare('SELECT password, email, f_path FROM accounts WHERE id = ?');
// In this case we can use the account ID to get the account info.
if (isset($_SESSION['searchID']) && $_SESSION['searchID'] != -1) {
    $pageID = $_SESSION['searchID'];
    $fromAdmin = 1;
} else {
    $pageID = $_SESSION['id'];
    $fromAdmin = 0;
}
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $pageID);
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
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Other CSS -->
    <link href="css/adminHome.css" rel="stylesheet">
    <link href="css/home.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>
<style>
  article, aside, figure, footer, header, hgroup, 
  menu, nav, section { display: block; }
</style>
<body class="loggedin">
<div class="container-fluid no-padding">

    <!--navbar-->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <!--Top Navigation Bar-->
            <div class="container">
                <a class="navbar-brand" href="home.php">Unisched</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        <?php
                        if ($_SESSION['admin']) {
                            //Display admin bar
                            echo ' 
                            <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="home.php"><i class="fas fa-home"></i> Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="admin.php"><i class="fas fa-edit"></i> Edit Course</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="courselist.php"><i class="fas fa-university"></i> Course List</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="showAccount.php"><i class="fas fa-th-list"></i> Account List</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="searchAccount.php"><i class="fas fa-user"></i> My Account</a> 
                            </li>
                            </ul>
                            ';
                        } else {
                            //Display normal user bar
                            echo '
                            <li class="nav-item">
                            <a class="nav-link" href="home.php"><i class="fas fa-home"></i> Home</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="timetable.php"><i class="fas fa-edit"></i> Timetable</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="mycourselist.php"><i class="fas fa-university"></i> My Course List</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="courselist.php"><i class="fas fa-th-list"></i> Course List</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="shared.php"><i class="fas fa-user"></i> Share Timetable</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="searchAccount.php"><i class="fas fa-user"></i> Profile</a>
                            </li>
                            ';
                        }
                        ?>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <button type="button" class="navbar-toggler btn btn-danger" onclick="document.location='logout.php'" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">Logout</button>
                        </li>
                        <li class="collapse navbar-collapse">
                        <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                            <?php
                                if (isset($_SESSION['f_path'])) {
                                    $pic = $_SESSION['f_path'];
                                }else{
                                    $pic = "/image/uploadImage.jpg";
                                }
                            ?>
                            <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <img class="rounded-circle" style="width: 40px; height: 40px;" src="<?php echo $pic; ?>" alt="Profile image"> </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown" style="left:-100px; min-width:200px;">
                                <div class="dropdown-header text-center">
                                    <img class="img-fluid rounded-circle img-thumbnail mw-100" style="width: 100px; height: 100px;" src="<?php echo $pic; ?>" alt="Profile pic">
                                    <p class="mb-1 mt-3 font-weight-semibold"><?php echo $_SESSION['name'];?></p>
                                    <p class="fw-light text-muted mb-0"><?php echo $_SESSION['myemail'];?></p>
                                </div>
                                <a class="dropdown-item" href="searchAccount.php"><i class="text-primary me-2"></i> My Account</a>
                                <a class="dropdown-item" href="logout.php"><i class="text-primary me-2"></i> Sign Out</a>
                            </div>
                        </li>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>
    </header>
</div>

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
                   
                    $username = $_POST['new_username'];

                    $stmt = "UPDATE accounts SET username = '$username' WHERE id = $pageID";
                    if($db->query($stmt) === TRUE){
                         $message = "Your username is changed!";	
                           
                         if ($_POST['new_username'] === $_SESSION['name']){
                            $message = "Please enter a new username!";
                         }
                         
                         if ($fromAdmin == 0){
                             $_SESSION['name'] = $username;  
                         }
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</html>