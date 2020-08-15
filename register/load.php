<?php
//This is my Final Year Project (E-Print System in UNIMAS)
//Made by Nur Alia Binti Mohd Yusof (57131)

require_once "../dbconfig.php";

// Processing form data when form is submitted
if(isset($_POST['signup'])){
    $username = $email = $password = $confirm_password  = "";
    $username_err = $email_err = $password_err = $confirm_password_err = ""; 

    $data = array();
    $error= array();
    // Validate username
    if(empty(trim($_POST["username"]))){
        $error['user_error'] = "Please enter username";
        $username_err = $error['user_error'];
     
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM login WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $error['user_error'] = "This username already taken";
                    $username_err = $error['user_error'];
       
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }       
        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Validate email
    if(empty(trim($_POST["email"]))){
        $error['email_err'] = "Please enter email";
        $email_err = $error['email_err'];
     
    } else {
        $email = trim($_POST["email"]);
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email_err'] = "Invalid email format";
        $email_err = $error['email_err'];
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $error['password_err'] = "Please enter password";
        $password_err = $error['password_err'];


    } elseif(strlen(trim($_POST["password"])) < 6){
        $error['password_err'] = "Password must have atleast 6 characters";
        $password_err = $error['password_err'];
 
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $error['confirm_password_err'] = "Please confirm password";
        $confirm_password_err =  $error['confirm_password_err'];
 
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $error['confirm_password_err'] = "Password did not match";
            $confirm_password_err =  $error['confirm_password_err'];
        }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO login (username, email, password, usertype) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt,"ssss", $param_username, $param_email, $param_password, $param_usertype);
            
            // Set parameters
            $param_usertype = $_POST['usertype'];
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            //to insert username(as vendor) in database vendor too
            if($param_usertype == 'vendor'){
                $sql1 = "INSERT INTO vendor (UserName) VALUES (?)";

                if($stmt1 = mysqli_prepare($link, $sql1)){
                    mysqli_stmt_bind_param($stmt1,"s", $param_username1);

                    $param_username1 = $param_username;

                    mysqli_stmt_execute($stmt1);
                }
                mysqli_stmt_close($stmt1);
            }
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                $error['success'] = "Success";
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }      
        // Close statement
        mysqli_stmt_close($stmt);
    }

    array_push($data, $error);
    echo json_encode($data);   
    // Close connection
    mysqli_close($link);
}
?>