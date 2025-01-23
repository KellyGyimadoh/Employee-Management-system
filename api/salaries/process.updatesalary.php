<?php
include '../../includes/sessions.php';
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/salarydb/Salary.php';
include '../../Controller/SalaryController/UpdateSalary.php';
header("Content-type:application/json");
try {
    $input=json_decode(file_get_contents('php://input'),true);
    if(json_last_error()!==JSON_ERROR_NONE){
        throw new Exception('invalid json content');
    }
    $salaryid=filter_var($input['id'],FILTER_SANITIZE_NUMBER_INT);
    $userid=filter_var($input['user_id'],FILTER_SANITIZE_NUMBER_INT);
    $baseSalary=filter_var($input['base_salary'],FILTER_SANITIZE_NUMBER_FLOAT);
    $bonus=filter_var($input['bonus'],FILTER_SANITIZE_NUMBER_FLOAT);
    $deductions=filter_var($input['deductions'],FILTER_SANITIZE_NUMBER_FLOAT);
    $overtime=filter_var($input['overtime'],FILTER_SANITIZE_NUMBER_FLOAT);
    $csrfToken=$input['csrf_token'];
    $totalSalary= round($baseSalary + $bonus + $overtime - $deductions,2);
    if(!isloggedin() || $_SESSION['accounttype']!=='admin'){
        http_response_code(403);
        echo json_encode(['success'=>false,'message'=>'action not allowed']);
        die();
    }
    
    if(!empty($csrfToken) && hash_equals($_SESSION['csrf_token'],$csrfToken)){
        $newsalary= new UpdateSalary($salaryid,$userid,$baseSalary,$bonus,
        $deductions,$overtime,$totalSalary);
        $result=$newsalary->updateNewSalary();
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