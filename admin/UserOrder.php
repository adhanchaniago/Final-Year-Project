<?php
//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

//Initialize the session
session_start();
// Setup connection
require_once "../dbconfig.php";

//Check if the admin is logged in, if not then redirect him to login page 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login/login.php");
    exit;
}

$username = $_SESSION["username"];

//download file user
include 'download.php';


?>

<!DOCTYPE html>
<html lang="en">
    <head>  
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>E-Print System - Admin</title>
        
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
            </form> -->
            <!-- Navbar-->
            <ul class="navbar-nav ml-auto ml-md-50">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt" style="margin-right:5px;"></i> Log out </a>
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
                            <a class="nav-link" href="ListUser.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-address-book"></i></div>
                            List of Customer</a>
                            <a class="nav-link" href="UserOrder.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Customer Order</a>
                            <a class="nav-link" href="ListVendor.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div>
                            List of Vendor</a>
                            <a class="nav-link" href="VendorInfo.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Vendor Info</a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        System Administrator
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content"> 
                <main>
                    <div class="container-fluid">
                        <h2 class="mt-4">Customer Order</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Customer Order</li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header"><i class="fas fa-table mr-2"></i>List of Printing Order
                                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="export" style="margin-left:69%;"><i class="fas fa-download mr-1"></i> Export Table</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="OrderTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Document Name</th>
                                                <th>Customer Name</th>
                                                <th>Details</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
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

        <!-- Modal Popup for button Details -->
        <div id="modalDetails" class="modal fade">
            <div class="modal-dialog"> 
                <form id="recordForm" style="margin:40px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"><b>Customer Printing Preferences</b></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        <table  id="userorderTable">
                            <tr>
                            <td>
                            <div class="form-group">
                                <label class="control-label" id="colour">Colour:</label></br>     

                                <label class="control-label" id="sidespaper">Sides of Paper:</label></br>
                                            
                                <label class="control-label" id="laminate">Laminate:</label></br>                
                            
                                <label class="control-label" id="coverpage">Cover Page:</label></br>             
                            
                                <label class="control-label" id="pagesprint">Pages to Print:</label></br>  

                                <label class="control-label" id="slidespage">Slides per Page:</label></br> 

                                <label class="control-label" id="paperquality">Paper Quality:</label></br>  

                                <label class="control-label" id="binding">Binding:</label></br>   

                                <label class="control-label" id="copies">No. of Copies:</label></br>   

                                <a class="btn btn-primary btn-sm" id="download" href="#" style="margin-top:10px;">
                                    <i class="fas fa-download" style="margin-right:10px;"></i>Download</a>      
                            </div>
                            </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer" style="margin-top:-20px;">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
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
        <script src="dist/assets/demo/UserOrder.js"></script>
        <script src="download.php"></script>
    </body>
</html>
