<?php
//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

    require_once "../dbconfig.php";

    //Check if the vendor is logged in, if not then redirect to login page 
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login/login.php");
    exit;
    }

    $username = $_SESSION["username"];

    $sql  = "SELECT login.username as username, count(userorder.user_id) as totalorder 
    FROM userorder
    INNER JOIN login ON login.id = userorder.user_id 
    INNER JOIN vendor ON vendor.VendorName=userorder.VendorName 
    where vendor.UserName = '$username'";
    
    $sql2  = "SELECT COUNT(DISTINCT userorder.user_id) as TotalCustomer   
    FROM userorder 
    INNER JOIN vendor ON vendor.VendorName=userorder.VendorName 
    where vendor.UserName = '$username'";

    $sql3 ="SELECT count(userorder.StatusOrder) as pending 
    FROM userorder
    INNER JOIN vendor ON vendor.VendorName=userorder.VendorName 
    where vendor.UserName = '$username' and userorder.StatusOrder = 'Pending'";

    $sql4 ="SELECT count(userorder.StatusOrder) as complete 
    FROM userorder
    INNER JOIN vendor ON vendor.VendorName=userorder.VendorName 
    where vendor.UserName = '$username' and userorder.StatusOrder = 'Complete'";

    $result = mysqli_query($link, $sql) or die (mysqli_error());
    $result2 = mysqli_query($link, $sql2) or die (mysqli_error());
    $result3 = mysqli_query($link, $sql3) or die (mysqli_error());
    $result4 = mysqli_query($link, $sql4) or die (mysqli_error());

    $data = array(); 
    $data2 = array(); 
    $data3 = array(); 
    $data4 = array(); 

		if ($result){
            if (mysqli_num_rows($result) > 0){
                while ($row = mysqli_fetch_array ($result)){
                    $data = $row['totalorder'];
                }
			}else{
                $data = 'N/A';
            }
        }

        if ($result2){
            if (mysqli_num_rows($result2) > 0){
                while ($row = mysqli_fetch_array ($result2)){
                    $data2 = $row['TotalCustomer'];
                }
			}else{
                $data2 = 'N/A';
            }
        }

        if ($result3){
            if (mysqli_num_rows($result3) > 0){
                while ($row = mysqli_fetch_array ($result3)){
                    $data3 = $row['pending'];
                }
			}else{
                $data3 = 'N/A';
            }
        }

        if ($result4){
            if (mysqli_num_rows($result4) > 0){
                while ($row = mysqli_fetch_array ($result4)){
                    $data4 = $row['complete'];
                }
			}else{
                $data4 = 'N/A';
            }
        }
    
    $total_order = $data;
    $total_customer = $data2; 
    $total_pending = $data3;
    $total_complete = $data4;
?>