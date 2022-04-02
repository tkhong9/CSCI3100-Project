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
    <title>My Course List</title>
    <link href="css/home.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
        <button type="button" onclick="deleteCourse(`<?php echo $_SESSION['id']; ?>`, `<?php echo $courseID[$i]; ?>`)">Delete Course</button>
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
</body>

<script src = "mycourse.js"></script>

</html>