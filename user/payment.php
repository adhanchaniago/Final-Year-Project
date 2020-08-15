<?php
//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

  // Initialize the session
  session_start();
  $vendor = $_SESSION['vendor'];
  $filepreview = $_SESSION['filepreview'];

  //Setup connection
  require_once "../dbconfig.php";

  //Check if the user is logged in, if not then redirect to login page 
  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
      header("location: ../login/login.php");
      exit;
  }

  $username = $_SESSION["username"];
  $id = $_SESSION["id"];
  $StatusOrder = 'Pending';
  $orderid = $_SESSION['orderid'];

  $query = "SELECT * from userorder where fileName = '$filepreview' and user_id = '$id'";
  $result = mysqli_query($link, $query);

  //Preference
  while($rows=mysqli_fetch_assoc($result))
  {
    $filename= str_replace('../fileupload/', '', $rows['fileName']);
    $pagetoprint = $rows['PagesToPrint'];
    $noofcopies = $rows['NoOfCopies'];
    $colour = $rows['Colour'];
    $laminate = $rows['Laminate'];
    $binding = $rows['Binding'];
    $coverpage = $rows['CoverPage'];
    $paperquality = $rows['PaperQuality'];
    $slideperpage = $rows['SlidesPerPage'];
  }

  if($colour == 'Yes'){
    $col = 1;
    $bw = 0;
  }else{
    $bw = 1;
    $col = 0;
  }

  if($laminate == 'Yes'){
    $lam = 1;
  }else{
    $lam = 0;
  }

  if($binding == 'comb'){
    $comb = 1;
    $tape = 0;
  }else if ($binding == 'tape'){
    $comb = 0;
    $tape = 1;
  }else{
    $comb = 0;
    $tape = 0;
  }

  if($coverpage == 'Yes'){
    $covpage = 1;
  }else{
    $covpage = 0;
  }

  if($paperquality == '80 gsm'){
    $gsm80 = 1;
    $gsm70 = 0;
  }else{
    $gsm80 = 0;
    $gsm70 = 1;
  }

  //Price
  $queryPayment = "SELECT Colour, BlackWhite, Laminate, BindingTape, BindingComb, PlasticCover, 
  PaperQuality70, PaperQuality80 
  FROM vendor where VendorName = '$vendor'";

  $resultPayment = mysqli_query($link, $queryPayment);
 
  while($rows=mysqli_fetch_assoc($resultPayment))
  {
    $Colour = number_format((float)$rows['Colour'], 2, '.', ' ');
    $BlackWhite = number_format((float)$rows['BlackWhite'], 2, '.', ' ');
    $Laminate = number_format((float)$rows['Laminate'], 2, '.', ' ');
    $BindingTape = number_format((float)$rows['BindingTape'], 2, '.', ' ');
    $BindingComb = number_format((float)$rows['BindingComb'], 2, '.', ' ');
    $PlasticCover = number_format((float)$rows['PlasticCover'], 2, '.', ' ');
    $PaperQuality70 = number_format((float)$rows['PaperQuality70'], 2, '.', ' ');
    $PaperQuality80 = number_format((float)$rows['PaperQuality80'], 2, '.', ' ');
  }

  if(strpos($pagetoprint, '-')== true){
    $pagetoprint = preg_replace('/\s/', '', $pagetoprint);
    $rangeno = explode('-', $pagetoprint);
    $pagetoprint = $rangeno[1] - $rangeno[0] + 1;
  }

  $noofpage = $pagetoprint / $slideperpage;

  $total =(($col*($Colour + ($gsm70*$PaperQuality70) + ($gsm80*$PaperQuality80))*$noofpage)+
  ($bw*($BlackWhite + ($gsm70*$PaperQuality70) + ($gsm80*$PaperQuality80))*$noofpage) + ($Laminate*$lam)+
  ($BindingTape*$tape) + ($BindingComb*$comb) + ($PlasticCover*$covpage))*$noofcopies;

  $unitcost = ($total)/$noofcopies; 

  //For money user account
  $queryMoney = "SELECT money from login where id = '$id'";
  $resultMoney = mysqli_query($link, $queryMoney);

  $rows = mysqli_fetch_assoc($resultMoney);
  $money = number_format((float)$rows['money'], 2, '.', ' ');

  $DonePay = $money - $total;
  //Payment 
  if(isset($_POST['DonePay'])){
    $bal = "UPDATE login SET money = '$DonePay' where id = '$id'";
    mysqli_query($link, $bal);

    $queryOrder = "UPDATE userorder set StatusOrder = '$StatusOrder' where fileName = '$filepreview' and Id = '$orderid'";
    mysqli_query($link, $queryOrder);
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
        <object width="400px" height="400px" style="border: 1px solid black;" type="application/pdf" data="<?php echo $filepreview; ?>#zoom=1000&scrollbar=0&toolbar=0&navpanes=0" id="pdf_content" style="pointer-events: none;">
            <p>Insert your error message here, if the PDF cannot be displayed.</p>
        </object>
    </div>

    <div class="column">
    <form action="payment.php" method="POST"  class="border border-dark p-4" style="margin-top:35px;margin-left:80px;border-radius:10px;background-color:#FFFFFF;">
        <!--Purchase Receipt-->
        <h5><img src="../login/images/payment.png" style="margin-right:10px;"><b>Confirm Purchase</b></h5>
        <table class="table" style="width:500px">
            <thead>
            <tr>
                <th>Document</th>
                <th>Pages</th>
                <th>Qty</th>
                <th style="text-align:center">Unit Cost</th>
                <th style="text-align:center">Total</th>
                <th style="text-align:center">Details</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?php echo $filename;?></td>
                <td><?php echo $pagetoprint;?></td>
                <td><?php echo $noofcopies;?></td>
                <td>RM<?php echo  number_format((float)$unitcost, 2, '.' , ' ');?></td>
                <td>RM<?php echo  number_format((float)$total, 2, '.' , ' ');?></td>
                <td><button class="btn btn-primary btn-sm" type="button" name="DetailFile" id="DetailFile" style="margin-left:5px;">
                <i class="fa fa-info-circle" aria-hidden="true" style="margin-right:5px;"></i>View Details</button></td>
            </tr>
            </tbody>
        </table>
        <div class="form-group" style="margin:100px; margin-top:40px;">
            <!-- Return back to map -->
            <button type="reset" class="btn btn-primary float-left" style="margin-left:-90px;" 
            onClick="window.location.replace('map.php')"><i class="fa fa-chevron-left" aria-hidden="true" style="margin-right:5px;"></i>Go Back</button>
            
            <!-- Save to database and proceed to pay -->
            <button type="button" name="Pay" id="Pay" class="btn btn-primary float-right" style="margin-right:-90px;">Proceed to Payment<i class="fa fa-chevron-right" aria-hidden="true" style="margin-left:7px;"></i></button>
        </div>
    </form>
    </div>
  </div> 

  <div id="DetailModal" class="modal fade">
      <div class="modal-dialog">
        <form id="recordForm" style="margin:50px;">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><b>Your Printing Preferences</b></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <table>
              <tr>
              <td>
              <div class="form-group">
                <label class="control-label"><b>Colour:</b> <?php echo $colour;?></label></br>     

                <label class="control-label"><b>Slides per page:</b> <?php echo $slideperpage;?></label></br>
                            
                <label class="control-label"><b>Laminate:</b> <?php echo $laminate;?></label></br>                
              
                <label class="control-label"><b>Binding:</b> <?php echo $binding;?></label></br>             
              
                <label class="control-label"><b>Plastic Cover:</b> <?php echo $coverpage;?></label></br>         
              
                <label class="control-label"><b>Paper Quality:</b> <?php echo $paperquality;?></label></br>          
              </div>
              </td>
              </tr>
            </table>
            </div>
            <div class="modal-footer">
								<button type="button" class="btn btn-primary" data-dismiss="modal" >Close</button>
						</div>
          </div>
        </form>
      </div>
    </div>

    <div id="DetailModalPay" class="modal fade">
      <div class="modal-dialog">
        <form action="payment.php" method="POST" id="recordForm1" style="margin:50px;">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><b>Printing Payment</b></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <table>
              <tr>
              <td>
              <div class="form-group">  
                <label class="control-label"><b>Your Account:</b> RM<?php echo number_format((float)$rows['money'], 2, '.' , ' ');?></label></br>                                  
                
                <label class="control-label"><b>Total Payment of Printing:</b> RM<?php echo  number_format((float)$total, 2, '.' , ' ');?></label></br>
              </div>
              </td>
              </tr>
            </table>
            </div>
            <div class="modal-footer">
								<button type="button" id="DonePay" class="btn btn-primary" data-dismiss="modal">Pay<i class="fa fa-check" aria-hidden="true" style="margin-left:5px;"></i></button>
						</div>
          </div>
        </form>
      </div>
    </div>

    <div id="Success" class="modal fade">
      <div class="modal-dialog">
        <form action="payment.php" method="POST" id="recordForm1" style="margin:50px;">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><b>Printing Payment</b></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">  
              <label class="control-label"><h3>Success<img src="../login/images/tick.png" style="margin-left:10px;"></h3></label></br>
              <label class="control-label">Your Balance is RM<?php echo  number_format((float)$DonePay, 2, '.' , ' ');?></label></br>
              </div>
            </div>
            <div class="modal-footer">
								<button type="button" class="btn btn-primary" onClick="window.location.replace('PrintHistory.php')" data-dismiss="modal">Close</button>
						</div>
          </div>
        </form>
      </div>
    </div>

    <script>
        $('#DonePay').click(function(){
          $.ajax({
            type:'POST',
            url:'payment.php',
            data:{'DonePay':'DonePay'},
            success:function(data){
              $('#Success').modal('show');	
            }
          });
        });

        $('#DetailFile').click(function(){
            $('#DetailModal').modal('show');		
        });
    
        $('#Pay').click(function(){
            $('#DetailModalPay').modal('show');		
        });
    </script>

</body>
</html>