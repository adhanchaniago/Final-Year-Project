<?php
//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

  // Initialize the session
  session_start();

  //Setup connection
  require_once "../dbconfig.php";

  //Check if the user is logged in, if not then redirect him to login page 
  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
      header("location: ../login/login.php");
      exit;
  }

  $username = $_SESSION["username"];
    $id = $_SESSION["id"];

  //For money user account
  $queryMoney = "select money,email from login where id = '$id'";
  $resultMoney = mysqli_query($link, $queryMoney);

  $rows = mysqli_fetch_assoc($resultMoney);
  $money = number_format((float)$rows['money'], 2, '.', ' ');
  $email = $rows['email'];
  
    //Update details user in database
    if(isset($_POST['UpdateUser'])){
        $username = $_POST['editusername'];
        $email = $_POST['editemail'];
        $queryUpdate = "UPDATE login SET username='$username', email='$email'where usertype = 'user' and id = '$id'";
        $update = mysqli_query($link, $queryUpdate);

        if($update){
            $_SESSION["username"] = $username;
        }
    }

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
          <li class="nav-item">
            <a class="nav-link" href="upload.php" style="margin-left:90px;"> Print </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="PrintHistory.php" style="margin-left:20px;"> Print History <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item dropdown" style="margin-left:610px;">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"> 
                <img src="../login/images/user.png" style="margin-right:10px;"> <?php echo $username ?> </a>
            <div class="dropdown-menu" style="margin-left:-50px;text-align:center;">
                <a class="dropdown-item" href="profile.php">My Profile</a>
              <a class="dropdown-item" href="#">Balance: RM<?php echo $money?></a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="../logout.php"><i class="fa fa-sign-out" aria-hidden="true" style="margin-right:5px;"></i> Log out </a>
            </div>
          </li>  
        </ul>  
      </div>
    </nav>

<!-- Form -->
<div class="row" style="margin:50px;margin-top:50px;"> 
    <div class="column">
      <form action="PrintHistory.php" method="POST" class="border border-dark p-4" 
        style="border-radius:10px;background-color:#FFFFFF; margin-left:80px;">
        <!--Profile-->
        <div class="form-group"> 
          <h5><img src="../login/images/person.png" style="margin-right:10px;"><b>My Profile</b></h5>
            <div class="card mb-4">
                <div class="card-header"><b>Account Details</b>
                    <style>
                        .btn {size:10px; padding:5px 10px;}
                    </style>
                    <button type="button" class="btn btn-primary btn-sm" style="margin-left:20px;" name="Edit" id="Edit"><i class="fa fa-edit mr-1"></i>Edit</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table>
                            <tr>
                                <td>
                                    <div>
                                        <label><img src="../login/images/user2.png" style="margin-right:10px;"><b>Name:</b> <?php echo $username ?></label></br>
                                        <label><img src="../login/images/mail.png" style="margin-right:10px;"><b>Email:</b> <?php echo $email ?></label></br>
                                        <label><img src="../login/images/money.png" style="margin-right:10px;"><b>Topup Balance: </b>RM<?php echo $money ?></label></br>
                                    </div> 
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div> 
      </form>
    </div>
</div>

    <!-- Modal Popup for button edit user details -->
    <div id="modalEdit" class="modal fade" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <form id="recordForm">
            <div class="modal-content" style="width:410px;">
                <div class="modal-header">
                    <h4 class="modal-title"><b>Edit Your Account</b></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <table>
                    <tr>
                        <td>   
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label><b>Name:</b> </label>   
                                    <input class="form-control form-control-sm" type="text" name="editusername" id="editusername">
                                </div> 
                                <div class="form-group col-md-7">
                                    <label><b>Email:</b> </label>   
                                    <input class="form-control form-control-sm" type="text" name="editemail" id="editemail">
                                </div> 
                            </div>          
                        </td>
                    </tr>
                </table>
                </div>
                <div class="modal-footer" style="margin-top:-20px;">
                    <button type="button" id="SaveDetails" class="btn btn-primary" data-dismiss="modal">Save</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    <!-- JS -->
    <script>
        $('#Edit').click(function(){
            var username = '<?php echo $username ?>';
            var email = '<?php echo $email ?>';

            $('#modalEdit').modal('show');
            $('#editusername').val(username);
            $('#editemail').val(email);
 
        });

        $('#SaveDetails').click(function(){
            var editusername = $('#editusername').val();
            var editemail = $('#editemail').val();

            $.ajax({
            type:'POST',
            url:'profile.php',
            data:{'UpdateUser':'UpdateUser', 'editusername': editusername, 'editemail':editemail},
            success:function(data){
                alert('Your Account Updated');
                location.reload();
            },
            error:function(){
                alert("Error");
            }
            });
        });
    </script>

</body>
</html>