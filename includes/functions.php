<?php
function regenerate_session_id()
{
    if (!isset($_SESSION['last_regeneration'])) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    } else {
        $interval = 60 * 30;
        if (time() - $_SESSION['last_regeneration'] >= $interval) {
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
    }
}


function regenerate_session_id_loggedin()
{
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

function isloggedin():bool
{
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        return true;
    } else {
        return false;
    }
}
function hashPassword($password)
{
    $options = ['cost' => 12];
    $password = password_hash($password, PASSWORD_DEFAULT, $options);
    return $password;
}

//for $_files processing
function processImage($file)
{
    if (!isset($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return "No valid image uploaded.";
    }

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
    $target_dir = __DIR__ . "/images/"; // Ensure target directory exists
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true); // Create directory if it doesn't exist
    }

    $target_file = $target_dir . uniqid() . '.' . $extension; // Generate a unique name

    if (!move_uploaded_file($temp_name, $target_file)) {
        return "Failed to upload the file.";
    }

    return $target_file; // Return the file path on success
}
//for json image processing
function processImageBase($base64Image) {
    if (strpos($base64Image, 'data:image') === 0) {
        // Extract base64 content
        $parts = explode(',', $base64Image);
        $imageData = base64_decode(end($parts));

        // Validate the file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_buffer($finfo, $imageData);
        finfo_close($finfo);

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($mimeType, $allowedMimeTypes)) {
            return "Invalid file type. Only JPEG, PNG, and JPG are allowed.";
        }

        // Generate a unique filename and save the image
        $extension = explode('/', $mimeType)[1];
        //$rootDir = dirname(__DIR__); // Move up one directory to the project root
        //$targetDir = $rootDir . "/images/";
        $targetDir = "../../images/"; 
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true); // Create directory if it doesn't exist
        }
        $targetFile = $targetDir . uniqid() . '.' . $extension;

        if (file_put_contents($targetFile, $imageData)) {
            return $targetFile;
        } else {
            return "Failed to save the image.";
        }
    } else {
        return "Invalid image data.";
    }
}


function isloggedOut()
{
    //$_SESSION['logged_out'] = true;
    // unset($_SESSION['csrf_token']);
    // unset($_SESSION['csrf_token_time']);

    // $_SESSION = [];

    
    // //Destroy the session cookie
    // if (ini_get("session.use_only_cookies")) {
    //     $params = session_get_cookie_params();
    //     setcookie(session_name(), '', time() - 42000,
    //         $params["path"], $params["domain"],
    //         $params["secure"], $params["httponly"]
    //     );
    // }
    session_destroy();
 header('location:../../auth/login.php');
    exit;
}

function signUpForm()
{
    if (isset($_SESSION['signupdata']['firstname'])) {
        echo "  <div class='row'>
        <div class='col-6'>
            <div class='form-group'>
                <input class='form-control' type='text' name='firstname' placeholder='First Name' value='" . $_SESSION['signupdata']['firstname'] . "'>
            </div>
        </div>";
    } else {
        echo "  <div class='row'>
        <div class='col-6'>
            <div class='form-group'>
                <input class='form-control' type='text' name='firstname' placeholder='First Name' >
            </div>
        </div>";
    }
    if (isset($_SESSION['signupdata']['lastname'])) {
        echo " <div class='col-6'>
        <div class='form-group'>
            <input class='form-control' type='text' name='lastname' placeholder='Last Name' value='" . $_SESSION['signupdata']['lastname'] . "'>
        </div>
    </div>
</div>";
    } else {
        echo " <div class='col-6'>
        <div class='form-group'>
            <input class='form-control' type='text' name='lastname' placeholder='Last Name'>
        </div>
    </div>
</div>";
    }
    if (isset($_SESSION['signupdata']['email']) && !isset($_SESSION['signuperrors']['invalidemail']) && !isset($_SESSION['signuperrors']['emailexist'])) {
        echo "
<div class='form-group'>
    <input class='form-control' type='email' name='email' placeholder='Email' autocomplete='off' value='" . $_SESSION['signupdata']['email'] . "'>
</div>
";
    } 
    else {
        echo "
        <div class='form-group'>
            <input class='form-control' type='email' name='email' placeholder='Email' autocomplete='off' >
        </div>
        ";
    }
    if (isset($_SESSION['signupdata']['phone']) && !isset($_SESSION['signuperrors']['invalidphone']) && !isset($_SESSION['signuperrors']['lowphone'])) {
        echo "
<div class='form-group'>
    <input class='form-control' type='number' name='phone' placeholder='Phone' value='" . $_SESSION['signupdata']['phone'] . "'>
</div>
";
    } else {
        echo "
    <div class='form-group'>
        <input class='form-control' type='number' name='phone' placeholder='Phone' >
    </div>
    ";
    }

    echo "
<div class='form-group'>
    <input class='form-control' id='password' type='password' name='password' placeholder='Password'>
</div>
<div class='form-group'>
    <input class='form-control' type='password' name='password_confirmation' placeholder='Confirm Password'>
</div>
<div class='form-group'>
    <label for='image'>Profile Image</label>
    <input class='form-control' type='file' name='image' placeholder='Profile image'/>
</div>
<div class='form-group'>
    <button class='btn btn-info btn-block' type='submit'>Sign up</button>
</div>";
}

