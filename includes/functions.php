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
    if(isset($_SESSION['accounttype'])){

        $account =htmlspecialchars($_SESSION['accounttype']) ;
    }
    //get available account
    $allow = true;
    if (!empty($accounttype)) {
        if (in_array($account, $accounttype)) {
            return $allow;
        } else {
            return !$allow;
        }
    } 
    
}

function isloggedin(): bool
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
function processImageBase($base64Image)
{
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
    } else {
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

function userProfileForm()
{

    if (isset($_SESSION['userinfo']['userid'])) {
        echo "  
          <div class='col-sm-6 form-group'>
              <input class='form-control' type='hidden' name='userid' value='" . $_SESSION['userinfo']['userid'] . "'>
          </div>";
    } else {
        echo "  <div class='row'>
          <div class='col-sm-6 form-group'>
              <input class='form-control' type='hidden' name='userid'>
          </div>";
    }
    if (isset($_SESSION['userinfo']['firstname'])) {
        echo "  <div class='row'>
        <div class='col-sm-6 form-group'>
            <label>First Name</label>
            <input class='form-control' name='firstname' type='text' placeholder='First Name' value='" . $_SESSION['userinfo']['firstname'] . "'>
        </div>";
    } else {
        echo "  <div class='row'>
        <div class='col-sm-6 form-group'>
            <label>First Name</label>
            <input class='form-control'  name='firstname' type='text' placeholder='First Name'>
        </div>";
    }
    if (isset($_SESSION['userinfo']['lastname'])) {
        echo "
         <div class='col-sm-6 form-group'>
        <label>Last Name</label>
        <input class='form-control' type='text' name='lastname' placeholder='Last Name' value='" . $_SESSION['userinfo']['lastname'] . "'>
    </div>
</div>
        ";
    } else {
        echo "
        <div class='col-sm-6 form-group'>
       <label>Last Name</label>
       <input class='form-control' type='text' name='lastname' placeholder='Last Name'>
   </div>
</div>
       ";
    }
    if (isset($_SESSION['userinfo']['email'])) {
        echo "<div class='form-group'>
    <label>Email</label>
    <input class='form-control' name='email' type='email' placeholder='Email address' value='" . $_SESSION['userinfo']['email'] . "'>
</div>";
    } else {
        echo "<div class='form-group'>
    <label>Email</label>
    <input class='form-control' name='email' type='email' placeholder='Email address'>
</div>";
    }
    if (isset($_SESSION['userinfo']['phone'])) {
        echo "<div class='form-group'>
    <label>Phone</label>
    <input class='form-control' name='phone' type='tel' placeholder='Phone Number' value='" . $_SESSION['userinfo']['phone'] . "'>
</div>";
    } else {
        echo "<div class='form-group'>
    <label>Email</label>
    <input class='form-control' name='phone' type='tel' placeholder='Phone Number'>
</div>";
    }
    echo "

<div class='form-group'>
    <button class='btn btn-primary' type='submit'>Submit</button>
</div>";
}
function managerProfileForm()
{

    if (isset($_SESSION['userinfo']['id'])) {
        echo "  
          <div class='col-sm-6 form-group'>
              <input class='form-control' type='hidden' name='userid' value='" . $_SESSION['userinfo']['id'] . "'>
          </div>";
    } else {
        echo "  <div class='row'>
          <div class='col-sm-6 form-group'>
              <input class='form-control' type='hidden' name='userid'>
          </div>";
    }
    if (isset($_SESSION['userinfo']['firstname'])) {
        echo "  <div class='row'>
        <div class='col-sm-6 form-group'>
            <label>First Name</label>
            <input class='form-control' name='firstname' type='text' placeholder='First Name' value='" . $_SESSION['userinfo']['firstname'] . "'>
        </div>";
    } else {
        echo "  <div class='row'>
        <div class='col-sm-6 form-group'>
            <label>First Name</label>
            <input class='form-control'  name='firstname' type='text' placeholder='First Name'>
        </div>";
    }
    if (isset($_SESSION['userinfo']['lastname'])) {
        echo "
         <div class='col-sm-6 form-group'>
        <label>Last Name</label>
        <input class='form-control' type='text' name='lastname' placeholder='Last Name' value='" . $_SESSION['userinfo']['lastname'] . "'>
    </div>
</div>
        ";
    } else {
        echo "
        <div class='col-sm-6 form-group'>
       <label>Last Name</label>
       <input class='form-control' type='text' name='lastname' placeholder='Last Name'>
   </div>
</div>
       ";
    }
    if (isset($_SESSION['userinfo']['email'])) {
        echo "<div class='form-group'>
    <label>Email</label>
    <input class='form-control' name='email' type='email' placeholder='Email address' value='" . $_SESSION['userinfo']['email'] . "'>
</div>";
    } else {
        echo "<div class='form-group'>
    <label>Email</label>
    <input class='form-control' name='email' type='email' placeholder='Email address'>
</div>";
    }
    if (isset($_SESSION['userinfo']['phone'])) {
        echo "<div class='form-group'>
    <label>Phone</label>
    <input class='form-control' name='phone' type='tel' placeholder='Phone Number' value='" . $_SESSION['userinfo']['phone'] . "'>
</div>";
    } else {
        echo "<div class='form-group'>
    <label>Phone</label>
    <input class='form-control' name='phone' type='tel' placeholder='Phone Number'>
</div>";
    }
    if (isset($_SESSION['userinfo']['account_type'])) {
        $accountType = $_SESSION['userinfo']['account_type'];

        echo "
<div class='form-group'>
<label for='accounttype'><strong>Account Type</strong></label>
<select name='account_type' class='form-control'>Account Type
<option value='' " . ($accountType == '' ? 'selected' : '') . ">Select Option</option>
<option value='admin' " . ($accountType == 'admin' ? 'selected' : '') . ">Admin</option>
<option value='staff' " . ($accountType == 'staff' ? 'selected' : '') . ">Staff</option>

</select></div>

";
    } else {
        $accountType = '';
        echo "
<div class='form-group'><label for='accounttype'><strong>Account Type</strong></label><select name='account_type'>Account Type
<option value=''>Select Option</option>
    <option value='director'>Director</option>
    <option value='staff'>Staff</option>
</select></div>

";
    }
    echo "

<div class='form-group'>
    <button class='btn btn-primary' type='submit'>Submit</button>
</div>";
}
function userDetailsProfileForm()
{

    if (isset($_SESSION['userdetails']['id'])) {
        echo "  
          <div class='col-sm-6 form-group'>
              <input class='form-control' type='hidden' name='userid' value='" . $_SESSION['userdetails']['id'] . "'>
          </div>";
    } else {
        echo "  <div class='row'>
          <div class='col-sm-6 form-group'>
              <input class='form-control' type='hidden' name='userid'>
          </div>";
    }
    if (isset($_SESSION['userdetails']['firstname'])) {
        echo "  <div class='row'>
        <div class='col-sm-6 form-group'>
            <label>First Name</label>
            <input class='form-control' name='firstname' type='text' placeholder='First Name' value='" . $_SESSION['userdetails']['firstname'] . "'>
        </div>";
    } else {
        echo "  <div class='row'>
        <div class='col-sm-6 form-group'>
            <label>First Name</label>
            <input class='form-control'  name='firstname' type='text' placeholder='First Name'>
        </div>";
    }
    if (isset($_SESSION['userdetails']['lastname'])) {
        echo "
         <div class='col-sm-6 form-group'>
        <label>Last Name</label>
        <input class='form-control' type='text' name='lastname' placeholder='Last Name' value='" . $_SESSION['userdetails']['lastname'] . "'>
    </div>
</div>
        ";
    } else {
        echo "
        <div class='col-sm-6 form-group'>
       <label>Last Name</label>
       <input class='form-control' type='text' name='lastname' placeholder='Last Name'>
   </div>
</div>
       ";
    }
    if (isset($_SESSION['userdetails']['email'])) {
        echo "<div class='form-group'>
    <label>Email</label>
    <input class='form-control' name='email' type='email' placeholder='Email address' value='" . $_SESSION['userdetails']['email'] . "'>
</div>";
    } else {
        echo "<div class='form-group'>
    <label>Email</label>
    <input class='form-control' name='email' type='email' placeholder='Email address'>
</div>";
    }
    if (isset($_SESSION['userdetails']['phone'])) {
        echo "<div class='form-group'>
    <label>Phone</label>
    <input class='form-control' name='phone' type='tel' placeholder='Phone Number' value='" . $_SESSION['userdetails']['phone'] . "'>
</div>";
    } else {
        echo "<div class='form-group'>
    <label>Phone</label>
    <input class='form-control' name='phone' type='tel' placeholder='Phone Number'>
</div>";
    }
    if (isset($_SESSION['userdetails']['account_type'])) {
        $accountType = $_SESSION['userdetails']['account_type'];

        echo "<div class='col'>
<div class='form-group'><label for='accounttype'><strong>Account Type</strong></label><select name='account_type' >Account Type
<option value='' " . ($accountType == '' ? 'selected' : '') . ">Select Option</option>
<option value='admin' " . ($accountType == 'admin' ? 'selected' : '') . ">Admin</option>
<option value='staff' " . ($accountType == 'staff' ? 'selected' : '') . ">Staff</option>

</select></div>
</div>
";
    } else {
        $accountType = '';
        echo "<div class='col'>
<div class='form-group'><label for='accounttype'><strong>Account Type</strong></label><select name='account_type'>Account Type
<option value=''>Select Option</option>
    <option value='director'>Director</option>
    <option value='staff'>Staff</option>
</select></div>
</div>
";
    }
    if (isset($_SESSION['userdetails']['status'])) {
        $status = $_SESSION['userdetails']['status'];

        echo "<div class='col'>
<div class='form-group'><label for='status'><strong>Account Status</strong></label><select name='status' >Account Type
<option value='' " . ($status == '' ? 'selected' : '') . ">Select Option</option>
<option value='2' " . ($status == '2' ? 'selected' : '') . ">Suspended</option>
<option value='1' " . ($status == '1' ? 'selected' : '') . ">Active</option>

</select></div>
</div>
";
    } else {
        $status = '';
        echo "<div class='col'>
<div class='form-group'><label for='status'><strong>Account Status</strong></label><select name='status'>Account Type
<option value=''>Select Option</option>
    <option value='1'>Activate</option>
    <option value='2'>Suspend</option>
</select></div>
</div>
";
    }
    echo "

<div class='form-group'>
    <button class='btn btn-primary' type='submit'>Edit User</button>
</div>";
}

function createDepartmentForm()
{
    if (isset($_SESSION['departmentdetails']['id'])) {
        echo "

           
            <input type='hidden' name='id' 
             value='" . $_SESSION['departmentdetails']['id'] . "'>
                                
";
    } else {

        echo "
       
        <input type='hidden' name='id' class='form-control' placeholder='Name of Department'>
        
        ";
    }

    if (isset($_SESSION['departmentdetails']['name'])) {
        echo "
     
<div class='col-6 mb-2 p-2'>
<label for='name'>Name Of Department</label>
<input type='text' name='name' class='form-control' placeholder='Name of Department'
 value='" . $_SESSION['departmentdetails']['name'] . "'>
     </div>
";
    } else {

        echo "
     
        <div class='col-6 mb-2 p-2'>
    <label for='name'>Name Of Department</label>
        <input type='text' name='name' class='form-control' placeholder='Name of Department'>
            </div>
        ";
    }
    if (isset($_SESSION['departmentdetails']['email'])) {
        echo "   <div class='col-6 mb-2 p-2'><label for='email'>Email Of Department</label>
                <input type='text' name='email' class='form-control'
                 placeholder='Email'  value='" . $_SESSION['departmentdetails']['email'] . "'>
                 </div>
                 
                        ";
    } else {
        echo "               <div class='col-6 mb-2 p-2'>
        <label for='email'>Email Of Department</label>
        <input type='text' name='email' class='form-control' placeholder='Email'>
    </div>

";
    }

    if (isset($_SESSION['departmentdetails']['phone'])) {

        echo "
    
            <div class='col-6 mb-2 p-2'>
            <label for='phone'>Department Phone</label>
            <input type='tel' name='phone' class='form-control' placeholder='Department Phone' value='" . $_SESSION['departmentdetails']['phone'] . "'>
            </div>
        ";
    } else {
        echo "
    
        <div class='col-6 mb-2 p-2'>
           <label for='phone'>Department Phone</label>
           <input type='tel' name='phone' class='form-control' placeholder='Department Phone'>
           </div>
       ";
    }
    if (isset($_SESSION['departmentdetails']['head'])) {
        $head = $_SESSION['departmentdetails']['head'];
        $firstname = $_SESSION['departmentdetails']['head_firstname'];
        $lastname = $_SESSION['departmentdetails']['head_lastname'];


        echo "
          <div class='col-6 mb-2 p-2'>
             <label for='departmenthead-edit'>Head Of Department</label>
             <h5 class='form-control m-2' 'id='headname'>$firstname $lastname</h5>
             <select name='head' id='departmenthead-select-edit' class='form-control'>
           <option value='' >Select Department Head</option>
        <option value='$head' " . (!empty($head) ? 'selected' : '') . ">$firstname $lastname</option>
        </select>
        </div>
        
        ";
    } else {
        echo "  <div class='col-6 mb-2 p-2'>
             <label for='departmenthead-select'>Head Of Department</label>
             <select name='head' id='departmenthead-select-edit' class='form-control'>
 <option value='' >Select Department Head</option>
        </select>
        </div>
        
        ";
    }


    if (isset($_SESSION['departmentdetails']['status'])) {

        $status = $_SESSION['departmentdetails']['status'];

        echo "<div class='col-6 mb-2 p-2'>
<div class='form-group'>
<label for='status'><strong>Account Status</strong></label>
<select name='status' class='form-control' >Account Type
<option value='' " . ($status == '' ? 'selected' : '') . ">Select Option</option>
<option value='2' " . ($status == '2' ? 'selected' : '') . ">Suspended</option>
<option value='1' " . ($status == '1' ? 'selected' : '') . ">Active</option>

</select>
</div>

";
    } else {
        $status = '';
        echo "<div class='col-6 mb-2 p-2'>
<div class='form-group'><label for='status'><strong>Account Status</strong>
</label><select name='status' class='form-control'>Account Type
<option value=''>Select Option</option>
    <option value='1'>Activate</option>
    <option value='2'>Suspend</option>
</select>
</div>

";
    }
}

function editSalariesForm()
{
    echo "<div class='row mt-3'>";
    if (isset($_SESSION['salarydetails']['id'])) {
        echo "<input type='hidden' name='id' value='" . $_SESSION['salarydetails']['id'] . "'/>";
    }
    if (isset($_SESSION['salarydetails']['user_id'])) {
        $id = $_SESSION['salarydetails']['user_id'];
        $firstname = $_SESSION['salarydetails']['firstname'];
        $lastname = $_SESSION['salarydetails']['lastname'];
        echo " <div class='col-6 mt-2'>
            <label for='user-select'>Worker Name</label>
            <select name='user_id' id='user-select' class='form-control' required>
            <option value=''>Select Worker</option>
            <option value='$id' " . (!empty($id) ? 'selected' : '') . ">$firstname $lastname</option>
                </select>
            </div>";
    } else {
        echo " <div class='col-6 mt-2'>
        <label for='user-select'>Worker Name</label>
        <select name='user_id' id='user-select' class='form-control' required>
        <option value=''>Select Worker</option>
        

            </select>
        </div>";
    }
    if (isset($_SESSION['salarydetails']['base_salary'])) {
        echo " <div class='col-6 mt-2'>
            <label class='m-2' for='base_salary'>Base Salary</label>
        <input type='number' name='base_salary' class='form-control' value='" . number_format($_SESSION['salarydetails']['base_salary'], '2', '.', '') . "' 
        step='0.01' min='0' placeholder='Base Salary' required>
        </div>

        ";
    } else {
        echo " <div class='col-6 mt-2'>
    <label class='m-2' for='base_salary'>Base Salary</label>
    <input type='number' name='base_salary' class='form-control' value='0' step='0.01' min='0' placeholder='Base Salary' required>
</div>
";
    }
    echo "</div>
 <div class='row'>
";
    if (isset($_SESSION['salarydetails']['bonus'])) {
        echo "
     <div class='col-6 mt-2'>
            <label class='m-2' for='name'>Bonus</label>
            <input type='number' name='bonus' class='form-control' value='" . number_format($_SESSION['salarydetails']['bonus'], '2', '.', '') . "' 
            step='0.01' min='0' placeholder='Bonus' required>
        </div>
    
    ";
    } else {
        echo "
    <div class='col-6 mt-2'>
           <label class='m-2' for='name'>Bonus</label>
           <input type='number' name='bonus' class='form-control' value='0' 
           step='0.01' min='0' placeholder='Bonus' required>
       </div>
   
   ";
    }
    if (isset($_SESSION['salarydetails']['deductions'])) {

        echo " <div class='col-6 mt-2'>
        <label class='m-2' for='deductions'>Deductions</label>
        <input type='number' name='deductions' class='form-control' value='" . number_format($_SESSION['salarydetails']['deductions'], '2', '.', '') . "' 
         step='0.01' min='0' placeholder='Deductions' required>
        </div>";
    } else {
        echo " <div class='col-6 mt-2'>
        <label class='m-2' for='deductions'>Deductions</label>
        <input type='number' name='deductions' class='form-control' value='0'
         step='0.01' min='0' placeholder='Deductions' required>
        </div>";
    }
    if (isset($_SESSION['salarydetails']['overtime'])) {
        echo " <div class='col-6 mt-2'>
        <label class='m-2' for='deductions'>Overtime</label>
        <input type='number' name='overtime' class='form-control' value='" . number_format($_SESSION['salarydetails']['overtime'], '2', '.', '') . "'  
        step='0.01' min='0' placeholder='Overtime' required>
        </div>";
    } else {
        echo " <div class='col-6 mt-2'>
    <label class='m-2' for='deductions'>Overtime</label>
    <input type='number' name='overtime' class='form-control' value='0'  
    step='0.01' min='0' placeholder='Overtime' required>
    </div>";
    }

    echo " <div class='col-6 mt-5  w-25 border border-primary d-flex flex-column justify-content-center rounded '>

<h3 class='text-center'>Total Salary GHS</h3>
<div class='col-6 m-auto   d-flex flex-column justify-content-center'>
     <p class='text-center fs-3'>
     <span class='totalsalary fs-3'>" . ($_SESSION['salarydetails']['total_salary'] ?? '0.00') . "</span></p>
    </div>
    </div>
</div>";
}


function editPayrollForm()
{
    $result = $_SESSION['payrolldetails'];
    if (isset($result['id'])) {
        echo "
        <input type='hidden' name='id'  class='form-control' value='" . $result['id'] . "' required>
        ";
    }
    if (isset($result['user_id'])) {
        $firstname = $result['firstname'];
        $lastname = $result['lastname'];
        echo "
        <div class='row mt-3'>

    <div class='col-6 mt-2'>
        <label for='user-select'>Worker Name</label>
        <h3  class='form-control'>$firstname $lastname</h3>
        <input type='hidden' value='" . $result['user_id'] . "'>

    </div>
        ";
    } else {
        echo "
        <div class='row mt-3'>

    <div class='col-6 mt-2'>
        <label for='user-select'>Worker Name</label>

    </div>";
    }
    if (isset($result['total_salary'])) {
        echo "
        <div class='col-6 mt-2'>
        <label class='m-2' for='total_salary'>Total Salary</label>
         <h3  class='form-control'>" . $result['total_salary'] . "</h3>
    </div>

</div>
        ";
    } else {
        echo "
        <div class='col-6 mt-2'>
        <label class='m-2' for='total_salary'>Total Salary</label>
    </div>

</div>
        ";
    }

    if (isset($result['date'])) {
        echo "
<div class='row'>
   
    <div class='col-6 mt-2'>
        <label class='m-2' for='date'>Payment Date</label>
        <input type='date' name='date' class='form-control' value='" . $result['date'] . "' required>
    </div>
";
    } else {

        echo "
        <div class='row'>
           
            <div class='col-6 mt-2'>
                <label class='m-2' for='date'>Payment Date</label>
                <input type='date' name='date' class='form-control' required>
            </div>
        ";
    }
    if (isset($result['due_date'])) {
        echo "
    
        <div class='col-6 mt-2'>
         <label class='m-2' for='due_date'>Due Date</label>
        <input type='date' name='due_date' class='form-control' value='" . $result['due_date'] . "'>
        </div>
    ";
    } else {

        echo "
            
                
                <div class='col-6 mt-2'>
                    <label class='m-2' for='due_date'>Due Date</label>
                    <input type='date' name='due_date' class='form-control'>
                </div>
            ";
    }


    if (isset($result['status'])) {

        $status = $result['status'];
        echo "
        <div class='col-6 mt-2'>
    <label for='user-select'>Status</label>
    <select name='status' id='status' class='form-control' required>
    <option value='' " . ($status == '' ? 'selected' : '') . ">Select Payment Status</option>
    <option value='paid' " . ($status == 'paid' ? 'selected' : '') . ">Paid</option>
    <option value='unpaid' " . ($status == 'unpaid' ? 'selected' : '') . ">Unpaid</option>                           
    <option value='pending' " . ($status == 'pending' ? 'selected' : '') . ">Pending</option>                           
</select>
        </div>
</div>";
    } else {
        echo "
    <div class='col-6 mt-2'>
    <label for='user-select'>Status</label>
    <select name='status' id='status' class='form-control' required>
    <option value=''>Select Payment Status</option>
    <option value='paid'>Paid</option>
    <option value='unpaid'>Unpaid</option>
    <option value='pending'>Pending</option>

    </select>
    </div>
    </div>";
    }
}


//attendance form
function editAttendanceForm()
{
    $result = $_SESSION['attendancedetails'];
    if (isset($result['id'])) {
        echo "
         <input type='hidden' name='id'  class='form-control' value='" . $result['id'] . "' required>
         ";
    }
    if (isset($result['user_id'])) {
        $firstname = $result['firstname'];
        $lastname = $result['lastname'];
        echo "
         <div class='row mt-3'>
 
     <div class='col-6 mt-2'>
         <label for='user-select'>Employee Name</label>
         <h3  class='form-control'>$firstname $lastname</h3>
         <input type='hidden' name='user_id' value='" . $result['user_id'] . "'>
 
     </div>
         ";
    } else {
        echo "
         <div class='row mt-3'>
 
     <div class='col-6 mt-2'>
         <label for='user-select'>Employee Name</label>
 
     </div>";
    }
    if (isset($result['checkin_time'])) {
        echo "
         <div class='col-6 mt-2'>
         <label class='m-2' for='checkin_time'>Time Checked In</label>
          <input type='time' name='checkin_time' class='form-control' value='" . $result['checkin_time'] . "' required>
     </div>
 
 </div>
         ";
    } else {
        echo "
         <div class='col-6 mt-2'>
         <label class='m-2' for='checkin_time'>Time Checked In</label>
          <input type='time' name='checkin_time' class='form-control'>
  
     </div>
 
 </div>
         ";
    }

    if (isset($result['date'])) {
        echo "
 <div class='row'>
    
     <div class='col-6 mt-2'>
         <label class='m-2' for='date'>Attendance Date</label>
         <input type='date' name='date' class='form-control' value='" . $result['date'] . "' required>
     </div>
 ";
    } else {

        echo "
         <div class='row'>
            
             <div class='col-6 mt-2'>
                 <label class='m-2' for='date'>Attendance Date</label>
                 <input type='date' name='date' class='form-control' required>
             </div>
         ";
    }

    if (isset($result['status'])) {

        $status = $result['status'];
        echo " <div class='col-6 mt-2'>
             <label for='user-select'>Status</label>
             <select name='status' id='status' class='form-control' required>
                <option value='' " . ($status == '' ? 'selected' : '') . ">Select Status</option>
             <option value='1' " . ($status == '1' ? 'selected' : '') . ">Absent</option>
             <option value='2' " . ($status == '2' ? 'selected' : '') . ">Present</option>                           
             <option value='3' " . ($status == '3' ? 'selected' : '') . ">Late</option>    
             </select>
         </div>
     
     </div>";
    } else {
        echo
        " <div class='col-6 mt-2'>
            <label for='user-select'>Status</label>
            <select name='status' id='status' class='form-control' required>
                <option value=''>Select Payment Status</option>
                <option value='1'>Absent</option>
                <option value='2'>Present</option>
                <option value='3'>Late</option>
             </select>
                 </div>
             
             </div>";
    }
}


function editTaskForm()
{
    $result = $_SESSION['taskdetails'];



    // Hidden task ID
    if (isset($result['id'])) {
        echo "<input type='hidden' name='taskid' value='" . htmlspecialchars($result['id']) . "'>";
    }

    // Task Name
    $taskName = $result['name'] ?? '';
    echo "
    <div class='row mt-3'>
        <div class='col-6 mt-2'>
            <label class='m-2' for='name'>Task Name</label>
            <input type='text' name='name' class='form-control' value='" . htmlspecialchars($taskName) . "' placeholder='Task Name' required>
        </div>";

    // Description
    $description = $result['description'] ?? '';
    echo "
        <div class='col-6 mt-2'>
            <label class='m-2' for='description'>Description</label>
            <textarea name='description' cols='3' rows='4' class='form-control' placeholder='Task Description'>" . htmlspecialchars($description) . "</textarea>
        </div>
    </div>";

    // Assign To
    $assignedTo = $result['assigned_to'] ?? '';
    $firstname = $result['assigned_to_firstname'] ?? '';
    $lastname = $result['assigned_to_lastname'] ?? '';
    echo "
    <div class='row'>
        <div class='col-6 mt-2'>
            <label for='user-select'>Assign To</label>
            <select name='assigned_to' id='user-select' class='form-control' required>
                <option value=''>Select Worker</option>
                " . (!empty($assignedTo) ? "<option value='$assignedTo' selected>$firstname $lastname</option>" : "") . "
            </select>
        </div>";

    // Department
    $departmentId = $result['department_id'] ?? '';
    $departmentName = $result['department_name'] ?? '';
    echo "
        <div class='col-6 mt-2'>
            <label for='department'>Department</label>
            <select name='department_id' id='department-select' class='form-control' required>
                <option value=''>Select Department</option>
                " . (!empty($departmentId) ? "<option value='$departmentId' selected>$departmentName</option>" : "") . "
            </select>
        </div>
    </div>";

    // Assigned By
    $assignedBy = $result['assigned_by'] ?? '';
    $headFirstname = $result['assigned_by_firstname'] ?? '';
    $headLastname = $result['assigned_by_lastname'] ?? '';
    echo "
    <div class='col-6 mt-2'>
        <label for='userhead'>Assigned By</label>
        <select name='assigned_by' id='user-head' class='form-control' required>
            <option value=''>Select Head</option>
            " . (!empty($assignedBy) ? "<option value='$assignedBy' selected>$headFirstname $headLastname</option>" : "") . "
        </select>
    </div>";

    // Due Date
    $dueDate = $result['due_date'] ?? '';
    echo "
    <div class='col-6 mt-3'>
        <label for='due_date'>Due Date</label>
        <input type='date' name='due_date' class='form-control' value='" . htmlspecialchars($dueDate) . "'>
    </div>";

    // Date Completed
    $dateCompleted = $result['date_completed'] ?? '';
    echo "
    <div class='col-6 mt-3'>
        <label for='date_completed'>Date Completed</label>
        <input type='date' name='date_completed' class='form-control' value='" . htmlspecialchars($dateCompleted) . "'>
    </div>";

    // Status
    $status = $result['status'] ?? '';
    echo "
    <div class='col-6 mt-2'>
        <label for='status'>Status</label>
        <select name='status' id='status' class='form-control' required>
            <option value='' " . ($status == '' ? 'selected' : '') . ">Select Status</option>
            <option value='1' " . ($status == '1' ? 'selected' : '') . ">Pending</option>
            <option value='2' " . ($status == '2' ? 'selected' : '') . ">Completed</option>
            <option value='3' " . ($status == '3' ? 'selected' : '') . ">Late</option>
        </select>
    </div>
    </div>";
}

function editUserLeaveForm(){
    $result=$_SESSION['userleavedetails'];
    if(isset($result['id'])){
        echo"<input type='hidden' name='id'
value='".$result['id']."'>
";
    }

    $requestedBy = $result['user_id'] ?? '';
    $userFirstname = $result['requested_by_firstname'] ?? '';
    $userLastname = $result['requested_by_lastname'] ?? '';
    echo "
    <div class='col-6 mt-2'>
        <label>Requested By</label>
        <input type='text'  class='form-control' value='".htmlspecialchars($userFirstname).' '.
        htmlspecialchars($userLastname)."' disabled>
        </div>
        ";

    $type=$result['type'] ?? '';
    echo"<div class='col-6 mt-2'>
<label class='m-2' for='type'>Reason</label>
<input type='text' name='type' class='form-control' placeholder='Reason' 
value='".htmlspecialchars($type)."' required>
</div>";

$startDate=$result['start_date']??'';
echo"<div class='col-6 mt-2'>
<label class='m-2' for='start_date'>Start Date</label>
<input type='date' name='start_date' class='form-control' value='".htmlspecialchars($startDate)."'>
</div>

</div>
";

$endDate=$result['end_date']??'';
echo"<div class='row'>
<div class='col-6 mt-2'>
<label class='m-2' for='end_date'>End Date</label>
<input type='date' name='end_date' class='form-control' value='".htmlspecialchars($endDate)."'>
</div>
";

$status = $result['status'] ?? '';
echo "
<div class='col-6 mt-2'>
    <label for='status'>Status</label>
    <select name='status' id='status' class='form-control' required>
        <option value='' " . ($status == '' ? 'selected' : '') . ">Select Status</option>
        <option value='1' " . ($status == '1' ? 'selected' : '') . ">Pending</option>
        <option value='2' " . ($status == '2' ? 'selected' : '') . ">Approved</option>
        <option value='3' " . ($status == '3' ? 'selected' : '') . ">Rejected</option>
    </select>
</div>";

 // Assigned By
 $assignedBy = $result['approved_by'] ?? '';
 $headFirstname = $result['approved_by_firstname'] ?? '';
 $headLastname = $result['approved_by_lastname'] ?? '';
 echo "
 <div class='col-6 mt-2'>
     <label for='userhead'>Assigned By</label>
     <select name='approved_by' id='user-head' class='form-control' required>
         <option value=''>Select Head</option>
         " . (!empty($assignedBy) ? "<option value='$assignedBy' selected>$headFirstname $headLastname</option>" : "") . "
     </select>
 </div>
</div> 
 ";
}









