<?php
//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

if(isset($_GET['fileName'])){

    $filename = $_GET['fileName'];
    echo $filename;
    $filepath = '../fileupload/'. $filename;
    if (file_exists($filepath)){
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filepath).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize('../fileupload/'. $filename));
        readfile('../fileupload/'.$filename);
    }else{
        echo "File not exist!";
    }
    exit;       
}

?>