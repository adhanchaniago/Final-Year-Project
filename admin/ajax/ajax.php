<?php
//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

session_start();
//Setup connection
require_once "../../dbconfig.php";

//Check if the admin is logged in, if not then redirect to login page 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login/login.php");
    exit;
}

if(isset($_POST['ajaxcall'])){
    
    $ajaxcall = $_POST['ajaxcall'];
    $username = $_SESSION["username"];
    switch($ajaxcall){
  
//ajax for VendorInfo.php
    //for view table
    case "vendortable" :         
        $query = "SELECT VendorName, UserName, Location, Colour, BlackWhite, Laminate, BindingTape, BindingComb, 
        PlasticCover, PaperQuality70, PaperQuality80
        FROM vendor";

        $result = mysqli_query($link, $query) or die (mysqli_error());
        $jsonArray = array();

        if ($result){     
            $no=1;
            while ($row = mysqli_fetch_array ($result)){          
                $jsonArrayItem = array();
                $jsonArrayItem['no'] = $no;
                $jsonArrayItem['PrintName'] = $row['VendorName'];
                $jsonArrayItem['Owner'] = $row['UserName'];
                $jsonArrayItem['Location'] = $row['Location'];
                //$jsonArrayItem['Image'] = $row['Image'];
                $jsonArrayItem['Details'] ='<button type="button" name="details" id="details" class="btn btn-sm btn-primary details" value="'.$row['UserName'].'"><i class="fas fa-info-circle mr-1"></i>Details</button>';
                $jsonArrayItem['Actions'] = '<button type="button" name="edit" id="edit" class="btn btn-sm btn-primary edit" value="'.$row['UserName'].'"><i class="fas fa-edit mr-1"></i>Edit</button>
                <button type="button" name="delete" id="delete" class="btn btn-sm btn-primary delete" value="'.$row['UserName'].'"><i class="fas fa-trash-alt mr-1"></i>Delete</button>';

                $no++;
                
                array_push($jsonArray, $jsonArrayItem);
            }
        }
        $data = array();
        $data['data'] = $jsonArray;

        mysqli_close($link);

        header('Content-type: application/json');
        echo json_encode($data);

    break;

    //for button details
    case "details" :
        $vendorname = $_POST['UserName'];

        $query = "SELECT VendorName, UserName, Location, Colour, BlackWhite, Laminate, BindingTape, BindingComb, 
        PlasticCover, PaperQuality70, PaperQuality80, Image
        FROM vendor where UserName = '$vendorname'";

        $result = mysqli_query($link, $query) or die (mysqli_error());
        $jsonArray = array();
        
        while($row=mysqli_fetch_assoc($result)) {
            $jsonArrayItem = array();
            $jsonArrayItem['colour'] = number_format((float)$row['Colour'], 2, '.', ' ');
            $jsonArrayItem['bw'] = number_format((float)$row['BlackWhite'], 2, '.', ' ');
            $jsonArrayItem['laminate'] = number_format((float)$row['Laminate'], 2, '.', ' ');
            $jsonArrayItem['bindingTape'] = number_format((float)$row['BindingTape'], 2, '.', ' ');
            $jsonArrayItem['bindingComb'] = number_format((float)$row['BindingComb'], 2, '.', ' ');
            $jsonArrayItem['coverpage'] = number_format((float)$row['PlasticCover'], 2, '.', ' ');
            $jsonArrayItem['paperquality70'] = number_format((float)$row['PaperQuality70'], 2, '.', ' ');
            $jsonArrayItem['paperquality80'] = number_format((float)$row['PaperQuality80'], 2, '.', ' ');

            array_push($jsonArray, $jsonArrayItem);
        }
        mysqli_close($link);

        header('Content-type: application/json');
        echo json_encode($jsonArray);

    break;

    //for button edit(view details)
    case "vendordetails":  
        $vendorname = $_POST['UserName'];

        $query = "SELECT VendorName, Location, UserName, Colour, BlackWhite, Laminate, BindingTape, BindingComb, 
        PlasticCover, PaperQuality70, PaperQuality80
        FROM vendor where UserName = '$vendorname'";

        $result = mysqli_query($link, $query) or die (mysqli_error());
        $jsonArray = array();      
  
        $rows = mysqli_fetch_assoc($result);
            $jsonArrayItem = array();
            $jsonArrayItem['VendorName'] = $rows['VendorName'];
            $jsonArrayItem['Location'] = $rows['Location'];
            $jsonArrayItem['UserName'] = $rows['UserName'];
            $jsonArrayItem['Colour'] = number_format((float)$rows['Colour'], 2, '.', ' ');
            $jsonArrayItem['BlackWhite'] = number_format((float)$rows['BlackWhite'], 2, '.', ' ');
            $jsonArrayItem['Laminate'] = number_format((float)$rows['Laminate'], 2, '.', ' ');
            $jsonArrayItem['BindingTape'] = number_format((float)$rows['BindingTape'], 2, '.', ' ');
            $jsonArrayItem['BindingComb'] = number_format((float)$rows['BindingComb'], 2, '.', ' ');
            $jsonArrayItem['PlasticCover'] = number_format((float)$rows['PlasticCover'], 2, '.', ' ');
            $jsonArrayItem['PaperQuality70'] = number_format((float)$rows['PaperQuality70'], 2, '.', ' ');
            $jsonArrayItem['PaperQuality80'] = number_format((float)$rows['PaperQuality80'], 2, '.', ' ');

            array_push($jsonArray, $jsonArrayItem);
    
        mysqli_close($link);
        echo json_encode($jsonArray);

    break;

    //for save updated vendor details
    case "UpdateVendor":
        $vusername= $_POST['VUserName']; //for username vendor old one

        $VendorName = $_POST['VendorName'];
        $Location = $_POST['Location'];
        $UserName = $_POST['UserName']; //for username vendor updated
        $Colour = $_POST['Colour'];
        $BlackWhite = $_POST['BlackWhite'];
        $Laminate = $_POST['Laminate'];
        $BindingTape = $_POST['BindingTape'];
        $BindingComb = $_POST['BindingComb'];
        $PlasticCover = $_POST['PlasticCover'];
        $PaperQuality70 = $_POST['PaperQuality70'];
        $PaperQuality80 = $_POST['PaperQuality80'];

        //Update details vendor in database
        $query = "UPDATE vendor SET VendorName='$VendorName', Location='$Location', UserName='$UserName', Colour='$Colour', 
        BlackWhite='$BlackWhite', Laminate='$Laminate', BindingTape='$BindingTape',BindingComb='$BindingComb', 
        PlasticCover='$PlasticCover' , PaperQuality70='$PaperQuality70',PaperQuality80='$PaperQuality80' 
        where UserName = '$vusername'";

        mysqli_query($link, $query) or die (mysqli_error());
        mysqli_close($link);

        echo "Details Updated!";

    break;

    //for button delete vendor
    case "DeleteVendor":
        $vusername= $_POST['VUserName']; //for username vendor old one

        $query = "Delete from vendor where UserName = '$vusername'";

        mysqli_query($link, $query) or die (mysqli_error());
        mysqli_close($link);

    break;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//ajax for ListVendor.php

    //for list of printing vendor from login
    case "ViewVendor":
        $query = "SELECT id, username, email, usertype FROM login where usertype = 'vendor'";

        $result = mysqli_query($link, $query) or die (mysqli_error());
        $jsonArray = array();

        if ($result){     
            $no=1;
            while ($row = mysqli_fetch_array ($result)){          
                $jsonArrayItem = array();
                $jsonArrayItem['no'] = $no;
                $jsonArrayItem['VendorName'] = $row['username'];
                $jsonArrayItem['Email'] = $row['email'];
                $jsonArrayItem['Action'] = '<button type="button" name="edit" id="edit" class="btn btn-sm btn-primary edit" value="'.$row['id'].'"><i class="fas fa-edit mr-1"></i>Edit</button>
                <button type="button" name="delete" id="delete" class="btn btn-sm btn-primary delete" value="'.$row['id'].'"><i class="fas fa-trash-alt mr-1"></i>Delete</button>';
                
                $no++;
                
                array_push($jsonArray, $jsonArrayItem);
            }
        }
        $data = array();
        $data['data'] = $jsonArray;

        mysqli_close($link);

        header('Content-type: application/json');
        echo json_encode($data);

    break;

    //for button edit from login
    case "EditLogin":
        $id = $_POST['id'];

        $query = "SELECT id, username, email, usertype, password FROM login 
        where usertype = 'vendor' and id = '$id'";

        $result = mysqli_query($link, $query) or die (mysqli_error());
        $jsonArray = array();
        
        while($row=mysqli_fetch_assoc($result)) {
            $jsonArrayItem = array();
            $jsonArrayItem['username'] = $row['username'];
            $jsonArrayItem['email'] = $row['email'];
            $jsonArrayItem['id'] = $row['id'];

            array_push($jsonArray, $jsonArrayItem);
        }
        mysqli_close($link);

        header('Content-type: application/json');
        echo json_encode($jsonArray);

    break;

    //for save updated vendor acc login
    case "UpdateVendorLogin":
        $id= $_POST['id']; 

        $username = $_POST['username']; 
        $email = $_POST['email'];

        //Update details vendor in database
        $query = "UPDATE login INNER JOIN vendor ON login.username = vendor.UserName 
        SET login.username='$username', login.email='$email', vendor.UserName='$username'
        where login.usertype = 'vendor' and login.id = '$id'";

        mysqli_query($link, $query) or die (mysqli_error());
        mysqli_close($link);

        echo "Details Updated!";

    break;

    //for button delete vendor account
    case "DeleteVendorLogin":
        $id= $_POST['id']; 

        $query = "DELETE login, vendor 
        from login 
        INNER JOIN vendor on login.username = vendor.UserName 
        where login.id = '$id'";

        mysqli_query($link, $query) or die (mysqli_error());
        mysqli_close($link);

    break;

    //for button add new vendor account
    case "AddVendor":
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmpass = $_POST['confirmpass'];
        $usernamecheck = strtolower($username);
        
		if(strpos($usernamecheck,'admin') !== false){
			echo "Cannot use admin as username!";
			break;
		}else{
			$sql = "SELECT id FROM login WHERE username = '$username'";
			$stmt = mysqli_query($link, $sql) or die (mysqli_error());

			if(mysqli_num_rows($stmt) == 1){
				echo "This username is already taken!";
				break;
			} else{
				$username = $_POST['username'];
			}
		}
		
		if(strlen($password) < 6){
			echo "Password must have atleast 6 characters!";
			break;
		}else{
			if($password != $confirmpass){
				echo "Password did not match!";
			break;
			}else{
				$hashpass = password_hash($password, PASSWORD_DEFAULT);
				$usertype = "vendor";
			}
		}

        $query = "INSERT into login (username, email, password, usertype) 
        values ('$username','$email', '$hashpass', '$usertype')";

        $query1 = "INSERT into vendor (UserName) values ('$username')";

        mysqli_query($link, $query) or die (mysqli_error());
        mysqli_query($link, $query1) or die (mysqli_error());
        mysqli_close($link);

        echo "New Vendor Added!";

    break;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//ajax for UserOrder.php

    //for table in userorder
    case "ordertable":         
        $query ="SELECT userorder.Id as id, userorder.fileName as fileName, login.username as username, time(userorder.DateTime) as ordertime,
                 DATE_FORMAT(date(userorder.DateTime),'%d-%m-%Y') as orderdate, userorder.StatusOrder as StatusOrder 
                FROM userorder INNER JOIN login on userorder.user_id = login.id order by StatusOrder ASC";

        $result = mysqli_query($link, $query) or die (mysqli_error());
        $jsonArray = array();

        if ($result){     
            $no=1;
            while ($row = mysqli_fetch_array ($result)){          
                $jsonArrayItem = array();
                $jsonArrayItem['no'] = $no;
                $jsonArrayItem['docName'] = str_replace('../fileupload/', '', $row['fileName']);
                $jsonArrayItem['userName'] = $row['username'];
                $jsonArrayItem['details'] = '<button type="button" name="orderdetails" id="orderdetails" value="'.$row['id'].'" class="btn btn-sm btn-primary orderdetails">Details</button>';
                $jsonArrayItem['date'] = $row['orderdate'];
                $jsonArrayItem['time'] = $row['ordertime'];
                $jsonArrayItem['status'] = $row['StatusOrder'];
                
                $no++;
                
                array_push($jsonArray, $jsonArrayItem);
            }
        }
        $data = array();
        $data['data'] = $jsonArray;

        mysqli_close($link);

        header('Content-type: application/json');
        echo json_encode($data);

    break;

    //for modal details in table userorder
    case "orderdetails" :
        $id = $_POST['id'];

        $query ="SELECT fileName, Colour, SidesPaper, Laminate, CoverPage, PagesToPrint, SlidesPerPage,
        PaperQuality, Binding, NoOfCopies FROM userorder where Id ='$id'"; 

        $result = mysqli_query($link, $query) or die (mysqli_error());
        $jsonArray = array();
    
        while($row=mysqli_fetch_assoc($result)) {
            $jsonArrayItem = array();
            $jsonArrayItem['Colour'] = $row['Colour'];
            $jsonArrayItem['SidesPaper'] = $row['SidesPaper'];
            $jsonArrayItem['Laminate'] = $row['Laminate'];
            $jsonArrayItem['CoverPage'] = $row['CoverPage'];
            $jsonArrayItem['PagesToPrint'] = $row['PagesToPrint'];
            $jsonArrayItem['SlidesPerPage'] = $row['SlidesPerPage'];
            $jsonArrayItem['PaperQuality'] = $row['PaperQuality'];
            $jsonArrayItem['Binding'] = $row['Binding'];
            $jsonArrayItem['NoOfCopies'] = $row['NoOfCopies'];
            $jsonArrayItem['fileName']= str_replace('../fileupload/', '', $row['fileName']);

            array_push($jsonArray, $jsonArrayItem);
        }
        mysqli_close($link);

        header('Content-type: application/json');
        echo json_encode($jsonArray);

    break;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//ajax for ListUser.php

    //for list of printing user from login
    case "ViewUser":
        $query = "SELECT id, username, email, usertype FROM login where usertype = 'user'";

        $result = mysqli_query($link, $query) or die (mysqli_error());
        $jsonArray = array();

        if ($result){     
            $no=1;
            while ($row = mysqli_fetch_array ($result)){          
                $jsonArrayItem = array();
                $jsonArrayItem['no'] = $no;
                $jsonArrayItem['Username'] = $row['username'];
                $jsonArrayItem['Email'] = $row['email'];
                $jsonArrayItem['Action'] = '<button type="button" name="edit" id="edit" class="btn btn-sm btn-primary edit" value="'.$row['id'].'"><i class="fas fa-edit mr-1"></i>Edit</button>
                <button type="button" name="delete" id="delete" class="btn btn-sm btn-primary delete" value="'.$row['id'].'"><i class="fas fa-trash-alt mr-1"></i>Delete</button>';
                
                $no++;
                
                array_push($jsonArray, $jsonArrayItem);
            }
        }
        $data = array();
        $data['data'] = $jsonArray;

        mysqli_close($link);

        header('Content-type: application/json');
        echo json_encode($data);

    break;

    //for button edit user from login
    case "EditUserLogin":
        $id = $_POST['id'];

        $query = "SELECT id, username, email, usertype, password FROM login 
        where usertype = 'user' and id = '$id'";

        $result = mysqli_query($link, $query) or die (mysqli_error());
        $jsonArray = array();
        
        while($row=mysqli_fetch_assoc($result)) {
            $jsonArrayItem = array();
            $jsonArrayItem['username'] = $row['username'];
            $jsonArrayItem['email'] = $row['email'];
            $jsonArrayItem['id'] = $row['id'];

            array_push($jsonArray, $jsonArrayItem);
        }
        mysqli_close($link);

        header('Content-type: application/json');
        echo json_encode($jsonArray);

    break;

    //for save updated user acc login
    case "UpdateUserLogin":
        $id= $_POST['id']; 

        $username = $_POST['username']; 
        $email = $_POST['email'];

        //Update details user in database
        $query = "UPDATE login SET username='$username', email='$email'
        where usertype = 'user' and id = '$id'";

        mysqli_query($link, $query) or die (mysqli_error());
        mysqli_close($link);

        echo "Details Updated!";

    break;

    //for button delete user account
    case "DeleteUserLogin":
        $id= $_POST['id']; 

        //Cek dalam userorder ada data user ke tak
        $query1 = "select user_id from userorder where user_id ='$id'";
        $result = mysqli_query($link, $query1);
        $rowcount=mysqli_num_rows($result); 

        
        if($rowcount>0){
            //Kalau ada, dia guna query ni
            $query = "DELETE login, userorder 
            from login 
            INNER JOIN userorder on login.id = userorder.user_id 
            where login.id = '$id'";
        }else{
            //Kalau tidak ada, dia guna query ni
            $query = "DELETE from login where id ='$id'";
        }

        //execute query
        mysqli_query($link, $query) or die (mysqli_error());
        mysqli_close($link);

    break;

    //for button add new user account
    case "AddUser":
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmpass = $_POST['confirmpass'];
        $usernamecheck = strtolower($username);
        
		if(strpos($usernamecheck,'admin') !== false){
			echo "Cannot use admin as username!";
			break;
		}else{
			$sql = "SELECT id FROM login WHERE username = '$username'";
			$stmt = mysqli_query($link, $sql) or die (mysqli_error());

			if(mysqli_num_rows($stmt) == 1){
				echo "This username is already taken!";
				break;
			} else{
				$username = $_POST['username'];
			}
		}
		
		if(strlen($password) < 6){
			echo "Password must have atleast 6 characters!";
			break;
		}else{
			if($password != $confirmpass){
				echo "Password did not match!";
			break;
			}else{
				$hashpass = password_hash($password, PASSWORD_DEFAULT);
				$usertype = "user";
			}
		}

        $query = "INSERT into login (username, email, password, usertype) 
        values ('$username','$email', '$hashpass', '$usertype')";

        mysqli_query($link, $query) or die (mysqli_error());
        mysqli_close($link);

        echo "New User Added!";

    break;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//ajax for chart

    case "piechart":
    
        $sql = "SELECT usertype, COUNT(usertype) as total 
        FROM login where usertype != 'admin' group by usertype";

        $result = mysqli_query($link, $sql) or die (mysqli_error());
        $jsonArray = array();
    
        if ($result){
            while ($row = mysqli_fetch_array ($result)){

                if ($row['usertype'] == 'user'){
                    $row['usertype'] = 'customer';
                }
              $jsonArrayItem = array();
              $jsonArrayItem['label'] = ucfirst($row['usertype']);
              $jsonArrayItem['value'] = ucfirst($row['total']);
      
              array_push($jsonArray, $jsonArrayItem);
            }
        }

        mysqli_close($link);    
        header('Content-type: application/json');
        echo json_encode($jsonArray);
        
    break;

    case "WeeklyCharts":

		$query="SELECT DAYNAME(userorder.DateTime) as DAYNAME, count(*) as TotalOrder
        from userorder 
        INNER JOIN vendor ON vendor.VendorName=userorder.VendorName
        where userorder.DateTime >= NOW() + INTERVAL -7 day AND userorder.DateTime <  NOW() + INTERVAL  0 day 
        group by DAYNAME 
        order BY (CASE DAYOFWEEK(userorder.DateTime) WHEN 1 THEN 7 ELSE DAYOFWEEK(userorder.DateTime) END)";

        $result = mysqli_query($link, $query) or die (mysqli_error());
        $jsonArray = array();

        while($row=mysqli_fetch_assoc($result)) {
            $jsonArrayItem = array();
            $jsonArrayItem['DAYNAME'] = $row['DAYNAME'];
            $jsonArrayItem['TotalOrder']= $row['TotalOrder'];

            array_push($jsonArray, $jsonArrayItem);
        }
        mysqli_close($link);

        header('Content-type: application/json');
        echo json_encode($jsonArray);

    break;

    case "areachart":

		$query="SELECT DAYOFMONTH(userorder.DateTime) as DAYNAME, count(*) as TotalOrder, MONTHNAME(userorder.DateTime) as MONTHNAME
        from userorder 
        INNER JOIN vendor ON vendor.VendorName=userorder.VendorName
        where MONTH(userorder.DateTime) = MONTH(CURRENT_DATE()) 
        AND YEAR(userorder.DateTime) = YEAR (CURRENT_DATE()) GROUP BY DAYNAME";

        $result = mysqli_query($link, $query) or die (mysqli_error());
        $jsonArray = array();

        while($row=mysqli_fetch_assoc($result)) {
            $jsonArrayItem = array();
            $jsonArrayItem['DAYNAME'] = $row['DAYNAME']." ".$row['MONTHNAME'];
            $jsonArrayItem['TotalOrder']= $row['TotalOrder'];

            array_push($jsonArray, $jsonArrayItem);
        }
        mysqli_close($link);

        header('Content-type: application/json');
        echo json_encode($jsonArray);

    break;

    }
}

?>