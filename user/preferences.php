<?php
//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

  // Initialize the session
  session_start();
  $filepreview = $_SESSION['filepreview'];

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
  $queryMoney = "select money from login where id = '$id'";
  $resultMoney = mysqli_query($link, $queryMoney);

  $rows = mysqli_fetch_assoc($resultMoney);
  $money = number_format((float)$rows['money'], 2, '.', ' ');
  
  //For file preview
  $path = $_SESSION['filepreview'];
  $totalPages = countPages($path);
    
  function countPages($path) {
    $pdftext = file_get_contents($path);
    $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
    return $num;
  }

  if (isset($_POST['submit'])){
    
    if ($_POST['PagesToPrint'] == "EnterRange"){
          $PagesToPrint  =$_POST['inputRange'];
     } else{
          $PagesToPrint  =$_POST['PagesToPrint'];
    }

    if (isset($_POST['Colour'])){
      $_POST['Colour'] = 'Yes';
    }else{
      $_POST['Colour'] = 'No';
    }

    if (isset($_POST['SidesPaper'])){
      $_POST['SidesPaper'] = 'Yes';
    }else{
      $_POST['SidesPaper'] = 'No';
    }
    
    if (isset($_POST['Laminate'])){
      $_POST['Laminate'] = 'Yes';
    }else{
      $_POST['Laminate'] = 'No';
    }

    if (isset($_POST['CoverPage'])){
      $_POST['CoverPage'] = 'Yes';
    }else{
      $_POST['CoverPage'] = 'No';
    }

    $Colour        =$_POST['Colour'];
    $SidesPaper    =$_POST['SidesPaper'];
    $Laminate      =$_POST['Laminate'];
    $CoverPage     =$_POST['CoverPage']; 

    $SlidesPerPage =$_POST['SlidesPerPage'];
    $PaperQuality  =$_POST['PaperQuality'];
    $Binding       =$_POST['Binding'];
    $NoOfCopies    =$_POST['quantity'];

    $sql = "UPDATE userorder SET DateTime = now(), Colour = '$Colour', SidesPaper = '$SidesPaper', Laminate = '$Laminate',
    CoverPage = '$CoverPage', PagesToPrint= '$PagesToPrint', SlidesPerPage = '$SlidesPerPage', PaperQuality = '$PaperQuality',
    Binding = '$Binding', NoOfCopies = '$NoOfCopies'
    WHERE user_id ='$id' and fileName = '$filepreview'";

    mysqli_query($link,$sql);

    if(mysqli_query($link, $sql)){
      $sql1 = "select Id from userorder where user_id ='$id' and fileName = '$filepreview'";
      $result= mysqli_query($link,$sql1);
      $row = mysqli_fetch_array ($result);

      $orderid = $row['Id'];

      $_SESSION['orderid'] = $orderid;
      
      echo "<script>alert('Your printing preferences successfully save.');
      window.location.replace('map.php')</script>"; 
      } else{
            echo "<script>alert('Sorry, there was an error.')</script>";
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
  <div class="row" style="margin:50px;margin-top:20px;"> 

    <div class="column" id="pdf">
        <!--Document Viewer-->
        <h5><img src="../login/images/preview.png" style="margin-right:10px;"><b>File Preview</b></h5>

        <object width="420px" height="400px" style="border: 1px solid black;" type="application/pdf" 
          data="<?php echo $filepreview;?>#zoom=1000&scrollbar=0&toolbar=0&navpanes=0" id="pdf_content" style="pointer-events: none;">
            <p>Insert your error message here, if the PDF cannot be displayed.</p>
        </object>
        
    </div>

    <div class="column">
    <form action="preferences.php" method="POST" class="border border-dark p-4" style="margin-left:120px;border-radius:10px;background-color:#FFFFFF;">
        <!--Preferences-->
          <div class="form-group"> 
            <h5><img src="../login/images/preferences.png" style="margin-right:10px;"><b>Printing Preferences</b></h5>
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" id="Colour" name="Colour" checked="">
                  <label class="custom-control-label" for="Colour"> Colour printing </label>
                </div>   
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" id="SidesPaper" name="SidesPaper" checked="">
                  <label class="custom-control-label" for="SidesPaper"> Print on both sides of paper </label>
                </div>
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" id="Laminate" name="Laminate" checked="">
                  <label class="custom-control-label" for="Laminate"> Laminate </label>
                </div>
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" id="CoverPage" name="CoverPage" checked="">
                  <label class="custom-control-label" for="CoverPage"> Include plastic cover </label>
                </div>
                <div class="form-group" style="margin-top:10px;"> Pages to print
                  <div class="custom-control custom-radio">
                    <input type="radio" id="customRadio1" name="PagesToPrint"  value="<?php echo $totalPages;?>" class="custom-control-input" checked>
                    <label class="custom-control-label" for="customRadio1">All</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input type="radio" id="customRadio2" name="PagesToPrint" value="EnterRange" class="custom-control-input">
                    <label class="custom-control-label" for="customRadio2">Pages</label>
                    <input type="text" placeholder="" id="inputRange" name="inputRange" style="margin-left:10px;">
                  </div>
                </div>
                <div class="form-group" style="margin-top:10px;">
                  <label for="SlidesPerPage">Slides per page</label>
                  <select class="form-control" id="SlidesPerPage" name="SlidesPerPage">
                    <option>1</option>
                    <option>2</option>
                    <option>4</option>
                    <option>6</option>
                    <option>8</option>
                  </select>
                </div>  
                <div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons"> Paper Quality
                  <label class="btn btn-primary" style="margin-left:20px;">
                    <input type="radio" name="PaperQuality" id="option1" value="70 gsm" autocomplete="off" checked=""> 70 gsm
                  </label>
                  <label class="btn btn-primary active">
                    <input type="radio" name="PaperQuality" id="option2" value="80 gsm" autocomplete="off"> 80 gsm
                  </label>
                </div></br>
                <div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons" style="margin-top:15px;"> Binding
                  <label class="btn btn-primary" style="margin-left:20px;">
                    <input type="radio" name="Binding" id="option1" value="none" autocomplete="off" checked=""> None
                  </label>
                  <label class="btn btn-primary active">
                    <input type="radio" name="Binding" id="option2" value="comb" autocomplete="off"> Comb
                  </label>
                  <label class="btn btn-primary">
                    <input type="radio" name="Binding" id="option3" value="tape" autocomplete="off"> Tape
                  </label>
                </div>
                <div class="form-group" style="margin-top:20px;">
                    <label for="Copies">No. of copies</label>
                        <input type='button' value='-' class='qtyminus' field='quantity' style="margin-left:20px;" />
                        <input type='text' name='quantity' value='1' class='qty' style="text-align:center;"/>
                        <input type='button' value='+' class='qtyplus' field='quantity' />
                </div> 
                <div class="form-group" style="margin-bottom:50px;">
                    <!-- Delete all and back to upload file -->
                    <button type="reset" class="btn btn-primary float-right"  onClick="window.location.replace('upload.php')"> <i class="fa fa-trash" aria-hidden="true"></i> Clear</button>
                    
                    <!-- Save to database and proceed to choose vendor -->
                    <button type="submit" name="submit" class="btn btn-primary float-right" style="margin-right:10px;"> <i class="fa fa-check" aria-hidden="true"></i> Continue</button>
                </div>
          </div> 
    </form>
    </div>
  </div>

  <!-- JS -->
  <script src="../user/copies.js"></script>
  <script>
      $(document).ready(function () {
        $("#inputRange").prop("disabled",true);
        $(".custom-control-input").click(function() {
          $("#inputRange").prop("disabled",true);
          if ($("input[name=PagesToPrint]:checked").val() == "EnterRange") {
            $("#inputRange").prop("disabled", false);
            }       
        });
      });
  </script>
</body>
</html>