<?php
include '../../includes/sessions.php';
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/taskdb/Task.php';
include '../../Controller/TasksController/UpdateTaskController.php';
header("Content-type:application/json");
try {
    $input=json_decode(file_get_contents('php://input'),true);
    if(json_last_error()!==JSON_ERROR_NONE){
        throw new Exception('invalid json content');
    }
    $taskid=filter_var($input['taskid'],FILTER_SANITIZE_NUMBER_INT);
    $deptid=filter_var($input['department_id'],FILTER_SANITIZE_NUMBER_INT);
    $status=filter_var($input['status'],FILTER_SANITIZE_NUMBER_INT);
    $assignedBy=filter_var($input['assigned_by'],FILTER_SANITIZE_NUMBER_INT);
    $assignedTo=filter_var($input['assigned_to'],FILTER_SANITIZE_NUMBER_INT);
    $taskname=filter_var($input['name'],FILTER_SANITIZE_SPECIAL_CHARS);
    $description=filter_var($input['description'],FILTER_SANITIZE_SPECIAL_CHARS);
    $duedate=filter_var($input['due_date'],FILTER_SANITIZE_SPECIAL_CHARS);
    $dateCompleted=filter_var($input['date_completed'],FILTER_SANITIZE_SPECIAL_CHARS);
    $csrfToken=$input['csrf_token'];
    
    if ($dateCompleted > $duedate) {
        $correctStatus = 3; // Late
    } elseif ($dateCompleted == $duedate) {
        $correctStatus = 2; // Completed
    } else {
        $correctStatus = 1; // Pending
    }
    
    if(!isloggedin() || $_SESSION['accounttype']!=='admin'){
        http_response_code(403);
        echo json_encode(['success'=>false,'message'=>'action not allowed']);
        die();
    }
    if((int)$status!==(int)$correctStatus){
        $response=['success' => false, 'message' => 'Action not allowed Status mistake'];
        echo json_encode($response);
        //http_response_code(403); 
        die();
    }

    if(!empty($csrfToken) && hash_equals($_SESSION['csrf_token'],$csrfToken)){
        $newtask= new UpdateTaskController($taskid,$taskname,$description,$duedate,
        $assignedBy,$assignedTo,$deptid,$status,$dateCompleted);
        $result=$newtask->UpdateNewTask();
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