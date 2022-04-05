<?php
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

$userid = $_GET['userid'];
$username = $_GET['username'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $username; ?>'s Timetable</title>
    <link href="css/home.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <link rel="stylesheet" href="css/timetable.css">
    
</head>
<body class="loggedin">
<nav class="navtop">
    <div>
        <h1>Unisched</h1>
        <a href="home.php">Home</a>
        <a href="timetable.php" onclick="showPage('timetable.php')">Timetable</a>
        <a href="mycourselist.php" onclick="showPage('mycourselist.php')">My Course List</a>
        <a href="courselist.php" onclick="showPage('courselist.php')">Course List</a>
        <a href="shared.php" onclick="showPage('shared.php')">Share Timetable</a>
        <a href="profile.php" onclick="showPage('profile.php')"><i class="fas fa-user-circle"></i>Profile</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
    </div>
</nav>
    <div class="content" id="display">

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
            <div id="thur8"></div>
            <div id="fri8">
            <!--<div class="accent-green-gradient"></div>-->
            </div>
            <div class="weekend"></div>
            <div class="weekend"></div>

            <!--10:00 - 12:00-->
            <div id="mon10"></div>
            <div id="tue10"></div>
            <div id="wed10"></div>
            <div id="thur10"></div>
            <div id="fri10"></div>
            <div class="weekend"></div>
            <div class="weekend"></div>

            <!--12:00 - 14:00-->
            <div id="mon12"></div>
            <div id="tue12"></div>
            <div id="wed12"></div>
            <div id="thur12"></div>
            <div id="fri12"></div>
            <div class="weekend"></div>
            <div class="weekend"></div>

            <!--14:00 - 16:00-->
            <div id="mon14"></div>
            <div id="tue14"></div>
            <div id="wed14"></div>
            <div id="thur14"></div>
            <div id="fri14"></div>
            <div class="weekend"></div>
            <div class="weekend"></div>

            <!--16:00 - 18:00-->
            <div id="mon16"></div>
            <div id="tue16"></div>
            <div id="wed16"></div>
            <div id="thur16"></div>
            <div id="fri16"></div>
            <div class="weekend"></div>
            <div class="weekend"></div>

            <!--18:00 - 20:00-->
            <div id="mon18"></div>
            <div id="tue18"></div>
            <div id="wed18"></div>
            <div id="thur18"></div>
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
            document.getElementById(weekid).className = "accent-pink-gradient";
            document.getElementById(weekid).innerHTML = courseCode;
        }
    </script>

</body>

<script src = "javascript/timetable.js"></script>

</html>