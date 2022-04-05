<!doctype html>
    <head>
        <title>Email Verification for Creating Account Page</title>
    </head>

    <body>
    <?php
        require __DIR__.'/lib/db.inc.php';
        
        if($_GET['key'] && $_GET['token'])
        { 
            $email = $_GET['key'];
            $token = $_GET['token'];
            
            global $db;
            $db = unisched_DB();
        
            $stmt = $db->prepare('SELECT verified FROM accounts WHERE token =? and email =?');
            // In this case we can use the account ID to get the account info.
            $stmt->bind_param('ss', $token, $email);
            $stmt->execute();
            $stmt->bind_result($verified);
            $stmt->fetch();
            $stmt->close();

            $num = 1;
            if ($verified === 0){
                $sql = "UPDATE accounts SET verified = $num WHERE token = '$token' AND email = '$email'";
                mysqli_query($db, $sql);
                $message = "Your account has been verified.";
            }else{
                $message = "A verification email is sent to your email address!";
            }
        }
        echo "<SCRIPT> 
        alert('$message')
        window.location.replace('index.html');
        </SCRIPT>";
    ?>

    </body>
</html>