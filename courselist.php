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
    <title>Course List</title>
    <link href="css/home.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
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
<div class="content" id="display">
    <h2>Course List</h2>

    <form action="" method="GET" name="">
	<table>
		<tr>
			<td><input type="text" name="k" value="<?php echo isset($_GET['k']) ? $_GET['k'] : ''; ?>" placeholder="Search for courses" /></td>
			<td><input type="submit" name="" value="Search" /></td>
		</tr>
	</table>
    </form>

    <?php

        $search_string = "SELECT * FROM courses";

        if (isset($_GET['k']) && $_GET['k'] != '') {
            $k = trim($_GET['k']);
            $keywords = explode(' ', $k);
            $search_string .= " WHERE ";
            foreach ($keywords as $word){
                $search_string .= "courseCode LIKE '%".$word."%' OR courseTitle LIKE '%".$word."%' OR ";
            }
            $search_string = substr($search_string, 0, strlen($search_string)-4);

        }

        $stmt = $db->prepare($search_string);
        $stmt->execute();
        $resultSet = $stmt->get_result();
        $res = $resultSet->fetch_all();
        foreach($res as $row){
    ?>
    <div onmouseover="this.style.background='#ccc'" onmouseout="this.style.background=''">
        <p id = "CourseCode<?php echo $row[0]; ?>">Course Code: <?php echo $row[1]; ?></p>
        <p id = "CourseTitle<?php echo $row[0]; ?>">Course Title: <?php echo $row[2]; ?></p>
        <p id = "Unit<?php echo $row[0]; ?>">Unit: <?php echo $row[3]; ?></p>
        <p id = "Time<?php echo $row[0]; ?>">Time: <?php echo $row[4]; ?>-<?php echo $row[5]; ?>, <?php echo $row[6]; ?></p>
        <p id = "Location<?php echo $row[0]; ?>">Location: <?php echo $row[7]; ?></p>
        <button type="button" onclick="addCourse(`<?php echo $_SESSION['id']; ?>`, `<?php echo $row[0]; ?>`)">Add Course</button>
    </div>
    <?php
        }
    ?>

</div>
</body>

<script src = "mycourse.js"></script>

</html>