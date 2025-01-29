<?php
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/departmentdb/Department.php';
include '../../Controller/DepartmentController/SelectAllDepartment.php';
require '../../includes/sessions.php';
header('Content-Type: application/json');
$page= isset($_GET['page']) ? (int)($_GET['page']) : 1;
$limit=isset($_GET['limit']) ? (int)($_GET['limit']) : 10;
$search= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : " ";

$offset=($page-1) * $limit;

$alldpt=new SelectAllDepartment($limit,$offset,$search);
$totaldpt= $alldpt->getAllDepartmentCount();
$departmentdetails=$alldpt->AllDepartments();

$response=[
    'departments'=>$departmentdetails,
    'departments_total'=>$totaldpt
    ];
echo json_encode($response);
exit;