<?php
require '../../includes/sessions.php';
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/userdb/Register.php';
include '../../Controller/AuthController/UpdateController.php';
header('Content-Type: application/json');
try {
    $input= json_decode(file_get_contents('php://input'),true);
    if(json_last_error() !== JSON_ERROR_NONE){
        throw new Exception('invalid json input');
    }
    $userid=filter_var($input['userid'],FILTER_SANITIZE_NUMBER_INT);
    $firstname=filter_var($input['firstname'],FILTER_SANITIZE_SPECIAL_CHARS);
    $lastname=filter_var($input['lastname'],FILTER_SANITIZE_SPECIAL_CHARS);
    $email=filter_var($input['email'],FILTER_SANITIZE_EMAIL);
    $phone=filter_var($input['phone'],FILTER_SANITIZE_NUMBER_INT);
    $accounttype = isset($input['account_type']) 
    ? filter_var($input['account_type'], FILTER_SANITIZE_SPECIAL_CHARS) 
    : null;
        $csrfToken=$input['csrf_token'];
        if(!isloggedin() || !isset($_SESSION['userid']) && $userid!==$_SESSION['userid']){
            http_response_code(403);
            echo json_encode(['success'=>false,'message'=>'action not allowed']);
            die();
        }
    if(!empty($csrfToken)&&hash_equals($_SESSION['csrf_token'],$csrfToken)){
       
          
         $user= new UpdateController($userid,$firstname,$lastname,
            $email,$phone,$accounttype);
        $result= $user->updateUserProfile();
            if($result){
                $_SESSION['csrf_token']=bin2hex(random_bytes(32));
                echo json_encode($result);
            }
        
    }else{
        http_response_code(403);
        echo json_encode(['success'=>false,'message'=>'invalid csrf token']);
    }

   

} catch (Exception $e) {
    echo json_encode(['success'=>false,'messsage'=>$e->getMessage()]);
}
