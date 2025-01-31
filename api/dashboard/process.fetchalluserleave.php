<?php
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/userleavedb/UserLeave.php';
include '../../Controller/UserLeaveController/SelectAllUserLeaveRequest.php';
require '../../includes/sessions.php';
header('Content-Type: application/json');
$page= isset($_GET['page']) ? (int)($_GET['page']) : 1;
$limit=isset($_GET['limit']) ? (int)($_GET['limit']) : 10;
$search= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : " ";
$date= isset($_GET['searchdate']) ? htmlspecialchars($_GET['searchdate']) : " ";
$status= isset($_GET['status']) ? htmlspecialchars($_GET['status']) : " ";
$userid= isset($_GET['id']) ? htmlspecialchars($_GET['id']) : " ";


$offset=($page-1) * $limit;

$allUserLeaves=new SelectAllUserLeaveRequest($limit,$offset,
$search,$date,$status,$userid);


$totalLeaveRequest= $allUserLeaves->getAllUserLeaveCount();

$response=[
    'total_request'=>$totalLeaveRequest['leave_total'],
    'total_pending'=>$totalLeaveRequest['pending_total'],
    'total_rejected'=>$totalLeaveRequest['rejected_total'],
    'total_approved'=>$totalLeaveRequest['approved_total'],
    
    ];
echo json_encode($response);
exit;