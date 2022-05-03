<?php
// This part is the front page of the user account list.
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <title>Account List Page</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Other CSS -->
    <link href="css/adminHome.css" rel="stylesheet">
    <link href="css/home.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>

<body>

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

        <div class="content row" id="display">
            <h2>Account List</h2>

            <div class="container-fluid px-5">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-striped" id="accountTable">

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- PHP insdie HTML-->
                                <?php
                                require __DIR__ . '/lib/db.inc.php';
                                $db = unisched_DB();
                                $res = $db->query("SELECT * FROM accounts ORDER BY id DESC");
                                while ($row = $res->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td scope="row">' . $row['id'] . '</td>';
                                    echo '<td>' . $row['username'] . '</td>';
                                    echo '<td>' . $row['email'] . '</td>';
                                    echo '<td>
                        <button type="button" class="btn btn-primary btn-result">
                         View & Edit
                        </button>
                        </td>
                        </tr>';
                                }
                                $res->free();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

</body>

</html>


<script>
    $(document).ready(function() {
        //Read selected row data (id)
        $(".btn-result").on('click', function() {
            var currentRow = $(this).closest("tr");
            var row_id = currentRow.find("td:eq(0)").html();

            const form = document.createElement('form');
            form.method = 'post';
            form.action = 'searchAccount.php';
            document.body.appendChild(form);


            const formField = document.createElement('input');
            formField.type = 'hidden';
            formField.name = 'selectedID';
            formField.value = row_id
            form.appendChild(formField);

            form.submit();
        });
    });

    $(document).ready(function() {
        $('#accountTable').DataTable();
    });
</script>