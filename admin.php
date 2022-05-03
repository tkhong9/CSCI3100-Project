<?php
// This part is the front page for the admin to handle the course database. 
//The admin can add, edit and delete the course from this page.
require __DIR__ . '/lib/db.inc.php';

// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}

// Select the course code
$res = course_fetchall();
$options = '';
foreach ($res as $val) {
    $options .= '<option value="' . $val[0] . '"> ' . $val[1] . ' </option>';
}

// Select the stary day
$options2 = '';
$options2 .= '<option value="8:00">8:00</option>';
$options2 .= '<option value="10:00">10:00</option>';
$options2 .= '<option value="12:00">12:00</option>';
$options2 .= '<option value="14:00">14:00</option>';
$options2 .= '<option value="16:00">16:00</option>';
$options2 .= '<option value="18:00">18:00</option>';

// Select the end day
$options3 = '';
$options3 .= '<option value="10:00">10:00</option>';
$options3 .= '<option value="12:00">12:00</option>';
$options3 .= '<option value="14:00">14:00</option>';
$options3 .= '<option value="16:00">16:00</option>';
$options3 .= '<option value="18:00">18:00</option>';
$options3 .= '<option value="20:00">20:00</option>';

// Select the Weekday day
$options4 = '';
$options4 .= '<option value="Monday">Monday</option>';
$options4 .= '<option value="Tuesday">Tuesday</option>';
$options4 .= '<option value="Wednesday">Wednesday</option>';
$options4 .= '<option value="Thursday">Thursday</option>';
$options4 .= '<option value="Friday">Friday</option>';

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <title>Edit course</title>

    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Other CSS -->
    <link href="css/adminHome.css" rel="stylesheet">
    <link href="css/home.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

</head>
<body class="loggedin">
<div class="container-fluid no-padding">

    <!--navbar-->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <!--Top Navigation Bar-->
            <div class="container">
                <a class="navbar-brand" href="home.php">Unisched</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        <?php
                        if ($_SESSION['admin']) {
                            //Display admin bar
                            echo ' 
                            <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="home.php"><i class="fas fa-home"></i> Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="admin.php"><i class="fas fa-edit"></i> Edit Course</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="courselist.php"><i class="fas fa-university"></i> Course List</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="showAccount.php"><i class="fas fa-th-list"></i> Account List</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="searchAccount.php"><i class="fas fa-user"></i> My Account</a> 
                            </li>
                            </ul>
                            ';
                        } else {
                            //Display normal user bar
                            echo '
                            <li class="nav-item">
                            <a class="nav-link" href="home.php"><i class="fas fa-home"></i> Home</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="timetable.php"><i class="fas fa-edit"></i> Timetable</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="mycourselist.php"><i class="fas fa-university"></i> My Course List</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="courselist.php"><i class="fas fa-th-list"></i> Course List</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="shared.php"><i class="fas fa-user"></i> Share Timetable</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="searchAccount.php"><i class="fas fa-user"></i> Profile</a>
                            </li>
                            ';
                        }
                        ?>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <button type="button" class="navbar-toggler btn btn-danger" onclick="document.location='logout.php'" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">Logout</button>
                        </li>
                        <li class="collapse navbar-collapse">
                        <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                            <?php
                                if (isset($_SESSION['f_path'])) {
                                    $pic = $_SESSION['f_path'];
                                }else{
                                    $pic = "/image/uploadImage.jpg";
                                }
                            ?>
                            <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <img class="rounded-circle" style="width: 40px; height: 40px;" src="<?php echo $pic; ?>" alt="Profile image"> </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown" style="left:-100px; min-width:200px;">
                                <div class="dropdown-header text-center">
                                    <img class="img-fluid rounded-circle img-thumbnail mw-100" style="width: 100px; height: 100px;" src="<?php echo $pic; ?>" alt="Profile pic">
                                    <p class="mb-1 mt-3 font-weight-semibold"><?php echo $_SESSION['name'];?></p>
                                    <p class="fw-light text-muted mb-0"><?php echo $_SESSION['myemail'];?></p>
                                </div>
                                <a class="dropdown-item" href="searchAccount.php"><i class="text-primary me-2"></i> My Account</a>
                                <a class="dropdown-item" href="logout.php"><i class="text-primary me-2"></i> Sign Out</a>
                            </div>
                        </li>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>
    </header>
</div>

<div class="content" id="display">
    <h2>Edit Course</h2>
<div>
<fieldset>
    <legend> Add Course</legend>
    <form id="insert" method="POST" action="admin-process.php?action=insert" enctype="multipart/form-data">
        <label for="course_code"> Course Code *</label>
        <div> <input id="course_code" type="text" name="code" required="required" pattern="^[a-zA-Z0-9\s]+$" /></div>
        <label for="course_title"> Course Title *</label>
        <div> <input id="course_title" type="text" name="title" required="required" pattern="^[a-zA-Z0-9\s]+$" /></div>
        <label for="course_unit"> Unit(s) *</label>
        <div> <input id="course_unit" type="number" name="unit" min="0" max="3" required="required" /></div>
        <label for="course_strtime"> Start Time *</label>
        <div> <select id="course_strtime" name="strtime"><?php echo $options2; ?></select></div>
        <label for="course_endtime"> End Time *</label>
        <div> <select id="course_endtime" name="endtime"><?php echo $options3; ?></select></div>
        <label for="course_day"> Weekday *</label>
        <div> <select id="course_day" name="day"><?php echo $options4; ?></select></div>
        <label for="course_location"> Location * </label>
        <div> <input id="course_location" type="text" name="location" required="required" pattern="^[a-zA-Z0-9\.\s]+$" /></div>
        <input type="submit" value="Submit" class="btn btn-primary btn-result"/>
    </form>
</fieldset>
</div>
<div>
<fieldset>
    <legend> Modify Course</legend>
    <form id="edit" method="POST" action="admin-process.php?action=edit" enctype="multipart/form-data">
        <label for="course_id"> Course Code *</label>
        <div> <select id="course_id" name="course_id"><?php echo $options; ?></select></div>
        <label for="course_strtime"> New Start Time *</label>
        <div> <select id="course_strtime" name="strtime"><?php echo $options2; ?></select></div>
        <label for="course_endtime"> New End Time *</label>
        <div> <select id="course_endtime" name="endtime"><?php echo $options3; ?></select></div>
        <input type="submit" value="Submit" class="btn btn-primary btn-result"/>
    </form>
</fieldset>
</div>
<div>
<fieldset>
    <legend> Delete Course</legend>
    <form id="delete" method="POST" action="admin-process.php?action=delete" enctype="multipart/form-data">
        <label for="course_id"> Course Code *</label>
        <div> <select id="course_id" name="course_id"><?php echo $options; ?></select></div>
        <input type="submit" value="Submit" class="btn btn-primary btn-result"/>
    </form>
</fieldset>
</div>
</div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</html>