<?php
//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

session_start();
//Setup connection
require_once "../../dbconfig.php";

//Check if the vendor is logged in, if not then redirect to login page 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login/login.php");
    exit;
}

if(isset($_POST['ajaxcall'])){
    
    $ajaxcall = $_POST['ajaxcall'];
    $username = $_SESSION["username"];
    switch($ajaxcall){
    
    case "ordertable" :         
        //Using inner join (2 database) to use Vendor UserName for condition
        $query ="SELECT userorder.Id as id, userorder.fileName as fileName, login.username as username, 
                time(userorder.DateTime) as ordertime, DATE_FORMAT(date(userorder.DateTime),'%d-%m-%Y') as orderdate, 
                userorder.StatusOrder as StatusOrder 
                FROM userorder
                INNER JOIN login ON login.id = userorder.user_id
                INNER JOIN vendor ON vendor.VendorName=userorder.VendorName 
                where vendor.UserName = '$username' and userorder.StatusOrder = 'Pending'";

        $result = mysqli_query($link, $query) or die (mysqli_error());
        $jsonArray = array();

        if ($result){     
            $no=1;
            while ($row = mysqli_fetch_array ($result)){          
                $jsonArrayItem = array();
                $jsonArrayItem['no'] = $no;
                $jsonArrayItem['docName'] = str_replace('../fileupload/', '', $row['fileName']);
                $jsonArrayItem['userName'] = $row['username'];
                $jsonArrayItem['details'] = '<button type="button" name="details" id="details" value="'.$row['id'].'" class="btn btn-sm btn-primary details">Details</button>';
                $jsonArrayItem['date'] = $row['orderdate'];
                $jsonArrayItem['time'] = $row['ordertime'];
                $jsonArrayItem['status'] = $row['StatusOrder'];
                $jsonArrayItem['action'] = '<button type="button" name="procees" id="proceed" value="'.$row['id'].'" class="btn btn-sm btn-primary proceed">Proceed</button>';

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

    case "details" :
        $id = $_POST['id'];

        //Using inner join (2 database) to use Vendor UserName for condition
        $query ="SELECT userorder.fileName as fileName, userorder.Colour, userorder.SidesPaper, userorder.Laminate, userorder.CoverPage, 
                    userorder.PagesToPrint, userorder.SlidesPerPage, userorder.PaperQuality, userorder.Binding,
                    userorder.NoOfCopies, userorder.Id as id
                FROM userorder
                INNER JOIN vendor ON vendor.VendorName=userorder.VendorName where vendor.UserName = '$username'
                and userorder.Id ='$id'"; 

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
            $jsonArrayItem['id'] = $row['id'];

            array_push($jsonArray, $jsonArrayItem);
        }
        mysqli_close($link);

        header('Content-type: application/json');
        echo json_encode($jsonArray);

    break;

    case "updatestatusorder" :
        $id = $_POST['id'];

        //Update status in database userorder
        $query ="UPDATE userorder 
        INNER JOIN vendor ON vendor.VendorName = userorder.VendorName 
        set userorder.StatusOrder = 'Complete'  
        where userorder.Id = '$id' and vendor.UserName = '$username'";

        mysqli_query($link, $query) or die (mysqli_error());
        mysqli_close($link);

    break;
    
    case "historytable" :         
        //Using inner join (2 database) to use Vendor UserName for condition
        $query ="SELECT userorder.Id as id, userorder.fileName as fileName, login.username as username, 
                time(userorder.DateTime) as ordertime, DATE_FORMAT(date(userorder.DateTime),'%d-%m-%Y') as orderdate, 
                userorder.StatusOrder as StatusOrder 
                FROM userorder
                INNER JOIN login ON login.id = userorder.user_id
                INNER JOIN vendor ON vendor.VendorName=userorder.VendorName 
                where vendor.UserName = '$username' and userorder.StatusOrder = 'Complete'";

        $result = mysqli_query($link, $query) or die (mysqli_error());
        $jsonArray = array();

        if ($result){     
            $no=1;
            while ($row = mysqli_fetch_array ($result)){          
                $jsonArrayItem = array();
                $jsonArrayItem['no'] = $no;
                $jsonArrayItem['docName'] = str_replace('../fileupload/', '', $row['fileName']);
                $jsonArrayItem['userName'] = $row['username'];
                $jsonArrayItem['details'] = '<button type="button" name="details" id="details" value="'.$row['id'].'" class="btn btn-sm btn-primary details"><i class="fas fa-info-circle mr-1"></i>Details</button>';
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

    case "vendordetails" :   
        //select database vendor
        $query = "SELECT VendorName, lng, lat, Location, Colour, BlackWhite, Laminate, BindingTape, BindingComb, 
        PlasticCover, PaperQuality70, PaperQuality80
        FROM vendor where  username = '$username'";

        $result = mysqli_query($link, $query) or die (mysqli_error());
        $jsonArray = array();      
  
        $rows = mysqli_fetch_assoc($result);
            $jsonArrayItem = array();
            $jsonArrayItem['VendorName'] = $rows['VendorName'];
            $jsonArrayItem['Lng'] = $rows['lng'];
            $jsonArrayItem['Lat'] = $rows['lat'];
            $jsonArrayItem['Location'] = $rows['Location'];
            $jsonArrayItem['Colour'] = number_format((float)$rows['Colour'], 2, '.', ' ');
            $jsonArrayItem['BlackWhite'] = number_format((float)$rows['BlackWhite'], 2, '.', ' ');
            $jsonArrayItem['Laminate'] = number_format((float)$rows['Laminate'], 2, '.', ' ');
            $jsonArrayItem['BindingTape'] = number_format((float)$rows['BindingTape'], 2, '.', ' ');
            $jsonArrayItem['BindingComb'] = number_format((float)$rows['BindingComb'], 2, '.', ' ');
            $jsonArrayItem['PlasticCover'] = number_format((float)$rows['PlasticCover'], 2, '.', ' ');
            $jsonArrayItem['PaperQuality70'] = number_format((float)$rows['PaperQuality70'], 2, '.', ' ');
            $jsonArrayItem['PaperQuality80'] = number_format((float)$rows['PaperQuality80'], 2, '.', ' ');
            //$jsonArrayItem['Image'] = '../login/images/'.$rows['Image'];

            array_push($jsonArray, $jsonArrayItem);
    
        mysqli_close($link);
        echo json_encode($jsonArray);

    break;

    case "UpdateVendor" :   
        $VendorName = $_POST['VendorName'];
        $Lng = $_POST['Lng'];
        $Lat = $_POST['Lat'];
        $Location = $_POST['Location'];
        $Colour = $_POST['Colour'];
        $BlackWhite = $_POST['BlackWhite'];
        $Laminate = $_POST['Laminate'];
        $BindingTape = $_POST['BindingTape'];
        $BindingComb = $_POST['BindingComb'];
        $PlasticCover = $_POST['PlasticCover'];
        $PaperQuality70 = $_POST['PaperQuality70'];
        $PaperQuality80 = $_POST['PaperQuality80'];

        if(isset($_FILES["fileToUpload"]) && !empty($_FILES["fileToUpload"])){
            $fileToUpload = basename($_FILES['fileToUpload']['name']);

            //For upload image of vendor shop
            $target_dir = "../../login/images/";
            $target_file = $target_dir . basename($_FILES['fileToUpload']['name']);
            $FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $uploadOk = 1;

            if($FileType != "png" && $FileType != "jpg" && $FileType != "jpeg") {
                $statusMsg = "Sorry, only JPG, JPEG & PNG files are allowed.";
                $uploadOk = 0;
            }
                
            if (file_exists($target_file)) {
                $no = preg_replace('/[^0-9]/', '', $target_file);
                $no = (int)$no;
                $no = $no +1;
                $filename = pathinfo($target_file, PATHINFO_FILENAME);
                $filename = preg_replace('/[^a-zA-Z]/', '', $filename);
                $temp = explode(".", basename($_FILES['fileToUpload']['name']));
                $target_file = $target_dir.$filename.'_'.$no.'.'.end($temp);
                $fileToUpload = basename($target_file);
            }

            if($uploadOk !=0){
            move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file);

            //select database vendor
            $query = "UPDATE vendor SET VendorName='$VendorName', Lng='$Lng', Lat='$Lat', Location='$Location',Colour='$Colour', 
            BlackWhite='$BlackWhite', Laminate='$Laminate', BindingTape='$BindingTape',BindingComb='$BindingComb', 
            PlasticCover='$PlasticCover' , PaperQuality70='$PaperQuality70',PaperQuality80='$PaperQuality80', Image='$fileToUpload'
            where UserName = '$username'";

            mysqli_query($link, $query) or die (mysqli_error());
            mysqli_close($link);
        
            $statusMsg = 'Success';
            echo $statusMsg;
            }else{
                echo $statusMsg;
            }
        }else{
            $query = "UPDATE vendor SET VendorName='$VendorName', Lng='$Lng', Lat='$Lat', Location='$Location',Colour='$Colour', 
            BlackWhite='$BlackWhite', Laminate='$Laminate', BindingTape='$BindingTape',BindingComb='$BindingComb', 
            PlasticCover='$PlasticCover' , PaperQuality70='$PaperQuality70',PaperQuality80='$PaperQuality80'
            where UserName = '$username'";

            mysqli_query($link, $query) or die (mysqli_error());
            mysqli_close($link);
            $statusMsg = 'Success';
            echo $statusMsg;
        }

    break;

    case "WeeklyCharts":

		$query="SELECT DAYNAME(userorder.DateTime) as DAYNAME, count(*) as TotalOrder
        from userorder 
        INNER JOIN vendor ON vendor.VendorName=userorder.VendorName
        where userorder.DateTime >= NOW() + INTERVAL -7 day AND userorder.DateTime <  NOW() + INTERVAL  0 day 
        AND vendor.UserName = '$username' 
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

    }
}

?>