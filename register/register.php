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
		<link rel="stylesheet" type="text/css" href="../login/vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="../login/vendor/animate/animate.css">
		<link rel="stylesheet" type="text/css" href="../login/vendor/css-hamburgers/hamburgers.min.css">
		<link rel="stylesheet" type="text/css" href="../login/vendor/animsition/css/animsition.min.css">
		<link rel="stylesheet" type="text/css" href="../login/vendor/select2/select2.min.css">
		<link rel="stylesheet" type="text/css" href="../login/vendor/daterangepicker/daterangepicker.css">
		<link rel="stylesheet" type="text/css" href="../login/css/util.css">
		<link rel="stylesheet" type="text/css" href="../login/css/main.css">
	
		<link rel="icon" type="image/png" href="../login/images/printer.png"/>
	
</head>
<body> 
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form method="post" class="login100-form validate-form p-l-40 p-r-40 p-t-95" id="register-form">
					<span class="login100-form-title">
						Sign Up
					</span>

					<div class="wrap-input100 m-b-16 validate-input user"data-validate="">
						<input class="input100" type="text" name="username" id="username" placeholder="Username">
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100  m-b-16 validate-input mail"data-validate="">
						<input class="input100" type="text" name="email" id="email" placeholder="Email">
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100  m-b-16 validate-input pass" data-validate="">
						<input class="input100" type="password" name="password" id="password" placeholder="Password">
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100  m-b-16 validate-input conf_pass" data-validate="">
						<input class="input100" type="password" name="confirm_password" id="confirm_password" placeholder="Confirm password">
						<span class="focus-input100"></span>
					</div>

					<div class="p-t-15 p-l-40 p-r-40">
                        <h6><b>Register as ?</b></h6>
                        <input type="radio" name="usertype" value="user" checked="checked">
						<label class="radio-container p-t-5 m-r-55">Customer
                            <span class="checkmark"></span>	
                        </label>
                        <input type="radio" name="usertype" value="vendor">
						<label class="radio-container p-b-15">Vendor
                            <span class="checkmark"></span>
						</label>
                    </div>	
                    
					<div class="container-login100-form-btn">
						<button class="login100-form-btn"  type="button" name="signup" id="signup" value="Register">
							Sign Up
                    	</button>
					</div>

					<div class="flex-col-c p-t-40 p-b-10">
						<span class="txt1 p-b-9">
							Already have an account?
						</span>

						<a href="../login/login.php" class="txt3">
							Sign In Here
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<!-- JS -->
	<script src="../login/vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="../login/vendor/animsition/js/animsition.min.js"></script>
	<script src="../login/vendor/bootstrap/js/popper.js"></script>
	<script src="../login/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="../login/vendor/select2/select2.min.js"></script>
	<script src="../login/vendor/daterangepicker/moment.min.js"></script>
	<script src="../login/vendor/daterangepicker/daterangepicker.js"></script>
	<script src="../login/vendor/countdowntime/countdowntime.js"></script>
    <!-- <script src="../login/js/main.js"></script> -->
    <script src="register.js"></script>
    
</body>
</html>