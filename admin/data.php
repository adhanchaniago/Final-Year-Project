<?php
//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

    require_once "../dbconfig.php";

    //Check if the admin is logged in, if not then redirect to login page 
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login/login.php");
    exit;
    }

    $username = $_SESSION["username"];

    $sql  = "SELECT COUNT(user_id) as TotalOrder   
    FROM userorder";
    
    $sql2  = "SELECT COUNT(username) as TotalCustomer   
    FROM login where usertype='user'";

    $sql3 ="SELECT COUNT(username) as TotalVendor   
    FROM login where usertype='vendor'";


    $result = mysqli_query($link, $sql) or die (mysqli_error());
    $result2 = mysqli_query($link, $sql2) or die (mysqli_error());
    $result3 = mysqli_query($link, $sql3) or die (mysqli_error());

    $data = array(); 
    $data2 = array(); 
    $data3 = array(); 

		if ($result){
            if (mysqli_num_rows($result) > 0){
                while ($row = mysqli_fetch_array ($result)){
                    $data = $row['TotalOrder'];
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
                    $data3 = $row['TotalVendor'];
                }
			}else{
                $data3 = 'N/A';
            }
        }
    
    $total_order = $data;
    $total_customer = $data2; 
    $total_vendor = $data3;
?>