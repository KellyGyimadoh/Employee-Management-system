<?php
include '../../includes/sessions.php';
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/attendancedb/Attendance.php';
include '../../Controller/AttendanceController/UpdateUserAttendance.php';
header("Content-Type:application/json");
try {
    $input=json_decode(file_get_contents("php://input"),true);
    if(json_last_error()!==JSON_ERROR_NONE){
        throw new Exception('invalid json format');

    }

$userid= filter_var($input['id'],FILTER_SANITIZE_NUMBER_INT);
$csrfToken=$input['csrf_token'];
$currentDate = date('Y-m-d');
$currentTime = date('H:i:s');

// Determine if the user is late or on time (8:00:00 is the threshold)
$status = ($currentTime <= '08:00:00') ? 2 : 3; // 2 = Present, 3 = Late
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
    $newattendance= new UpdateUserAttendance($userid,$currentDate,
    $status,$currentTime);
    $result= $newattendance->updateNewUserAttendance();
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