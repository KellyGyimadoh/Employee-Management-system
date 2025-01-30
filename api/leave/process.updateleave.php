<?php
include '../../includes/sessions.php';
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/userleavedb/UserLeave.php';
include '../../Controller/UserLeaveController/UpdateUserLeave.php';
header("Content-Type:application/json");
try {
    $input=json_decode(file_get_contents("php://input"),true);
    if(json_last_error()!==JSON_ERROR_NONE){
        throw new Exception('invalid json format');

    }

$leaveid= filter_var($input['id'],FILTER_SANITIZE_NUMBER_INT);
$approvedBy=!empty($input['approved_by'])? 
filter_var($input['approved_by'],FILTER_SANITIZE_NUMBER_INT):null;
$status= !empty($input['status'])? filter_var($input['status'],FILTER_SANITIZE_NUMBER_INT):null;
$startDate= filter_var($input['start_date'],FILTER_SANITIZE_SPECIAL_CHARS);
$endDate= filter_var($input['end_date'],FILTER_SANITIZE_SPECIAL_CHARS);
$type= filter_var($input['type'],FILTER_SANITIZE_SPECIAL_CHARS);
$csrfToken=$input['csrf_token'];


if (strtotime($endDate) > strtotime($startDate . ' +30 days')) {
    $response=['success' => false, 'message' => 'The end date must be within 30 days from the start date.'];
    echo json_encode($response);
    die();
}
if (
    !isloggedin() || 
    (
        ($_SESSION['accounttype'] !== 'admin') &&
        (!isset($_SESSION['userinfo']['id']) || (int)$_SESSION['userinfo']['id'] !== (int)$userid)
    )
) {
    $response=['success' => false, 'message' => 'Action not allowed'];
    echo json_encode($response);
    //http_response_code(403);

   
    die();
}

if(!empty($csrfToken)&&hash_equals($_SESSION['csrf_token'],$csrfToken)){
    $newUserLeave= new UpdateUserLeave($leaveid,$startDate,
    $endDate,$type,$status,$approvedBy);
    $result= $newUserLeave->UpdateNewUserLeaveRequest();
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