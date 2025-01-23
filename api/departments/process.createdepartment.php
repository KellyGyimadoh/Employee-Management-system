<?php
include '../../includes/sessions.php';
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/departmentdb/Department.php';
include '../../Controller/DepartmentController/CreateDepartment.php';
header("Content-Type:application/json");
try {
    $input=json_decode(file_get_contents("php://input"),true);
    if(json_last_error()!==JSON_ERROR_NONE){
        throw new Exception('invalid json format');

    }
$name=filter_var($input['name'],FILTER_SANITIZE_SPECIAL_CHARS);
$head=isset($input['head']) ? filter_var($input['head'],FILTER_SANITIZE_NUMBER_INT): null;
$email=isset($input['email']) ? filter_var($input['email'],FILTER_SANITIZE_EMAIL): null;
$phone=isset($input['phone']) ? filter_var($input['phone'],FILTER_SANITIZE_SPECIAL_CHARS): null;
$csrfToken=$input['csrf_token'];
if(!isloggedin() && $_SESSION['accounttype']=='admin'){
    http_response_code(403);
    echo json_encode(['success'=>false,'message'=>'action not allowed']);
    die();
}
if(!empty($csrfToken)&&hash_equals($_SESSION['csrf_token'],$csrfToken)){
    $newdepartment= new CreateDepartment($name,$head,$phone,$email);
    $result= $newdepartment->createNewDepartment();
    if($result['success']){
        $_SESSION['csrf_token']=bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time']=time();
    }
    echo json_encode($result);
}else{
  http_response_code(403);
  echo json_encode(['success'=>false,'message'=>'invalid csrf token']);
}
} catch (Exception $e) {
    echo json_encode($e->getMessage());
}