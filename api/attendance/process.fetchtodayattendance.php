<?php
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/attendancedb/Attendance.php';
include '../../Controller/AttendanceController/SelectAllAttendance.php';
require '../../includes/sessions.php';
header('Content-Type: application/json');
$page= isset($_GET['page']) ? (int)($_GET['page']) : 1;
$limit=isset($_GET['limit']) ? (int)($_GET['limit']) : 10;
$search= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : " ";
$date= isset($_GET['searchdate']) ? htmlspecialchars($_GET['searchdate']) : " ";


$offset=($page-1) * $limit;

$allattendance=new SelectAllAttendance($limit,$offset,$search,$date);


$totalattendance= $allattendance->getTodayAttendanceCount();
$attendancedetails=$allattendance->getAllTodayAttendance();
$response=[
    'attendances'=>$attendancedetails,
    'pagination'=>[
        'total_users'=>$totalattendance,
        'current_page'=>$page,
        'total_pages'=>ceil($totalattendance/$limit)
    ]
    ];
echo json_encode($response);
exit;