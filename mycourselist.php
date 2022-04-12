<?php
require __DIR__.'/lib/db.inc.php';
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}
// Call database
global $db;
$db = unisched_DB();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <title>My Course List</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    <!-- Other CSS -->
    <link href="css/adminHome.css" rel="stylesheet">
    <link href="css/home.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">        
</head>
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

<div class="content" id="display">
    <h2>My Course List</h2>

    <?php
        $stmt = $db->prepare("SELECT id FROM accounts WHERE username = ?");
        $stmt->bind_param('s', $_SESSION['name']);
        $stmt->execute();
        $resultSet = $stmt->get_result();
        $res = $resultSet->fetch_all();
        $res_count = count($res);

        if ($res_count == 0) {
            header('Content-Type: text/html; charset=utf-8');
            echo 'This user is not exist! <br/><a href="javascript:history.back();">Back to admin panel.</a>';
        }
        else{
            foreach($res as $row){
                $user_id = $row[0];
                $stmt2 = $db->prepare("SELECT * FROM mycourses WHERE user_id = ?");
                $stmt2->bind_param('i', $user_id);
                $stmt2->execute();
                $resultSet2 = $stmt2->get_result();
                $res2 = $resultSet2->fetch_all();
                foreach($res2 as $row2){
                    $courseID = array($row2[3], $row2[4], $row2[5], $row2[6], $row2[7], $row2[8]);
                    for ($i = 0;$i < count($courseID);$i++){
                        if ($courseID[$i] != 0){
                            $stmt3 = $db->prepare("SELECT * FROM courses WHERE course_id = ?");
                            $stmt3->bind_param('i', $courseID[$i]);
                            $stmt3->execute();
                            $resultSet3 = $stmt3->get_result();
                            $res3 = $resultSet3->fetch_all();
                            foreach($res3 as $row3){
        
    ?>
    <div onmouseover="this.style.background='#ccc'" onmouseout="this.style.background=''">
        <p id = "myCourseCode<?php echo $courseID[$i]; ?>">Course Code: <?php echo $row3[1]; ?></p>
        <p id = "myCourseTitle<?php echo $courseID[$i]; ?>">Course Title: <?php echo $row3[2]; ?></p>
        <p id = "myUnit<?php echo $courseID[$i]; ?>">Unit: <?php echo $row3[3]; ?></p>
        <p id = "myTime<?php echo $courseID[$i]; ?>">Time: <?php echo $row3[4]; ?>-<?php echo $row3[5]; ?>, <?php echo $row3[6]; ?></p>
        <p id = "myLocation<?php echo $courseID[$i]; ?>">Location: <?php echo $row3[7]; ?></p>
        <button type="button" class="bn632-hover bn25" onclick="deleteCourse(`<?php echo $_SESSION['id']; ?>`, `<?php echo $courseID[$i]; ?>`)">Delete Course</button>
    </div>
    <?php
                            }
                        }
                    }
                }
            }
        }
    ?>

</div>

    <!-- Popper and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

<script src = "mycourse.js"></script>

</html>