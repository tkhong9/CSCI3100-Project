<?php
require __DIR__.'/lib/db.inc.php';
// We need to use sessions, so you should always start sessions using the below code.
session_start();

global $db;
$db = unisched_DB();


if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}

$stmt = $db->prepare("SELECT post_id FROM home_post WHERE id = ?");
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($post_id);
$stmt->fetch();

if ($post_id != NULL) {
    //UPDATE home_post SET post_text = 'We can have fun!', course_id = 2 WHERE home_post.post_id = 4;
    $sql = "UPDATE home_post SET post_text = 'We can have fun!', course_id = 2 WHERE home_post.post_id = 4;";
    $sql = $sql.$_POST['post_text'];//post_text
    $sql = $sql.",  course_id = ";
    $sql = $sql.$_SESSION['post_id'];//course_id
    $sql = $sql.",WHERE home_post.post_id = ";
    $sql = $sql.$_SESSION['post_id']; //post_id
    $sql = $sql.";"; //post_id

}else{
    //INSERT INTO home_post (post_id, id, post_text, post_date, isShow, accept_id, course_id) VALUES (NULL, '4', 'testing', '2022-04-05', '1', NULL, '7');
    $sql = "INSERT INTO home_post (post_id, id, post_text, post_date, isShow, accept_id, course_id) VALUES (NULL, ";
    $sql = $sql.$_SESSION['id'];//user id
    $sql = $sql.", ";
    $sql = $sql.$_SESSION['post_text'];//post_text
    $sql = $sql.", '2022-04-05', 1, NULL,";
    $sql = $sql.$_SESSION['post_cid']; //course_id
    $sql = $sql.");"; //post_id
}

if ($db->query($sql) === TRUE) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

header('Location: timetable.php');
?>