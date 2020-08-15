<?php
//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

    //Initialize the session
    session_start();
    // Setup connection
    require_once "../dbconfig.php";

    //Check if the vendor is logged in, if not then redirect him to login page 
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: ../login/login.php");
        exit;
    }

    $username = $_SESSION["username"];

    //Vendor Information
    $query = "SELECT VendorName, lng, lat, Location, Colour, BlackWhite, Laminate, BindingTape, BindingComb, 
    PlasticCover, PaperQuality70, PaperQuality80, Image
    FROM vendor where  username = '$username'";

    $result = mysqli_query($link, $query);
    
    while($rows=mysqli_fetch_assoc($result))
    {   
        $VendorName = $rows['VendorName'];
        $Lng = $rows['lng'];
        $Lat = $rows['lat'];
        $Location = $rows['Location'];
        $Colour = number_format((float)$rows['Colour'], 2, '.', ' ');
        $BlackWhite = number_format((float)$rows['BlackWhite'], 2, '.', ' ');
        $Laminate = number_format((float)$rows['Laminate'], 2, '.', ' ');
        $BindingTape = number_format((float)$rows['BindingTape'], 2, '.', ' ');
        $BindingComb = number_format((float)$rows['BindingComb'], 2, '.', ' ');
        $PlasticCover = number_format((float)$rows['PlasticCover'], 2, '.', ' ');
        $PaperQuality70 = number_format((float)$rows['PaperQuality70'], 2, '.', ' ');
        $PaperQuality80 = number_format((float)$rows['PaperQuality80'], 2, '.', ' ');
        $Image = $rows['Image'];
    }
  
?>

