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
    <title>Profile Page</title>
    <link href="css/home.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>
<body class="loggedin">
<nav class="navtop">
    <div>
        <h1>Unisched</h1>
        <a href="home.php">Home</a>
        <a href="#" onclick="showPage('timetable.php')">Timetable</a>
        <a href="courselist.php" onclick="showPage('courselist.php')">Course List</a>
        <a href="profile.php" onclick="showPage('profile.php')"><i class="fas fa-user-circle"></i>Profile</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
    </div>
</nav>
<div class="content" id="display">
    <h2>Course List</h2>

    <?php
        $stmt = $con->prepare("SELECT * FROM courses");
        $stmt->execute();
        $resultSet = $stmt->get_result();
        $res = $resultSet->fetch_all();
        foreach($res as $row){
    ?>
    <div onmouseover="this.style.background='#ccc'" onmouseout="this.style.background=''">
        <p>Course Code: <?php echo $row[1]; ?></p>
        <p>Course Title: <?php echo $row[2]; ?></p>
        <p>Unit: <?php echo $row[3]; ?></p>
        <p>Time: <?php echo $row[4]; ?>, <?php echo $row[5]; ?></p>
        <p>Location: <?php echo $row[6]; ?></p>
    </div>
    <?php
        }
    ?>

</div>
</body>
</html>