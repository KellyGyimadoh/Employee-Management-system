<?php
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/departmentdb/Department.php';
include '../../Controller/DepartmentController/SelectOneUserDepartment.php';
require '../../includes/sessions.php';
header('Content-Type: application/json');
$page= isset($_GET['page']) ? (int)($_GET['page']) : 1;
$limit=isset($_GET['limit']) ? (int)($_GET['limit']) : 10;
$search= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : " ";
$userid= isset($_GET['id']) ? htmlspecialchars($_GET['id']) : " ";

$offset=($page-1) * $limit;

$userdpt=new SelectOneUserDepartment($limit,$offset,$search,$userid);
$totaldpt= $userdpt->getUserDepartmentCount();
$departmentdetails=$userdpt->getUserDepartmentProfileDetails();

$response=[
    'departments'=>$departmentdetails,
    'pagination'=>[
        'total_users'=>$totaldpt,
        'current_page'=>$page,
        'total_pages'=>ceil($totaldpt/$limit)
    ]
    ];
echo json_encode($response);
exit;