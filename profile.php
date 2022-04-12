<?php
require __DIR__ . '/lib/db.inc.php';
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}

global $db;
global $pageID;
$db = unisched_DB();

// We don't have the password or email info stored in sessions so instead we can get the results from the database.
$stmt = $db->prepare('SELECT username, password, email, f_path, isAdmin FROM accounts WHERE id = ?');
// In this case we can use the account ID to get the account info.
if (isset($_SESSION['searchID']) && $_SESSION['searchID'] != -1) {
    $pageID = $_SESSION['searchID'];
} else {
    $pageID = $_SESSION['id'];
}
$stmt->bind_param('i', $pageID);
$stmt->execute();
$stmt->bind_result($username, $password, $email, $f_path, $isAdmin);
$stmt->fetch();
$stmt->close();
?>

<?php


$db = unisched_DB();
//Refresh the profile pic
$sql = "SELECT f_path FROM accounts WHERE id = ";
$sql = $sql.$_SESSION['id'];
$res = $db->query($sql);
while ($row = $res->fetch_assoc()) {
$_SESSION['f_path'] = $row['f_path'];
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <title>Profile Page</title>
    <!-- <link href="css/home.css" rel="stylesheet" type="text/css"> -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link class="jsbin" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Other CSS -->
    <link href="css/adminHome.css" rel="stylesheet">
    <link href="css/home.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>
<style>
    article,
    aside,
    figure,
    footer,
    header,
    hgroup,
    menu,
    nav,
    section {
        display: block;
    }
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
    <h2>Profile Page</h2>
    <div class="row">
    <div class="col-12">
    <div class="card position-relative" >
          <div class="card-body">
            <ul class="list-group list-group-flush">
                  <li class="list-group-item">            
                    <form action="profile.php" method="post" enctype="multipart/form-data">
                                <label>
                                <?php
                                if ($f_path == NULL) {
                                    $f_path = "/image/uploadImage.jpg";
                                }
                                ?>
                                 
                                <img id="output" src="<?php echo $f_path ?>" width="200" height="200" class="image" />
                                <br><input name="Filename" type="file" accept="image/*" onchange="document.getElementById('output').src = window.URL.createObjectURL(this.files[0])" style="display:none;">
                                               
                                <br><br><input TYPE="submit" class="bn632-hover bn25" name="upload" value="Submit" /></label><p></p>
                    </form>
                    
                    <?php
                    // Upload the profile pic 
                    if (isset($_POST['upload'])) {
                        // We need to use sessions, so you should always start sessions using the below code.
                        $id = $pageID;

                        $fileName = $_FILES['Filename']['name'];

                        $target = "profile pic/";
                        $fileTarget = $target . $fileName;
                        $tempFileName = $_FILES["Filename"]["tmp_name"];
                        $result = move_uploaded_file($tempFileName, $fileTarget);

                        $fileCheck = basename($_FILES['Filename']['name']);
                        $fileType = strtolower(substr($fileCheck, strrpos($fileCheck, '.') + 1));
                        if (!($fileType == "jpg" || $fileType == "png")) {
                            echo '<script>alert("Please upload a jpg/png file!")</script>';
                        } else {
                            if ($result) {
                                echo '<script>alert("Your profile picture has been successfully uploaded!")</script>';
                                $query = "UPDATE accounts SET f_path = '$fileTarget' WHERE id = $id";;
                                $db->query($query) or die("Error : " . mysqli_error($db));
                            } else {
                                echo '<script>alert("ERROR")</script>';
                            }
                        }

                        mysqli_close($db);
                        // Refresh the page to show the new profile pic
                        $page = $_SERVER['PHP_SELF'];
                        echo '<meta http-equiv="Refresh" content="0;' . $page . '">';
                    }
                    ?></li>
                    
                    <li class="list-group-item">     
                    <p>Username: <?= $username ?></p>       
                    <form action="reset_username.php" method="post" enctype="multipart/form-data">
                        <input TYPE="submit" class="bn632-hover bn25" name="upload" value="Reset Username" /><p></p>
                    </form></li>
                    
                    <li class="list-group-item">     
                    <p>Password: *******************</p>
                    <form action="reset_password.php" method="post" enctype="multipart/form-data">                          
                        <input TYPE="submit" class="bn632-hover bn25" name="upload" value="Reset Password" /><p></p>
                    </form></li>
                    
                    <li class="list-group-item">     
                    <p>Email: <?= $email ?></p>       
                    </li>
                    
                    <li class="list-group-item">
                    <?php
                        echo "Account Type: ";
                        if ($isAdmin == 1) {
                            echo "Administrator";
                        } else {
                            echo "Normal User";
                        }        
                    ?></li>
                    

            </ul>
        </div>
    </div>
    </div>
    </div>
    </div>

</body>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>



</html>