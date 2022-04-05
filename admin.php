<?php
require __DIR__.'/lib/db.inc.php';
$res = course_fetchall();
$options = '';

foreach ($res as $val){
    $options .= '<option value="'.$val[0].'"> '.$val[1].' </option>';
}

?>

<html>
    <fieldset>
        <legend> New Course</legend>
        <form id="insert" method="POST" action="admin-process.php?action=insert"
        enctype="multipart/form-data">
            <label for="course_code"> Course Code *</label>
            <div> <input id="course_code" type="text" name="code" required="required" pattern="^[a-zA-Z0-9\s]+$"/></div>
            <label for="course_title"> Course Title *</label>
            <div> <input id="course_title" type="text" name="title" required="required" pattern="^[a-zA-Z0-9\s]+$"/></div>
            <label for="course_unit"> Unit(s) *</label>
            <div> <input id="course_unit" type="number" name="unit" min="0" max="3" required="required"/></div>
            <label for="course_strtime"> Start Time *</label>
            <div> <input id="course_strtime" type="text" name="strtime" required="required" pattern="^[0-9\:]+$"/></div>
            <label for="course_endtime"> End Time *</label>
            <div> <input id="course_endtime" type="text" name="endtime" required="required" pattern="^[0-9\:]+$"/></div>
            <label for="course_day"> Weekday *</label>
            <div> <input id="course_day" type="text" name="day" required="required" pattern="^[a-zA-Z]+$"/></div>
            <label for="course_location"> Location * </label>
            <div> <input id="course_location" type="text" name="location" required="required" pattern="^[a-zA-Z0-9\s]+$"/></div>
            <input type="submit" value="Submit"/>
        </form>
    </fieldset>

    <fieldset>
        <legend> Edit Course</legend>
        <form id="edit" method="POST" action="admin-process.php?action=edit"
        enctype="multipart/form-data">
            <label for="course_id"> Course Code *</label>
            <div> <select id="course_id" name="course_id"><?php echo $options; ?></select></div>
            <label for="course_strtime"> New Start Time *</label>
            <div> <input id="course_strtime" type="text" name="strtime" required="required" pattern="^[0-9\:]+$"/></div>
            <label for="course_endtime"> New End Time *</label>
            <div> <input id="course_endtime" type="text" name="endtime" required="required" pattern="^[0-9\:]+$"/></div>
            <input type="submit" value="Submit"/>
        </form>
    </fieldset>

    <fieldset>
        <legend> Delete Course</legend>
        <form id="delete" method="POST" action="admin-process.php?action=delete"
        enctype="multipart/form-data">
            <label for="course_id"> Course Code *</label>
            <div> <select id="course_id" name="course_id"><?php echo $options; ?></select></div>
            <input type="submit" value="Submit"/>
        </form>
    </fieldset>

</html>
