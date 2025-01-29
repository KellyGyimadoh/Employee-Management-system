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
$userid= isset($_GET['id']) ? htmlspecialchars($_GET['id']) : " ";


$offset=($page-1) * $limit;
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
$alltasks=new SelectAllTasks($limit,$offset,$search,$date,$status,$userid);


$totaltasks= $alltasks->getOneUserTaskcount();
$tasksdetails=$alltasks->getOneUserTask();
$taskstatusCount=$alltasks->getOneUserTaskStatuscount();
$response=[
    'tasks'=>$tasksdetails,
    'total_tasks'=>$totaltasks,
    'taskstatuscount'=>$taskstatusCount,
    'pagination'=>[
        'total_users'=>$totaltasks,
        'current_page'=>$page,
        'total_pages'=>ceil($totaltasks/$limit)
        
    ]
    ];
echo json_encode($response);
exit;