<?php
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/taskdb/Task.php';
include '../../Controller/TasksController/SelectAllTasks.php';
require '../../includes/sessions.php';
header('Content-Type: application/json');
$page= isset($_GET['page']) ? (int)($_GET['page']) : 1;
$limit=isset($_GET['limit']) ? (int)($_GET['limit']) : 10;
$search= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : " ";
$date= isset($_GET['searchdate']) ? htmlspecialchars($_GET['searchdate']) : " ";
$status= isset($_GET['status']) ? htmlspecialchars($_GET['status']) : " ";


$offset=($page-1) * $limit;

$alltasks=new SelectAllTasks($limit,$offset,$search,$date,$status);


$totaltasks= $alltasks->getTodaycount();
$tasksdetails=$alltasks->getTodaysTask();
$response=[
    'tasks'=>$tasksdetails,
    'pagination'=>[
        'total_users'=>$totaltasks,
        'current_page'=>$page,
        'total_pages'=>ceil($totaltasks/$limit)
    ]
    ];
echo json_encode($response);
exit;