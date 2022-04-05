<!doctype html>
    <head>
        <title>Email Verification for Password Page</title>
    </head>

    <body>
    <?php
        if($_GET['key'] && $_GET['token'])
        { 
            $email = $_GET['key'];
            $token = $_GET['token'];

            $DATABASE_HOST = 'localhost';
            $DATABASE_USER = 'root';
            $DATABASE_PASS = '';
            $DATABASE_NAME = 'unisched';
            $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
            if (mysqli_connect_errno()) {
                exit('Failed to connect to MySQL: ' . mysqli_connect_error());
            }
        
            $stmt = $con->prepare('SELECT buffer_password FROM accounts WHERE token =? and email =?');
            // In this case we can use the account ID to get the account info.
            $stmt->bind_param('ss', $token, $email);
            $stmt->execute();
            $stmt->bind_result($buffer_password);
            $stmt->fetch();
            $stmt->close();

            if ($buffer_password != NULL){
                $sql = "UPDATE accounts SET buffer_password = NULL WHERE token = '$token' AND email = '$email'";
                mysqli_query($con, $sql);
                $sql = "UPDATE accounts SET password = '$buffer_password' WHERE token = '$token' AND email = '$email'";
                mysqli_query($con, $sql);
                $message = "Your new password has been verified.";
            }else{
                $message = "ERROR";
            }
        }
        echo "<SCRIPT> 
        alert('$message')
        window.location.replace('index.html');
        </SCRIPT>";
    ?>

    </body>
</html>
