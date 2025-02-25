<?php
include '../../includes/sessions.php';
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/payrolldb/Payroll.php';
include '../../Controller/PayrollController/InsertNewPayrollRecord.php';
header("Content-Type:application/json");
try {
    $input=json_decode(file_get_contents("php://input"),true);
    if(json_last_error()!==JSON_ERROR_NONE){
        throw new Exception('invalid json format');

    }

$userid= filter_var($input['id'],FILTER_SANITIZE_NUMBER_INT);
$csrfToken=$input['csrf_token'];


if (
    !isloggedin() || 
    (
        $_SESSION['accounttype'] !== 'admin'
    )
) {
    $response=['success' => false, 'message' => 'Action not allowed'];
    echo json_encode($response);
    //http_response_code(403);

   
    die();
}



if(!empty($csrfToken)&&hash_equals($_SESSION['csrf_token'],$csrfToken)){
    $addPayroll= new InsertNewPayrollRecord($userid);
    $result= $addPayroll->AddNewPayrollRecord();
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