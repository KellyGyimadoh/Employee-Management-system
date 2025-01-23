<?php
include '../../includes/sessions.php';
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/departmentdb/Department.php';
include '../../Controller/DeptUserController/DepartmentUserJoinedController.php';
header("Content-Type:application/json");
try {
    $input=json_decode(file_get_contents("php://input"),true);
    if(json_last_error()!==JSON_ERROR_NONE){
        throw new Exception('invalid json format');

    }

$userid=isset($input['user_id'])? filter_var($input['user_id'],FILTER_SANITIZE_NUMBER_INT): null;
$deptid=isset($input['department_id']) ? filter_var($input['department_id'],FILTER_SANITIZE_NUMBER_INT): null;
$csrfToken=$input['csrf_token'];
if(!isloggedin() || $_SESSION['accounttype']!=='admin'){
    http_response_code(403);
    echo json_encode(['success'=>false,'message'=>'action not allowed']);
    die();
}
if(!empty($csrfToken)&&hash_equals($_SESSION['csrf_token'],$csrfToken)){
    $newUserdept= new DepartmentUserJoinedController($deptid,$userid);
    $result= $newUserdept->AddUserToDepartment();
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