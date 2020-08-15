<!--This is my Final Year Project (E-Print System in UNIMAS)
    Made by Nur Alia Binti Mohd Yusof (57131)-->
<?php
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title> E-Print System</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">

	<link rel="icon" type="image/png" href="images/printer.png"/>
	
</head>
<body> 
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form method="post" class="login100-form validate-form p-l-40 p-r-40 p-t-110" id="login-form">
					<span class="login100-form-title">
						Sign In
					</span>

					<div class="wrap-input100 m-b-16 validate-input user" data-validate="">
						<input class="input100" type="text" name="username" id="username" placeholder="Username">
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 m-b-16 validate-input pass" data-validate="">
						<input class="input100" type="password" name="password" id="password" placeholder="Password">
						<span class="focus-input100"></span>
					</div>

					<div class="text-right p-t-13 p-b-23">
						<span class="txt1">
							Forgot
						</span>

						<a href="../password.php" class="txt2">
							Your Password?
						</a>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type="button" name="signin" id="signin" value="Login">
							Sign in
                        <i class="fa fa-sign-in" aria-hidden="true" style="margin-left:10px"></i></button>
					</div>

					<div class="flex-col-c p-t-70 p-b-20">
						<span class="txt1 p-b-9">
							Don’t have an account?
						</span>

						<a href="../register/register.php" class="txt3">
							Sign up now
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<!-- JS -->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="vendor/animsition/js/animsition.min.js"></script>
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="vendor/select2/select2.min.js"></script>
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
	<script src="vendor/countdowntime/countdowntime.js"></script>
	<!-- <script src="js/main.js"></script> -->
	<script src="login.js"></script>

</body>
</html>