<?php
// This part is for the user to delete their course from the mycourse database.
require __DIR__.'/lib/db.inc.php';
global $db;
$db = unisched_DB();

$userID = $_POST['userID'];
$CourseID = $_POST['courseID'];
$CourseNo = 0;
$hascourse = 0;
$resetcourse = 0;

$sql="SELECT * from mycourses WHERE user_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$resultSet = $stmt->get_result();
$res = $resultSet->fetch_all();
foreach($res as $row){
    for ($x = 0; $x < 6;$x++){
        if ($row[$x+3] == $CourseID){
            $hascourse = 1;
            $CourseNo = $x+1;
            break;
        }
    }
}

if ($CourseNo != 0 and $hascourse == 1){
    $thiscourseID = "courseID" . $CourseNo;

    $sql2="UPDATE mycourses SET " . $thiscourseID . " = ? WHERE user_id = ?";
    $stmt2 = $db->prepare($sql2);

    $stmt2->bind_param("ii", $resetcourse, $userID);
    if($stmt2->execute()){
        echo('The course is deleted successfully!');
    }
}
elseif($hascourse == 0 or $CourseNo == 0){
    echo('The course is not exist in your course list!');
}

?>