function userProfileForm(){
    
    if(isset($_SESSION['userinfo']['userid'])){
        echo"  
          <div class='col-sm-6 form-group'>
              <input class='form-control' type='hidden' name='userid' value='" . $_SESSION['userinfo']['userid'] . "'>
          </div>";
      }else{
          echo"  <div class='row'>
          <div class='col-sm-6 form-group'>
              <input class='form-control' type='hidden' name='userid'>
          </div>";
      }
    if(isset($_SESSION['userinfo']['firstname'])){
      echo"  <div class='row'>
        <div class='col-sm-6 form-group'>
            <label>First Name</label>
            <input class='form-control' name='firstname' type='text' placeholder='First Name' value='" . $_SESSION['userinfo']['firstname'] . "'>
        </div>";
    }else{
        echo"  <div class='row'>
        <div class='col-sm-6 form-group'>
            <label>First Name</label>
            <input class='form-control'  name='firstname' type='text' placeholder='First Name'>
        </div>";
    }
    if(isset($_SESSION['userinfo']['lastname'])){
        echo"
         <div class='col-sm-6 form-group'>
        <label>Last Name</label>
        <input class='form-control' type='text' name='lastname' placeholder='Last Name' value='" . $_SESSION['userinfo']['lastname'] . "'>
    </div>
</div>
        ";
    }else{
        echo"
        <div class='col-sm-6 form-group'>
       <label>Last Name</label>
       <input class='form-control' type='text' name='lastname' placeholder='Last Name'>
   </div>
</div>
       ";
    }
   if(isset($_SESSION['userinfo']['email'])){
    echo"<div class='form-group'>
    <label>Email</label>
    <input class='form-control' name='email' type='email' placeholder='Email address' value='" . $_SESSION['userinfo']['email'] . "'>
</div>";
   }else{
    echo"<div class='form-group'>
    <label>Email</label>
    <input class='form-control' name='email' type='email' placeholder='Email address'>
</div>";
   }
   if(isset($_SESSION['userinfo']['phone'])){
    echo"<div class='form-group'>
    <label>Phone</label>
    <input class='form-control' name='phone' type='tel' placeholder='Phone Number' value='" . $_SESSION['userinfo']['phone'] . "'>
</div>";
   }else{
    echo"<div class='form-group'>
    <label>Email</label>
    <input class='form-control' name='phone' type='tel' placeholder='Phone Number'>
</div>";
   }
echo"

<div class='form-group'>
    <button class='btn btn-primary' type='submit'>Submit</button>
</div>";
}
function managerProfileForm(){
    
    if(isset($_SESSION['userinfo']['userid'])){
        echo"  
          <div class='col-sm-6 form-group'>
              <input class='form-control' type='hidden' name='userid' value='" . $_SESSION['userinfo']['userid'] . "'>
          </div>";
      }else{
          echo"  <div class='row'>
          <div class='col-sm-6 form-group'>
              <input class='form-control' type='hidden' name='userid'>
          </div>";
      }
    if(isset($_SESSION['userinfo']['firstname'])){
      echo"  <div class='row'>
        <div class='col-sm-6 form-group'>
            <label>First Name</label>
            <input class='form-control' name='firstname' type='text' placeholder='First Name' value='" . $_SESSION['userinfo']['firstname'] . "'>
        </div>";
    }else{
        echo"  <div class='row'>
        <div class='col-sm-6 form-group'>
            <label>First Name</label>
            <input class='form-control'  name='firstname' type='text' placeholder='First Name'>
        </div>";
    }
    if(isset($_SESSION['userinfo']['lastname'])){
        echo"
         <div class='col-sm-6 form-group'>
        <label>Last Name</label>
        <input class='form-control' type='text' name='lastname' placeholder='Last Name' value='" . $_SESSION['userinfo']['lastname'] . "'>
    </div>
</div>
        ";
    }else{
        echo"
        <div class='col-sm-6 form-group'>
       <label>Last Name</label>
       <input class='form-control' type='text' name='lastname' placeholder='Last Name'>
   </div>
</div>
       ";
    }
   if(isset($_SESSION['userinfo']['email'])){
    echo"<div class='form-group'>
    <label>Email</label>
    <input class='form-control' name='email' type='email' placeholder='Email address' value='" . $_SESSION['userinfo']['email'] . "'>
</div>";
   }else{
    echo"<div class='form-group'>
    <label>Email</label>
    <input class='form-control' name='email' type='email' placeholder='Email address'>
</div>";
   }
   if(isset($_SESSION['userinfo']['phone'])){
    echo"<div class='form-group'>
    <label>Phone</label>
    <input class='form-control' name='phone' type='tel' placeholder='Phone Number' value='" . $_SESSION['userinfo']['phone'] . "'>
</div>";
   }else{
    echo"<div class='form-group'>
    <label>Phone</label>
    <input class='form-control' name='phone' type='tel' placeholder='Phone Number'>
</div>";
   }
   if (isset($_SESSION['userinfo']['account_type'])) {
    $accountType = $_SESSION['userinfo']['account_type'];

    echo "<div class='col'>
<div class='form-group'><label for='accounttype'><strong>Account Type</strong></label><select name='account_type' >Account Type
<option value='' " . ($accountType == '' ? 'selected' : '') . ">Select Option</option>
<option value='admin' " . ($accountType == 'admin' ? 'selected' : '') . ">Admin</option>
<option value='staff' " . ($accountType == 'staff' ? 'selected' : '') . ">Staff</option>

</select></div>
</div>
</div>";
} else {
    $accountType = '';
    echo "<div class='col'>
<div class='form-group'><label for='accounttype'><strong>Account Type</strong></label><select name='account_type'>Account Type
<option value=''>Select Option</option>
    <option value='director'>Director</option>
    <option value='staff'>Staff</option>
</select></div>
</div>
</div>";
}
echo"

<div class='form-group'>
    <button class='btn btn-primary' type='submit'>Submit</button>
</div>";
}