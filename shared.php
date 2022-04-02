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
    <title>Share Course</title>
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
        <a href="shared.php" onclick="showPage('shared.php')">Share Course</a>
        <a href="profile.php" onclick="showPage('profile.php')"><i class="fas fa-user-circle"></i>Profile</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
    </div>
</nav>
<div class="content" id="display">
    <h2>Share Course</h2>

    <?php
        $shared = 1;
        $stmt = $db->prepare("SELECT * FROM mycourses WHERE shared = ?");
        $stmt->bind_param('i', $shared);
        $stmt->execute();
        $resultSet = $stmt->get_result();
        $res = $resultSet->fetch_all();
        foreach($res as $row){
            $user_id = $row[1];
            $stmt2 = $db->prepare("SELECT username FROM accounts WHERE id = ?");
            $stmt2->bind_param('i', $user_id);
            $stmt2->execute();
            $resultSet2 = $stmt2->get_result();
            $res2 = $resultSet2->fetch_all();
            foreach($res2 as $row2){
                $username = $row2[0];
    ?>
    <div onclick="location.href='shared_timetable.php?userid=<?php echo $user_id; ?>&username=<?php echo $username; ?>'" onmouseover="this.style.background='#ccc'" onmouseout="this.style.background=''">
        <h3><?php echo $username; ?>'s Timetable</h3>
    </div>
    <?php
            }
        }
    ?>

</div>
</body>

</html>