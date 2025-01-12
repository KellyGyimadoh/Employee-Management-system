<?php
function regenerate_session_id(){
    if(!isset($_SESSION['last_regeneration'])){
        session_regenerate_id(true);
        $_SESSION['last_regeneration']=time();
    }else{
        $interval= 60*30;
        if(time()-$_SESSION['last_regeneration']>=$interval){
            session_regenerate_id(true);
            $_SESSION['last_regeneration']=time();
        }
    }
}


function regenerate_session_id_loggedin(){
    session_regenerate_id(true); // Regenerate session ID to create a new session
    $userid = $_SESSION['userid']; // Retrieve the user ID from session
    $newsessionid = session_create_id(); // Create a new session ID
    session_commit(); // Close the current session to apply changes
    session_id($newsessionid . "_" . $userid); // Set the new session ID
    session_start(); // Restart the session with the new session ID
    $_SESSION['userid'] = $userid; // Reassign the user ID to the session
    $_SESSION['last_regeneration'] = time(); // Update the session regeneration time

}

function checkAccount($accounttype)
{
    $account = isset($_SESSION['accounttype']) ? $_SESSION['accounttype'] : "";
    //get available account
    $allow = true;
    if (!empty($accounttype) && isset($accounttype) && $account === $accounttype) {
        if (in_array($accounttype, $account) && $allow === true) {
        } else {
            header("../auth/index.php");
        }
    } else {
        header("Location:../auth/index.php");
        die();
    }
}

function isloggedin(){
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
        return true;
    }else{
        return false;
    }
}
function hashPassword($password){
    $options=['cost'=>12];
$password=password_hash($password,PASSWORD_DEFAULT,$options );
return $password;
}

function processImage($file)
{
    $filename = $file['name'];
    $filesize = $file['size'];
    $temp_name = $file['tmp_name'];
    $type = $file['type'];

    // Get file extension
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    // Allowed file extensions
    $allowed_extensions = ['jpeg', 'png', 'jpg'];

    // Check if the file extension is valid
    if (!in_array($extension, $allowed_extensions)) {
        return "Invalid file type. Only JPEG, PNG, and JPG are allowed.";
    }

    // Check if the file size exceeds the limit (e.g., 500KB)
    if ($filesize > 500 * 1024) {
        return "File size exceeds the 500KB limit.";
    }

    // Move the uploaded file to the target directory
    $target_dir = "images/";
    $target_file = $target_dir . uniqid() . '.' . $extension; // Generate a unique name

    if (!move_uploaded_file($temp_name, $target_file)) {
        return "Failed to upload the file.";
    }

    return $target_file; // Return the file path on success
}

function isloggedOut(){
    $_SESSION['logged_out']=true;
    session_destroy();

    header('location:../auth/login.php?logout=true');
    exit;
}