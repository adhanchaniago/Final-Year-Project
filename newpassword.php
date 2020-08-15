<?php
//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect to welcome page   
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){

	if($_SESSION["usertype"] == 'admin'){
		header("location: admin/dashboard.php");
		 exit;
	}else if ($_SESSION["usertype"] == 'vendor'){
		header("location: vendor/dashboard.php");
		exit;
	}else{
		header("location: user/upload.php");
		exit;
	}
}

// Include config file
require_once "dbconfig.php";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

	// Validate password
    if(empty(trim($_POST["pwd"]))){
        $password_err = "Please enter a password";     
    } elseif(strlen(trim($_POST["pwd"])) < 6){
        $password_err = "Password must have atleast 6 characters";
    } else{
        $password = trim($_POST["pwd"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["pwd-repeat"]))){
        $confirm_password_err = "Please confirm password";     
    } else{
        $passwordRepeat = trim($_POST["pwd-repeat"]);
        if(empty($password_err) && ($password != $passwordRepeat)){
            $confirm_password_err = "Password did not match";
        }
    }
   
	if(isset($_POST["reset-password-submit"])){

		$selector = $_POST["selector"];
		$validator = $_POST["validator"];
		$password = $_POST["pwd"];
		$passwordRepeat = $_POST["pwd-repeat"];

		if(empty($password) || empty($passwordRepeat)) {
			header("Location: newpassword.php?newpwd=empty");
			exit();
		} else if ($password != $passwordRepeat) {
			header("Location: newpassword.php?newpwd=pwdnotsame");
			exit();
		}

		$currentDate = date("U");
		require_once "dbconfig.php";

		$sql = "SELECT * FROM pwdreset where pwdResetSelector=? AND pwdResetExpires >=? ";
		$stmt = mysqli_stmt_init($link);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			echo "There is an error!";
			exit();
		} else {
			mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate);
			mysqli_stmt_execute($stmt);

			$result = mysqli_stmt_get_result($stmt);
			if(!$row = mysqli_fetch_assoc($result)){
				echo "You need to re-submit your reset request.";
				exit();
			} else{
				$tokenBin = hex2bin($validator);
				$tokenCheck = password_verify($tokenBin, $row["pwdResetToken"]);

				if($tokenCheck === false){
					echo "You need to re-submit your reset request.";
					exit();
				}else if ($tokenCheck === true){
					$tokenEmail = $row['pwdResetEmail'];
					 
					$sql = "SELECT * FROM login WHERE email=?";
					$stmt = mysqli_stmt_init($link);
					if(!mysqli_stmt_prepare($stmt, $sql)){
						echo "There was an error!";
						exit();
					}else{
						mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
						mysqli_stmt_execute($stmt);
						$result = mysqli_stmt_get_result($stmt);
						if(!$row = mysqli_fetch_assoc($result)){
							echo "There was an error!";
							exit();
						}else{
							$sql = "UPDATE login set password=? where email=?";
							$stmt = mysqli_stmt_init($link);
							if(!mysqli_stmt_prepare($stmt, $sql)){
								echo "There was an error!";
								exit();
							}else{
								$newPwdHash = password_hash($password, PASSWORD_DEFAULT);
								mysqli_stmt_bind_param($stmt, "ss", $newPwdHash, $tokenEmail);
								mysqli_stmt_execute($stmt);

								$sql = "DELETE from pwdReset where pwdResetEmail=?";
								$stmt = mysqli_stmt_init($link);
								if(!mysqli_stmt_prepare($stmt, $sql)){
									echo "There was an error!";
									exit();
								} else {
									mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
									mysqli_stmt_execute($stmt);
									header("Location: newpassword.php?newpwd=passwordupdated");
								}
							}
						}
					}
				}
			}
		}

	} else{
		header("location: login/login.php");
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title> E-Print System</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="login/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="login/vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="login/vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="login/vendor/animsition/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="login/vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="login/vendor/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="login/css/util.css">
	<link rel="stylesheet" type="text/css" href="login/css/main.css">

	<link rel="icon" type="image/png" href="login/images/printer.png"/>
	
</head>
<body> 	
	<div class="limiter">
	<div class="container-login100">
			<div class="wrap-login100">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="login100-form validate-form p-l-40 p-r-40 p-t-100">			
                    <?php
					if(isset($_GET["selector"], $_GET["validator"])){
						// Check for tokens
                        $selector = $_GET["selector"];
                        $validator = $_GET["validator"];

                        if(empty($selector) || empty($validator)){
                            echo "Could not validate your request!";
                        } else {
                            if(ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false){
                    ?>
					<span class="login100-form-title">
						New Password
					</span>
						<input type="hidden" name="selector" value="<?php echo $selector;?>">
						<input type="hidden" name="validator" value="<?php echo $validator;?>">	

						<div class="wrap-input100 validate-input m-b-16 <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>" data-validate = "Password is required">
							<input class="input100" type="password" name="pwd" placeholder="Enter a new password">
							<span class="focus-input100"></span>
						</div>
						<div class="wrap-input100 validate-input <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>" data-validate="Password is required">
							<input class="input100" type="password" name="pwd-repeat" placeholder="Confirm new password">
							<span class="focus-input100"></span>
						</div>
						<div class="container-login100-form-btn m-b-15 m-t-30">
							<button class="login100-form-btn" type="submit" name="reset-password-submit" id="reset-password-submit">
								SAVE NEW PASSWORD
							<i class="fa fa-lock" style="margin-left:10px;"></i></button>
						</div>
					<?php
							}			
						}
					}
						if(isset($_GET["newpwd"])){
							if($_GET["newpwd"] == "passwordupdated" ){
								echo '<p class="txt3 m-l-30"> Your password has been reset! </p>';
							}
						}
					?>
					<div class="flex-col-c p-t-20 p-b-95">
						<a href="login/login.php" class="txt3">
							Click Here to sign in
						</a>
					</div>	
				</form>
			</div>
		</div>
	</div>
	
	<!-- JS -->
	<script src="login/vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="login/vendor/animsition/js/animsition.min.js"></script>
	<script src="login/vendor/bootstrap/js/popper.js"></script>
	<script src="login/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="login/vendor/select2/select2.min.js"></script>
	<script src="login/vendor/daterangepicker/moment.min.js"></script>
	<script src="login/vendor/daterangepicker/daterangepicker.js"></script>
	<script src="login/vendor/countdowntime/countdowntime.js"></script>
	<script src="login/js/main.js"></script>

</body>
</html>