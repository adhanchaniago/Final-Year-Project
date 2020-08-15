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

// Define variables and initialize with empty values
$email = "";
$email_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Email is required.";     
    } else {
        $email = trim($_POST["email"]);
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        	$email_err = "Invalid email format";
        }
    }
   
	if(isset($_POST["reset-request-submit"])){
		//create tokens
		$selector = bin2hex(random_bytes(8));
		$token = random_bytes(32);

		$url = "http://localhost/E-Print/newpassword.php?selector=". $selector . "&validator=" . bin2hex($token);
		// Token expiration
		$expires = date("U") + 1800;

		require_once "dbconfig.php";
		$email = $_POST["email"];

		// Delete any existing tokens for this user
		$sql = "DELETE FROM pwdreset where pwdResetEmail=?;";
		//statement connection from db
		$stmt = mysqli_stmt_init($link);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			echo "There is an error!";
			exit();
		} else {
			mysqli_stmt_bind_param($stmt, "s", $email);
			mysqli_stmt_execute($stmt);
		}

		// Insert reset token into database
		$sql = "INSERT INTO pwdreset (pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) 
		values (?,?,?,?);";
		//statement connection from db
		$stmt = mysqli_stmt_init($link);
		if(!mysqli_stmt_prepare($stmt, $sql)){
			echo "There is an error!";
			exit();
		} else {
			$hashedToken = password_hash($token, PASSWORD_DEFAULT);
			mysqli_stmt_bind_param($stmt, "ssss", $email, $selector, $hashedToken, $expires);
			mysqli_stmt_execute($stmt);
		}
		mysqli_stmt_close($stmt);
		mysqli_close($link);

		// Send the email
		$to = $email;

		// Subject
		$subject = 'Reset Password to Login E-Print System in UNIMAS';
		
		// Message
		$message = '<p>We received your request to reset password. The link to reset your password is below. 
						If you did not make this request, you can ignore this email.</p>';
		$message .= '<p>Here is your password reset link: </br>';
		$message .= '<a href="' . $url . '">' .' Click Here' . '</a></p>';

		// Headers
		$headers = "From: Admin E-Print System <eprintsystem20@gmail.com>\r\n";
		$headers .= "Reply-To: eprintsystem20@gmail.com\r\n";
		$headers .= "Content-type: text/html\r\n";

		// Send email
		mail($to, $subject, $message, $headers);

		header("Location: password.php?reset=success");

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
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" 
					class="login100-form validate-form p-l-40 p-r-40 p-t-60" id="login-form">
					<span class="login100-form-title">
						Password Recovery
					</span>

					<div class="flex-col-c p-t-20 p-b-20">
						<span class="txt1">
						Enter your email address and we will send you a link to reset your password.
						</span>
					</div>

					<div class="wrap-input100 validate-input m-b-15 m-t-20<?php echo (!empty($email_err)) ? 'has-error' : ''; ?>" data-validate="Please enter an email">
						<input class="input100" type="text" name="email" id="email" placeholder="Email Address">
						<span class="focus-input100"></span>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type="submit" name="reset-request-submit">
							Reset Password
						<i class="fa fa-unlock-alt" style="margin-left:10px;"></i></button>
					</div>

					<?php
						if(isset($_GET["reset"])){
							if($_GET["reset"] == "success" ){
								echo '<p class="txt3 m-l-80 p-t-20"> Check Your Email! </p>';
							}
						}
					?>

					<div class="flex-col-c p-t-70 p-b-20">
						<span class="txt1 p-b-7">
							Return to 
						</span>

						<a href="login/login.php" class="txt3">
							Sign In
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