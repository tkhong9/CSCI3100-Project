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
$DATABASE_NAME = 'login_account';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Timetable</title>
    <link href="css/home.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <link rel="stylesheet" href="css/timetable.css">
    
</head>
<body class="loggedin">
    <div>
    <h3>Course List:</h3>
    <?php
        $stmt = $con->prepare("SELECT * FROM courses");
        $stmt->execute();
        $resultSet = $stmt->get_result();
        $res = $resultSet->fetch_all();
        $course_no = 0;
        foreach($res as $row){
            $course_no += 1;
    ?>
        <input type="checkbox" id="course<?php echo $course_no; ?>" name="vehicle<?php echo $course_no; ?>" onclick="course_click('c<?php echo $course_no; ?>')">
        <label for="course<?php echo $course_no; ?>" id="c<?php echo $course_no; ?>"><?php echo $row[1]; ?> <?php echo $row[4]; ?>, <?php echo $row[5]; ?></label><br>
    <?php
        }
    ?>
    </div>
    <hr>
    <div class="timetable">
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
</body>

<script src = "timetable.js"></script>

</html>