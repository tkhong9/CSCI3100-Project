<?php
// This part is for the user authentication.
require __DIR__.'/lib/db.inc.php';
// We need to use sessions, so you should always start sessions using the below code.
session_start();

global $db;
$db = unisched_DB();

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['username'], $_POST['password']) ) {
	// Could not get the data that should have been sent.
	exit('Please fill both the username and password fields!');
}

// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $db->prepare('SELECT id, password, email, isAdmin, verified, f_path FROM accounts WHERE username = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	// Store the result so we can check if the account exists in the database.
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
    	$stmt->bind_result($id, $password, $email, $isAdmin, $verified, $f_path);
    	$stmt->fetch();
    	// Account exists, now we verify the password.
    	// Note: remember to use password_hash in your registration file to store the hashed passwords.
    	if (password_verify($_POST['password'], $password)) {
         if ($verified === 1) {
   		      // Verification success! User has logged-in!
        		// Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
        		session_regenerate_id();
        		$_SESSION['loggedin'] = TRUE;
            $_SESSION['myemail'] = $email;
        		$_SESSION['name'] = $_POST['username'];
        		$_SESSION['id'] = $id;
            $_SESSION['f_path'] = $f_path;
        		if($isAdmin == 1){
                $_SESSION['admin'] = TRUE;
        		}else{
                $_SESSION['admin'] = FALSE;
        		} 
          
               header('Location: home.php');
       		    // Unverified account
              $message = "Account has not been verified!";
        		  echo "<script>
        		  alert('$message');
        		  window.location.href='login.html';
        		  </script>";    
         }

    	} else {
    		// Incorrect password
            $message = "Incorrect username and/or password!";
        		echo "<script>
        		alert('$message');
        		window.location.href='login.html';
        		</script>";
    	}
    } else {
    	// Incorrect username
        $message = "Incorrect username and/or password!";
    		echo "<script>
    		alert('$message');
    		window.location.href='login.html';
    		</script>";
    }


	$stmt->close();
}
?>