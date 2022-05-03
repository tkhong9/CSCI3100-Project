<?php
// This part is to handle the database. 
//The admin can add, edit, delete the course from the course_xxx() function.
function unisched_DB() {
	// connect to the database
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'unisched';
    $db = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    if (mysqli_connect_errno()) {
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

	return $db;
}

function course_fetchall() {
    global $db;
    $db = unisched_DB();
    $stmt = $db->prepare("SELECT * FROM courses LIMIT 100;");
    $stmt->execute();
    $resultSet = $stmt->get_result();
    return $resultSet->fetch_all();
}

function course_insert(){
    global $db;
    $db = unisched_DB();

    if (!preg_match('/^[a-zA-Z0-9\s]+$/', $_POST['code']))
        throw new Exception("invalid-code");
    if (!preg_match('/^[a-zA-Z0-9\s]+$/u', $_POST['title']))
        throw new Exception("invalid-title");
    if (!preg_match('/^[0-9]+$/', $_POST['unit']))
        throw new Exception("invalid-unit");
    $_POST['unit'] = (int) $_POST['unit'];
    if (!preg_match('/^[0-9\:]+$/u', $_POST['strtime']))
        throw new Exception("invalid-start-time");
    if (!preg_match('/^[0-9\:]+$/u', $_POST['endtime']))
        throw new Exception("invalid-end-time");
    if (!preg_match('/^[a-zA-Z]+$/', $_POST['day']))
        throw new Exception("invalid-day");
    if (!preg_match('/^[a-zA-Z0-9\.\s]+$/', $_POST['location']))
        throw new Exception("invalid-location");


    $sql="INSERT INTO courses (courseCode, courseTitle, unit, startTime, endTime, day, location) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);

    $code = $_POST["code"];
    $title = $_POST["title"];
    $unit = $_POST["unit"];
    $strtime = $_POST["strtime"];
    $endtime = $_POST["endtime"];
    $day = $_POST["day"];
    $location = $_POST["location"];

    $stmt->bind_param("ssissss", $code, $title, $unit, $strtime, $endtime, $day, $location);

    if($stmt->execute()){
        header('Location: admin.php');
    }
}

function course_edit(){
    global $db;
    $db = unisched_DB();
    if (!preg_match('/^\d*$/', $_POST['course_id']))
        throw new Exception("invalid-course-id");
    if (!preg_match('/^[0-9\:]+$/u', $_POST['strtime']))
        throw new Exception("invalid-start-time");
    if (!preg_match('/^[0-9\:]+$/u', $_POST['endtime']))
        throw new Exception("invalid-end-time");

    $_POST['course_id'] = (int) $_POST['course_id'];
    $course_id = $_POST["course_id"];
    $strtime = $_POST["strtime"];
    $endtime = $_POST["endtime"];

    $stmt = $db->prepare("SELECT * FROM courses WHERE course_id = ?");
    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $resultSet = $stmt->get_result();
    $res_count = count($resultSet->fetch_all());

    if ($res_count == 0) {
        header('Content-Type: text/html; charset=utf-8');
        echo 'This course is not inside the database! <br/><a href="javascript:history.back();">Back to admin panel.</a>';
    }
    else{
        $stmt2 = $db->prepare("UPDATE courses SET startTime = ?, endTime = ? WHERE course_id = ?");
        $stmt2->bind_param("ssi", $strtime, $endtime, $course_id);
        if($stmt2->execute()){
            header('Location: admin.php');
        }
    }
}

function course_delete(){
    global $db;
    $db = unisched_DB();
    if (!preg_match('/^\d*$/', $_POST['course_id']))
        throw new Exception("invalid-course-id");
    $_POST['course_id'] = (int) $_POST['course_id'];
    $course_id = $_POST["course_id"];
    $stmt = $db->prepare("SELECT * FROM courses WHERE course_id = ?");
    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $resultSet = $stmt->get_result();
    $res_count = count($resultSet->fetch_all());

    if ($res_count == 0) {
        header('Content-Type: text/html; charset=utf-8');
        echo 'This course is not inside the database! <br/><a href="javascript:history.back();">Back to admin panel.</a>';
    }
    else{
        $stmt2 = $db->prepare("DELETE FROM courses WHERE course_id = ?");
        $stmt2->bind_param('i', $course_id);
        if($stmt2->execute()){
            header('Location: admin.php');
        }
    }
}

?>