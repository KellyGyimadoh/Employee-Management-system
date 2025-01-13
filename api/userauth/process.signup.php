<?php
require '../../includes/sessions.php';
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/userdb/Register.php';
include '../../Controller/AuthController/RegisterController.php';
header('Content-Type: application/json');
try {
    $input= json_decode(file_get_contents('php://input'),true);
    if(json_last_error() !== JSON_ERROR_NONE){
        throw new Exception('invalid json input');
    }
    $firstname=filter_var($input['firstname'],FILTER_SANITIZE_SPECIAL_CHARS);
    $lastname=filter_var($input['lastname'],FILTER_SANITIZE_SPECIAL_CHARS);
    $password=filter_var($input['password'],FILTER_SANITIZE_SPECIAL_CHARS);
    $password_confirmation=filter_var($input['password_confirmation'],FILTER_SANITIZE_SPECIAL_CHARS);
    $email=filter_var($input['email'],FILTER_SANITIZE_EMAIL);
    $phone=filter_var($input['phone'],FILTER_SANITIZE_NUMBER_INT);
    $image=filter_var($input['image'],FILTER_SANITIZE_SPECIAL_CHARS);
    $csrfToken=$input['csrf_token'];
    $imageUploadResult=null;
    if(!hash_equals($_SESSION['csrf_token'],$csrfToken)){
        if($image){

         $imageUploadResult = processImage($image);
         if (is_string($imageUploadResult)) {
            // Error message from processImage()
            echo json_encode(['message'=>$imageUploadResult]);
        }
        }
         $user= new RegisterController($firstname,$lastname,
            $email,$phone,$password,$password_confirmation,$imageUploadResult);
        $result= $user->registerUser();
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
