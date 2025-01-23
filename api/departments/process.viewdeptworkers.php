<?php
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/departmentdb/Department.php';
include '../../Controller/DeptUserController/DepartmentUserDetails.php';
require '../../includes/sessions.php';
header('Content-Type: application/json');
$page= isset($_GET['page']) ? (int)($_GET['page']) : 1;
$limit=isset($_GET['limit']) ? (int)($_GET['limit']) : 10;
$id=isset($_GET['deptid']) ? (int)($_GET['deptid']) : null ;
$search= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : " ";

$offset=($page-1) * $limit;

$alldptworkers=new DepartmentUserDetails($limit,$offset,$search,$id);
$totaldpt= $alldptworkers->getAllDepartmentWorkersCount();
$departmentdetails=$alldptworkers->getDepartmentWorkersProfileDetails();

$response=[
    'departmentworkers'=>$departmentdetails,
    'pagination'=>[
        'total_users'=>$totaldpt,
        'current_page'=>$page,
        'total_pages'=>ceil($totaldpt/$limit)
    ]
    ];
echo json_encode($response);
exit;