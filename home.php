<?php
// This is the home page for user. It also shows the post that posted by others.
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <title>Home Page</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Other CSS -->
    <link href="css/adminHome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link href="css/home.css" rel="stylesheet" type="text/css">
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
                                } else {
                                    $pic = "/image/uploadImage.jpg";
                                }
                                ?>
                                <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img class="rounded-circle" style="width: 40px; height: 40px;" src="<?php echo $pic; ?>" alt="Profile image"> </a>
                                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown" style="left:-100px; min-width:200px;">
                                    <div class="dropdown-header text-center">
                                        <img class="img-fluid rounded-circle img-thumbnail mw-100" style="width: 100px; height: 100px;" src="<?php echo $pic; ?>" alt="Profile pic">
                                        <p class="mb-1 mt-3 font-weight-semibold"><?php echo $_SESSION['name']; ?></p>
                                        <p class="fw-light text-muted mb-0"><?php echo $_SESSION['myemail']; ?></p>
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

        <!--Welcome message-->
        <div class="content container pt-1">
            <h2>Home</h2>
            <p>Welcome back, <?= $_SESSION['name'] ?>!</p>
        </div>

        <!--Home page post-->
        <div class="container mb-5">
            <!-- PHP inside HTML -->
            <?php
            require __DIR__ . '/lib/db.inc.php';
            $db = unisched_DB();
            $sql = "SELECT  accounts.f_path, accounts.username,home_post.post_text, courses.courseTitle,courses.courseCode\n"
                . "FROM home_post , accounts , courses\n"
                . "WHERE (( isShow = 1))\n"
                . "AND accounts.id = home_post.id\n"
                . "AND courses.course_id = home_post.course_id;";
            $res = $db->query($sql);
            while ($row = $res->fetch_assoc()) {
                if ($row['f_path'] == Null) {
                    $my_f_path = "/image/uploadImage.jpg";
                }
                else {
                    $my_f_path = $row['f_path'];
                }
                echo '<div class="row d-flex align-items-center justify-content-center">
                      <div class="col pt-5">
                      <div class="card">
                      <div class="d-flex justify-content-between p-2 px-3">
                      <div class="d-flex flex-row align-items-center">';
                //Dispaly username pic
                echo '<img src="';
                echo $my_f_path;
                echo '"width="200px" height: "30%" class="img-fluid rounded-circle">';
                //Display username
                echo '<div class="d-flex flex-column ml-2">';
                echo $row['username'];
                echo '</span></div></div>';
                echo '<p>';
                echo $row['post_text'];
                echo '</p>';
                echo '<p>';
                echo $row['courseTitle'];
                echo ' ';
                echo $row['courseCode'];
                echo '</p></div>
                     </div>
                     </div>
                     </div>';
            }
            ?>
        </div>
    </div>
</body>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</html>