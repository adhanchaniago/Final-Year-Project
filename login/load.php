<?php
//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

// Check if the user is already logged in, if yes then redirect to welcome page   
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){

	if($_SESSION["usertype"] == 'admin'){
		header("location: ../admin/dashboard.php");
		 exit;
	}else if ($_SESSION["usertype"] == 'vendor'){
		header("location: ../vendor/dashboard.php");
		exit;
	}else{
		header("location: ../user/upload.php");
		exit;
	}
}

// Include config file
require_once "../dbconfig.php";

// Processing form data when form is submitted
if(isset($_POST['signin'])){

	// Define variables and initialize with empty values
	$username = $password = "";
	$username_err = $password_err = "";
	
    $data = array();
    $error= array();
    // Check if username is empty
	if(empty(trim($_POST["username"]))){
		$error['user_error'] = "Please enter username";
		$username_err = $error['user_error']; 
	} else{
		$username = trim($_POST["username"]);
	}

	// Validate password
    if(empty(trim($_POST["password"]))){
        $error['password_err'] = "Please enter password";
        $password_err = $error['password_err'];

    } else{
        $password = trim($_POST["password"]);
    }

	// Validate credentials
	if(empty($username_err) && empty($password_err)){
		// Prepare a select statement
		$sql = "SELECT id, username, email, password, usertype FROM login WHERE username = ?";

		if($stmt = mysqli_prepare($link, $sql)){
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "s", $param_username);

			// Set parameters
			$param_username = $username;

			// Attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt)){
				// Store result
				mysqli_stmt_store_result($stmt);

				// Check if username exists, if yes then verify password
				if(mysqli_stmt_num_rows($stmt) == 1){
					// Bind result variables
					mysqli_stmt_bind_result($stmt, $id, $username, $email, $hashed_password, $usertype);
					if(mysqli_stmt_fetch($stmt)){
						
						if(password_verify($password, $hashed_password)){                       
							//Check either admin or user or vendor
							if ($usertype=='admin'){
								// Password is correct, so start a new session
								session_start();

								// Store data in session variables
								$_SESSION["loggedin"] = true;
								$_SESSION["id"] = $id;
								$_SESSION["username"] = $username;
								$_SESSION["usertype"] = $usertype;

								$error['admin_success']= "Sucess";

								// Redirect user to welcome page
								//header("location: admin/dashboard.php");
									
							} else if ($usertype=='vendor'){
								// Password is correct, so start a new session
								session_start();

								// Store data in session variables
								$_SESSION["loggedin"] = true; 
								$_SESSION["id"] = $id;
								$_SESSION["username"] = $username;
								$_SESSION["usertype"] = $usertype;
								$error['vendor_success']= "Sucess";

								// Redirect user to welcome page
								//header("location: vendor/dashboard.php");

							}else if ($usertype=='user'){ 
								// Password is correct, so start a new session
								session_start();

								// Store data in session variables
								$_SESSION["loggedin"] = true;
								$_SESSION["id"] = $id;
								$_SESSION["username"] = $username;
								$_SESSION["usertype"] = $usertype;
								$error['user_success']= "Sucess";

								// Redirect user to welcome page
								//header("location: user/upload.php");
								}
							} else{
							// Display an error message if password is not valid
							$error['password_err'] = "Invalid username or password";
							$error['user_error'] = "Invalid username or password";
						}
					}
				} else{
					// Display an error message if username doesn't exist
					$error['user_error'] = "Invalid username or password";
					$error['password_err'] = "Invalid username or password";
				}
			} else{
				echo "Oops! Something went wrong. Please try again later.";
			}
		}
		// Close statement
		mysqli_stmt_close($stmt);
	}
	
	array_push($data, $error);
    echo json_encode($data);   
    // Close connection
    mysqli_close($link);
}
?>


