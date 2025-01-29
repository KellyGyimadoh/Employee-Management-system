<?php
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/departmentdb/Department.php';
include '../../Controller/DepartmentController/SelectAllDepartment.php';
require '../../includes/sessions.php';
header('Content-Type: application/json');

$deptid=isset($_GET['id']) ? (int)($_GET['id']) : null;


$allheads=new SelectAllDepartment(null,null,null,$deptid);

$departmentHeadDetails=$allheads->getHeadOfDepartments();

$response=[
    'departmentheads'=>$departmentHeadDetails
    
    ];
echo json_encode($response);
exit;