<?php
include '../../includes/sessions.php';
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/payrolldb/Payroll.php';
include '../../Controller/PayrollController/CreatePayroll.php';
header("Content-type:application/json");
try {
    $input=json_decode(file_get_contents('php://input'),true);
    if(json_last_error()!==JSON_ERROR_NONE){
        throw new Exception('invalid json content');
    }
    $userid=filter_var($input['user_id'],FILTER_SANITIZE_NUMBER_INT);
    $totalSalary=filter_var($input['total_salary'],FILTER_SANITIZE_NUMBER_FLOAT);
    $status=filter_var($input['status'],FILTER_SANITIZE_SPECIAL_CHARS);
    $date=filter_var($input['date'],FILTER_SANITIZE_SPECIAL_CHARS);
    $duedate=filter_var($input['due_date'],FILTER_SANITIZE_SPECIAL_CHARS);
    $csrfToken=$input['csrf_token'];
    if(!isloggedin() || $_SESSION['accounttype']!=='admin'){
        http_response_code(403);
        echo json_encode(['success'=>false,'message'=>'action not allowed']);
        die();
    }
    
    if(!empty($csrfToken) && hash_equals($_SESSION['csrf_token'],$csrfToken)){
        $newpayroll= new CreatePayroll($userid,$date,$status,
        $totalSalary,$duedate);
        $result=$newpayroll->addNewPayroll();
        if($result['success']){
            $_SESSION['csrf_token']=bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time']=time();
        }
        echo json_encode($result);

    }else{
        http_response_code(403);
        echo json_encode(['success'=>false,'message'=>'invalid csrf token..try again']);
        
    }

} catch (Exception $e) {
    echo json_encode($e->getMessage());
}