<?php
//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

//Initialize the session
session_start();
// Setup connection
require_once "../dbconfig.php";

//Check if the user is logged in, if not then redirect to login page 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login/login.php");
    exit;
}

$username = $_SESSION["username"];
$id = $_SESSION["id"];

//For money user account
$queryMoney = "select money from login where id = '$id'";
$resultMoney = mysqli_query($link, $queryMoney);

$rows = mysqli_fetch_assoc($resultMoney);
$money = number_format((float)$rows['money'], 2, '.', ' ');

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title> E-Print System</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Icon -->
    <link rel="stylesheet" href="../login/fonts/material-icon/css/material-design-iconic-font.min.css">
    
    <!-- Image -->
    <link rel="icon" type="image/png" href="../login/images/printer.png"/>

    <!-- Main css -->
    <link rel="stylesheet" href="../user/css/bootstrap.min.css">
    <link rel="stylesheet" href="../user/upload.css">
    <link rel="stylesheet" href="../user/upload.less">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  
</head>
<body style="background-color:#F0FFFF;"> 
    <!-- Header in user page -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <a class="navbar-brand" style="margin-left:15px;"><img src="../login/images/print(1).png" style="margin-right:10px"> E-Print System </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarColor02">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="upload.php" style="margin-left:90px;"> Print <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="PrintHistory.php" style="margin-left:20px;"> Print History </a>
          </li>
          <li class="nav-item dropdown" style="margin-left:610px;">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"> 
                <img src="../login/images/user.png" style="margin-right:10px;"> <?php echo $username ?> </a>
            <div class="dropdown-menu" style="margin-left:-50px;text-align:center;">
              <a class="dropdown-item" href="profile.php">My Profile<?php ?></a>
              <a class="dropdown-item" href="#">Balance: RM<?php echo $money?></a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="../logout.php"><i class="fa fa-sign-out" aria-hidden="true" style="margin-right:5px;"></i> Log out </a>
            </div>
          </li>  
        </ul>  
      </div>
    </nav>

    <div class="zone">
      <div id="dropZ">
        <i class="fa fa-cloud-upload"></i>
          <h5 style="color:white;">Drag and drop your file here</h5>                  
          <span>OR</span>
            <div class="selectFile" style="margin-top:1px;"> 
                <label class="btn" for="fileUpload"> 
                    <input type="file" name="uploaded_file" id="uploaded_file"/> SELECT FILE
                </label>
            </div>     
          <p>File size limit : 10 MB</p>
      </div>   
    </div>
  
    <div id="Success" class="modal fade">
      <style>
        .btn {size:20px;padding: 10px 15px;}
        h5   {color:#000000;}
      </style>
      <div class="modal-dialog">
        <form action="payment.php" method="POST" id="recordForm1">
          <div class="modal-content">
           <div class="modal-header">
              <h4 class="modal-title"><b>Your Printing Document</b></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">  
                <label class="control-label"><h5 id="text"></h5></label>
              </div>
            </div>
            <div class="modal-footer">
								<button type="button" class="btn btn-primary" style="color:#FFFFFF;" name="SuccessUpload" onClick="" id="SuccessUpload" data-dismiss="modal">Close</button>
						</div>
          </div>
        </form>
      </div>
    </div>

      <!-- Modal -->
      <div class="modal fade" id="loading" data-backdrop="static" data-keyboard="false" tabindex="-1">
        <div class = "modal-body">
          <div class="text-center">
            <button class="btn btn-primary" type="button" disabled style="margin-top:99px;">
              Uploading...
              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            </button>
          </div>
        </div>
      </div> 

      <!-- JS -->
      <script src="../user/upload.js"></script>
</body>
</html>