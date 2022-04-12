<!doctype html>
    <head>
        <title>Email Verification for Password Page</title>
    </head>

    <body>
    <?php
        require __DIR__.'/lib/db.inc.php';
        
        global $db;
        $db = unisched_DB();
    
        if($_GET['key'] && $_GET['token'])
        { 
            $email = $_GET['key'];
            $token = $_GET['token'];
        
            $stmt = $db->prepare('SELECT buffer_password FROM accounts WHERE token =? and email =?');
            // In this case we can use the account ID to get the account info.
            $stmt->bind_param('ss', $token, $email);
            $stmt->execute();
            $stmt->bind_result($buffer_password);
            $stmt->fetch();
            $stmt->close();

            if ($buffer_password != NULL){
                $sql = "UPDATE accounts SET buffer_password = NULL WHERE token = '$token' AND email = '$email'";
                mysqli_query($db, $sql);
                $sql = "UPDATE accounts SET password = '$buffer_password' WHERE token = '$token' AND email = '$email'";
                mysqli_query($db, $sql);
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