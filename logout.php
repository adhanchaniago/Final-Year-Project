<?php
//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

    require_once "./dbconfig.php";
    // Initialize the session
    session_start();
   
    //auto-delete file
    $id = $_SESSION["id"];
    
    $query1 = "SELECT fileName FROM userorder where StatusOrder = '' and user_id = '$id'";
    $result = mysqli_query($link, $query1);

    //Delete file in fileupload
    while($rows = mysqli_fetch_assoc($result)){
        $fileName = $rows['fileName'];
        $fileName = str_replace('../fileupload/', './fileupload/', $fileName);
        unlink($fileName);
    }
    
    //Delete filename in database
    $query = "DELETE FROM userorder where StatusOrder = '' and user_id = '$id'";
    mysqli_query($link, $query);

    // Unset all of the session variables
    $_SESSION = array();
    
    // Destroy the session.
    session_destroy();

        
    // Redirect to login page
    header("location: index.php");
    exit;
?>