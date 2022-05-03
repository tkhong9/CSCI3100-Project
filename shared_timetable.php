<?php
// This part is the front page of the user shared timetable.
require __DIR__.'/lib/db.inc.php';
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}

global $db;
$db = unisched_DB();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <title>Shared Timetable</title>

    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    <!-- Other CSS -->
    <link href="css/adminHome.css" rel="stylesheet">
    <link rel="stylesheet" href="css/timetable.css">
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
    <?php
        $userid = $_GET['userid'];
        $username = $_GET['username'];
    ?>
    <h2><?php echo $username; ?>'s Timetable</h2>
    <?php
        $stmt = $db->prepare("SELECT * FROM mycourses WHERE user_id = ?");
        $stmt->bind_param('i', $userid);
        $stmt->execute();
        $resultSet = $stmt->get_result();
        $res = $resultSet->fetch_all();
        foreach($res as $row){
            $courseno = 0;
            $courseID = array($row[3], $row[4], $row[5], $row[6], $row[7], $row[8]);
            for ($i = 0;$i < count($courseID);$i++){
                if ($courseID[$i] != 0){
                    $stmt2 = $db->prepare("SELECT * FROM courses WHERE course_id = ?");
                    $stmt2->bind_param('i', $courseID[$i]);
                    $stmt2->execute();
                    $resultSet2 = $stmt2->get_result();
                    $res2 = $resultSet2->fetch_all();
                    foreach($res2 as $row2){
                        $courseno += 1;
                        $courseCode = $row2[1];
                        //$courseTitle = $row3[2];
                        //$courseUnit = $row3[3];
                        $courseStrTime = $row2[4];
                        $courseEndTime = $row2[5];
                        $courseWeekday = $row2[6];
                        //$courseLocation = $row3[7];

                        $weekday = strtolower(substr($courseWeekday, 0, 3));
                        $strtime = explode(":", $courseStrTime);
                        $weekid = $weekday . $strtime[0]; //e.g. wed10
        
    ?>
    <p id = "mycourseCode<?php echo $courseno; ?>" hidden><?php echo $courseCode; ?></p>
    <p id = "mycourseWeekid<?php echo $courseno; ?>" hidden><?php echo $weekid; ?></p>

    <?php
                    }
                }
            }
        }

    ?>
    <p id = "mycourseno" hidden><?php echo $courseno; ?></p>

    </div>
    <hr>
    <div class="timetable" id = "content1">
        <div class="week-names">
            <div>monday</div>
            <div>tuesday</div>
            <div>wednesday</div>
            <div>thursday</div>
            <div>friday</div>
            <div class="weekend">saturday</div>
            <div class="weekend">sunday</div>
        </div>
        <div class="time-interval">
            <div>8:00 - 10:00</div>
            <div>10:00 - 12:00</div>
            <div>12:00 - 14:00</div>
            <div>14:00 - 16:00</div>
            <div>16:00 - 18:00</div>
            <div>18:00 - 20:00</div>
        </div>
        <div class="content">
            <!--8:00 - 10:00-->
            <div id="mon8">
            <!--<div class="accent-orange-gradient"></div>-->
            </div>
            <div id="tue8"></div>
            <div id="wed8"></div>
            <div id="thu8"></div>
            <div id="fri8">
            <!--<div class="accent-green-gradient"></div>-->
            </div>
            <div class="weekend"></div>
            <div class="weekend"></div>

            <!--10:00 - 12:00-->
            <div id="mon10"></div>
            <div id="tue10"></div>
            <div id="wed10"></div>
            <div id="thu10"></div>
            <div id="fri10"></div>
            <div class="weekend"></div>
            <div class="weekend"></div>

            <!--12:00 - 14:00-->
            <div id="mon12"></div>
            <div id="tue12"></div>
            <div id="wed12"></div>
            <div id="thu12"></div>
            <div id="fri12"></div>
            <div class="weekend"></div>
            <div class="weekend"></div>

            <!--14:00 - 16:00-->
            <div id="mon14"></div>
            <div id="tue14"></div>
            <div id="wed14"></div>
            <div id="thu14"></div>
            <div id="fri14"></div>
            <div class="weekend"></div>
            <div class="weekend"></div>

            <!--16:00 - 18:00-->
            <div id="mon16"></div>
            <div id="tue16"></div>
            <div id="wed16"></div>
            <div id="thu16"></div>
            <div id="fri16"></div>
            <div class="weekend"></div>
            <div class="weekend"></div>

            <!--18:00 - 20:00-->
            <div id="mon18"></div>
            <div id="tue18"></div>
            <div id="wed18"></div>
            <div id="thu18"></div>
            <div id="fri18"></div>
            <div class="weekend"></div>
            <div class="weekend"></div>
        </div>
    </div>

    <script>
        var mycourseno = document.getElementById("mycourseno").innerHTML;
        for (var x = 1; x <= parseInt(mycourseno); x++){
            var courseCode = document.getElementById("mycourseCode" + x).innerHTML;
            var weekid = document.getElementById("mycourseWeekid" + x).innerHTML;
            
            const color = ['accent-pink-gradient', 'accent-orange-gradient', 'accent-green-gradient', 'accent-cyan-gradient', 'accent-blue-gradient', 'accent-purple-gradient'];

            document.getElementById(weekid).className = color[x-1];
            document.getElementById(weekid).innerHTML = courseCode;
        }
    </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>