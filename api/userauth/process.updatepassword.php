<?php
require '../../includes/sessions.php';
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/userdb/Register.php';
include '../../Controller/AuthController/UpdatePasswordController.php';
header('Content-Type: application/json');
try {
    $input= json_decode(file_get_contents('php://input'),true);
    if(json_last_error() !== JSON_ERROR_NONE){
        throw new Exception('invalid json input');
    }
    $userid=filter_var($input['userid'],FILTER_SANITIZE_NUMBER_INT);
    $oldpassword=filter_var($input['old_password'],FILTER_SANITIZE_SPECIAL_CHARS);
    $newpassword=filter_var($input['new_password'],FILTER_SANITIZE_SPECIAL_CHARS);
  
        $csrfToken=$input['csrf_token'];
        if(!isloggedin() || !isset($_SESSION['userid']) && $userid!==$_SESSION['userid']){
            http_response_code(403);
            echo json_encode(['success'=>false,'message'=>'action not allowed']);
            die();
        }
    if(!empty($csrfToken)&&hash_equals($_SESSION['csrf_token'],$csrfToken)){
       
          
         $user= new UpdatePasswordController($userid,$newpassword,$oldpassword);
        $result= $user->updatePassword();
        if ($result['success'] && $result['success']==true) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();
        }

        echo json_encode($result);
        
    }else{
        http_response_code(403);
        echo json_encode(['success'=>false,'message'=>'invalid csrf token']);
    }

   

} catch (Exception $e) {
    echo json_encode(['success'=>false,'messsage'=>$e->getMessage()]);
}
