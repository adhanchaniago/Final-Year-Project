<?php
//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

session_start();
//Setup connection
require_once "../../dbconfig.php";

//Check if the user is logged in, if not then redirect him to login page 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login/login.php");
    exit;
}

if(isset($_POST['ajaxcall'])){   
  $ajaxcall = $_POST['ajaxcall'];
  $username = $_SESSION["username"];
  $id = $_SESSION["id"];

  switch($ajaxcall){
    
    case "showmodaldata" :
      $VendorName = $_POST['viewdetail'];
      $query = "select * from vendor where VendorName = '$VendorName'";
      $result = mysqli_query($link, $query);
      $data = array();

      while($rows = mysqli_fetch_assoc($result)){
          $jsonArrayItem = array();
          $jsonArrayItem['VendorName'] = $rows['VendorName'];
          $jsonArrayItem['Location'] = 'Info: '.$rows['Location'];
          $jsonArrayItem['Image'] = '../login/images/'.$rows['Image'];
          $jsonArrayItem['Colour'] = 'Colour: RM'.number_format((float)$rows['Colour'], 2, '.', ' ');
          $jsonArrayItem['BlackWhite'] = 'Black & White: RM'.number_format((float)$rows['BlackWhite'], 2, '.', ' ');
          $jsonArrayItem['Laminate'] = 'Laminate: RM'.number_format((float)$rows['Laminate'], 2, '.', ' ');
          $jsonArrayItem['BindingTape'] = 'Binding Tape: RM'.number_format((float)$rows['BindingTape'], 2, '.', ' ');
          $jsonArrayItem['BindingComb'] = 'Binding Comb: RM'.number_format((float)$rows['BindingComb'], 2, '.', ' ');
          $jsonArrayItem['PlasticCover'] = 'Plastic Cover: RM'.number_format((float)$rows['PlasticCover'], 2, '.', ' ');
          $jsonArrayItem['PaperQuality70'] = 'Paper Quality (70gsm): RM'.number_format((float)$rows['PaperQuality70'], 2, '.', ' ');
          $jsonArrayItem['PaperQuality80'] = 'Paper Quality (80gsm): RM'.number_format((float)$rows['PaperQuality80'], 2, '.', ' ');

          array_push($data, $jsonArrayItem);
          }

          mysqli_close($link);
          echo json_encode($data);

    break;   

    case "uploadfile" :
        $target_dir = "../../fileupload/";
        $target_file = $target_dir.basename($_FILES["uploaded_file"]["name"]);
        $uploadOk = 1;
        $FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $message = array();
        $statusMsg = '';
      
        // Check if file already exists
        if (file_exists($target_file)) {
          $statusMsg = "Sorry, file already exists.";
          $uploadOk = 0;
        }
      
        // Check file size 10MB (10x1024x1024)
        if ($_FILES["uploaded_file"]["size"] > 10485760) {
          $statusMsg = "Sorry, your file is too large.";
          $uploadOk = 0;
        }
      
        // Allow certain file formats
        if($FileType != "pdf" && $FileType != "doc" && $FileType != "docx" && $FileType != "pptx" && $FileType != "ppt") {
          $statusMsg = "Sorry, only pdf, pptx, ppt, doc & docx files are allowed.";
          $uploadOk = 0;
        }
      
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
          // Display status message
            $data = array();
            $data['statusMsg']= $statusMsg;
            $data['uploadOk'] = $uploadOk;

        // if everything is ok, try to upload file
        } else {
          //file pdf
          if ($FileType == "pdf")
          {
            if (move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $target_file)) {
              //Insert file name into database
              $target_file = str_replace('../../fileupload/', '../fileupload/', $target_file);    
              $sql= "INSERT into userorder (fileName, user_id) VALUES ('$target_file', '$id')";         
              mysqli_query($link,$sql);
      
              $statusMsg = "File name ". basename( $_FILES["uploaded_file"]["name"]). " uploaded.";
              $_SESSION['filepreview'] =  $target_file;
            } else {
              $statusMsg = "Sorry, there was an error uploading your file.";
              $uploadOk = 0;
            }
          }else{
            if (move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $target_file)) {
              //convert file to pdf
              require_once '../cloudmersive/vendor/autoload.php';
          
              // Configure API key authorization: Apikey
              $config = Swagger\Client\Configuration::getDefaultConfiguration()->setApiKey('Apikey', '6094e768-a1e0-4e5b-8902-e1fac53d568b');
          
              $apiInstance = new Swagger\Client\Api\ConvertDocumentApi(  
                  new GuzzleHttp\Client(),
                  $config
              );
              $newfilename = pathinfo($target_file);
              $newfilename = $target_dir.$newfilename['filename'].'.'.'pdf';

              //for file format docx, doc, pptx, ppt convert to pdf
              try {
                if ($FileType == "docx"){
                  $result = $apiInstance->convertDocumentDocxToPdf($target_file);
                }
                elseif ($FileType == "doc"){
                  $result = $apiInstance->convertDocumentDocToPdf($target_file);
                }
                elseif ($FileType == "pptx"){
                  $result = $apiInstance->convertDocumentPptxToPdf($target_file);
                }
                else{
                  $result = $apiInstance->convertDocumentPptToPdf($target_file);
                }
            
                file_put_contents($newfilename, $result);
                //for delete docx file 
                unlink($target_file);
              } catch (Exception $e) {
                  echo 'Exception when calling ConvertDocumentApi->convertDocumentDocxToPdf: ', $e->getMessage(), PHP_EOL;
              }

              //Insert file name into database
              $newfilename = str_replace('../../fileupload/', '../fileupload/', $newfilename);    
              $sql= "INSERT into userorder (fileName, user_id) VALUES ('$newfilename', '$id')";         
              mysqli_query($link,$sql);
      
              $statusMsg = "File name ". basename( $_FILES["uploaded_file"]["name"]). " uploaded.";
              $_SESSION['filepreview'] =  $newfilename;
            } else {
              $statusMsg = "Sorry, there was an error uploading your file.";
              $uploadOk = 0;
            }
          }
            $data = array();
            $data['statusMsg']= $statusMsg;
            $data['uploadOk'] = $uploadOk;

        }      
        array_push($message, $data);
        echo json_encode($message);
        
    break;

    //for map.php
    case "locateMap":  
      $vendor = $_POST['VendorName'];

      $query = "SELECT lng, lat
      FROM vendor where VendorName = '$vendor'";

      $result = mysqli_query($link, $query) or die (mysqli_error());
      $jsonArray = array();

      while($row=mysqli_fetch_assoc($result)) {
          $jsonArrayItem = array();
          $jsonArrayItem['lng'] = $row['lng'];
          $jsonArrayItem['lat'] = $row['lat'];

          array_push($jsonArray, $jsonArrayItem);
      }
      mysqli_close($link);

      header('Content-type: application/json');
      echo json_encode($jsonArray);

      break;

  }
}
?>