<!DOCTYPE html>
<html lang="en">
    <head>  
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>E-Print System - Vendor</title>
        
        <!-- CSS -->
        <link href="dist/css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
        
        <!-- Image -->
        <link rel="icon" type="image/png" href="../login/images/printer.png"/>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="dashboard.php"><img src="../login/images/print(1).png" style="margin-right:10px">
            E-Print System</a><button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search
            <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" />
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>-->
            <!-- Navbar-->
            <ul class="navbar-nav ml-auto ml-md-50">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="Profile.php">My Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt" style="margin-right:5px;"></i> Log out </a>
                    </div>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="dashboard.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard</a>
                            
                            <div class="sb-sidenav-menu-heading">Extra</div>
                            <a class="nav-link" href="Profile.php">
                                <div class="sb-nav-link-icon"><i class="far fa-address-card"></i></div>
                            Profile</a>
                            <a class="nav-link" href="UserOrder.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Customer Order</a>
                            <a class="nav-link" href="PrintHistory.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Print History</a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        Printing Vendor
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content"> 
                <main>
                    <div class="container-fluid">
                        <h2 class="mt-4">Profile</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header"><i class="fas fa-address-card mr-2"></i>Information
                                <button type="button" class="btn btn-primary btn-sm" style="margin-left:80%;" name="Edit" id="Edit"><i class="fas fa-edit mr-1"></i>Edit</button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table>
                                        <tr>
                                            <td>
                                                <div style="margin-left:20px;margin-top:-40px;">
                                                    <label><i class="fas fa-user-tie mr-2"></i><b>Company Name:</b> <?php echo $VendorName ?></label></br>
                                                    <label><i class="fas fa-map-marker-alt mr-2"></i><b>Location:</b> <?php echo $Location ?></label></br>
                                                    <label><i class="fas fa-globe-asia mr-2"></i><b>Latitude:</b> <?php echo $Lat ?></label>
                                                    <label><b>Longitude:</b> <?php echo $Lng ?></label></br>
                                                    <div style="margn-left:20px;margin-top:10px;">
                                                        <img src="<?php echo '../login/images/'.$Image;?>" width='300' height='200' style='border-radius:10px;' alt=' <?php echo $VendorName ?>'>
                                                    </div>   
                                                </div> 
                                            </td>
                                            <td>
                                                <label style="margin-left:40%;"><i class="fas fa-tags mr-2"></i><b>Printing Price</b> </label>
                                                <div style="margin-left:200px;">
                                                    <p><b>Colour:</b> RM<?php echo $Colour ?></p> 
                                                    <p><b>Black & White:</b> RM<?php echo $BlackWhite ?></p> 
                                                    <p><b>Laminate:</b> RM<?php echo $Laminate ?></p> 
                                                    <p><b>Binding Tape:</b> RM<?php echo $BindingTape ?></p> 
                                                    <p><b>Binding Comb:</b> RM<?php echo $BindingComb ?></p> 
                                                    <p><b>Plastic Cover:</b> RM<?php echo $PlasticCover ?></p> 
                                                    <p><b>Paper Quality (70gsm):</b> RM<?php echo $PaperQuality70 ?></p> 
                                                    <p><b>Paper Quality (80gsm):</b> RM<?php echo $PaperQuality80 ?></p> 
                                                </div>  
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Alia Website 2020</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <!-- Modal Popup for button edit vendor details -->
        <div id="modalEdit" class="modal fade" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog">
                <form id="recordForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><b>Edit Vendor Profile Details</b></h4>
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
                                        <label><b>Company Name:</b> </label>   
                                        <input class="form-control form-control-sm" type="text" name="VendorName" id="VendorName">
                                    </div> 
                                    <div class="form-group col-md-6">
                                        <label><b>Location:</b> </label>   
                                        <input class="form-control form-control-sm" type="text" name="Location" id="Location">
                                    </div> 
                                    <div class="form-group col-md-6">
                                        <label><b>Latitude:</b> </label>   
                                        <input class="form-control form-control-sm" type="text" name="Lat" id="Lat">
                                    </div> 
                                    <div class="form-group col-md-6">
                                        <label><b>Longitude:</b> </label>   
                                        <input class="form-control form-control-sm" type="text" name="Lng" id="Lng">
                                    </div> 
                                    <div class="form-group col-md-6">
                                        <label><b>Colour:</b> </label>    
                                        <input class="form-control form-control-sm" type="text" name="Colour" id="Colour">
                                    </div> 
                                    <div class="form-group col-md-6">
                                        <label><b>Black & White:</b> </label>  
                                        <input class="form-control form-control-sm" type="text" name="BlackWhite" id="BlackWhite">                                       
                                    </div> 
                                    <div class="form-group col-md-6">   
                                        <label><b>Laminate:</b> </label>  
                                        <input class="form-control form-control-sm" type="text" name="Laminate" id="Laminate">                            
                                    </div> 
                                    <div class="form-group col-md-6">
                                        <label><b>Binding Tape:</b> </label>
                                        <input class="form-control form-control-sm" type="text" name="BindingTape" id="BindingTape">
                                    </div> 
                                    <div class="form-group col-md-6">  
                                        <label><b>Binding Comb:</b> </label>     
                                        <input class="form-control form-control-sm" type="text" name="BindingComb" id="BindingComb">                                     
                                    </div> 
                                    <div class="form-group col-md-6">   
                                        <label><b>Plastic Cover:</b> </label>  
                                        <input class="form-control form-control-sm" type="text" name="PlasticCover" id="PlasticCover">                                 
                                    </div> 
                                    <div class="form-group col-md-6">  
                                        <label><b>Paper Quality (70gsm):</b> </label> 
                                        <input class="form-control form-control-sm" type="text" name="PaperQuality70" id="PaperQuality70"> 
                                    </div> 
                                    <div class="form-group col-md-6">   
                                        <label><b>Paper Quality (80gsm):</b> </label> 
                                        <input class="form-control form-control-sm" type="text" name="PaperQuality80" id="PaperQuality80">             
                                    </div> 
                                    <div class="form-group col-md-6">   
                                        <label><b>Select image to upload:</b> </label> 
                                        <input type="file" name="fileToUpload" id="fileToUpload">             
                                    </div> 
                                </div>          
                            </td>
                        </tr>
                    </table>
                    </div>
                    <div class="modal-footer" style="margin-top:-20px;">
                        <button type="button" id="LocateMe" class="btn btn-primary" style="margin-right:205px;">My Location</button>
                        <input type="hidden" name="ajaxcall" id="action" value=""/>
                        <button type="button" id="SaveDetails" class="btn btn-primary" data-dismiss="modal">Save</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
                </form>
            </div>
        </div>

        <!-- Modal Popup for success save -->
        <div id="Success" class="modal fade">
        <div class="modal-dialog">
            <form action="payment.php" method="POST" id="recordForm1" style="margin:50px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><b>Printing Details</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">  
                            <label class="control-label"><h5>Successfully Save<img src="../login/images/tick.png" style="margin-left:10px;"></h5></label></br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onClick="window.location.replace('Profile.php')" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="dist/js/scripts.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="dist/assets/demo/Profile.js"></script>

    </body>
</html>
