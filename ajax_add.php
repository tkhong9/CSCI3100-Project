<?php
require __DIR__.'/lib/db.inc.php';
global $db;
$db = unisched_DB();

$userID = $_POST['userID'];
$CourseID = $_POST['courseID'];
$strTime = $_POST['strTime'];
$endTime = $_POST['endTime'];
$day = $_POST['day'];

$newstrTime = intval(explode(":", $strTime));
$newendTime = intval(explode(":", $endTime));

$CourseNo = 0;
$hascourse = 0;
$timeconflict = 0;
$conflictcourse = "";

$sql="SELECT * from mycourses WHERE user_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$resultSet = $stmt->get_result();
$res = $resultSet->fetch_all();
foreach($res as $row){
    for ($x = 0; $x < 6;$x++){
        // If the course already inside the mycourses database
        if ($row[$x+3] == $CourseID){
            $hascourse = 1;
            break;
        }

        // Check whether it has time conflict with the origin course in my course list
        $sql3="SELECT * from courses WHERE course_id = ?";
        $stmt3 = $db->prepare($sql3);
        $stmt3->bind_param("i", $row[$x+3]);
        $stmt3->execute();
        $resultSet3 = $stmt3->get_result();
        $res3 = $resultSet3->fetch_all();
        foreach($res3 as $row3){
            $thestrTime = intval(explode(":", $row3[4]));
            $theendTime = intval(explode(":", $row3[5]));
            $theday = $row3[6];
            if ($day == $theday){
                if ($newstrTime >= $thestrTime and $newstrTime <= $theendTime){
                    $timeconflict = 1;
                    $conflictcourse = $row3[1];
                    break;
                }
                if ($newendTime >= $thestrTime and $newendTime <= $theendTime){
                    $timeconflict = 1;
                    $conflictcourse = $row3[1];
                    break;
                }
            }
        }

        // If the mycourses database has empty space, use this space
        if ($row[$x+3] == 0){
            $CourseNo = $x+1;
            break;
        }
    }
}

if ($CourseNo != 0 and $hascourse != 1 and $timeconflict != 1){
    $thiscourseID = "courseID" . $CourseNo;

    $sql2="UPDATE mycourses SET " . $thiscourseID . " = ? WHERE user_id = ?";
    $stmt2 = $db->prepare($sql2);
    
    $stmt2->bind_param("ii", $CourseID, $userID);
    
    if($stmt2->execute()){
        echo('The course is added successfully!');
    }
}
elseif($hascourse == 1){
    echo('The course is already added into your course list!');
}
elseif($timeconflict == 1){
    echo('The course has time conflict with ' . $conflictcourse . ' in your course list! Please check it again!');
}
elseif($CourseNo == 0){
    echo('Your course list is full!');
}

?>