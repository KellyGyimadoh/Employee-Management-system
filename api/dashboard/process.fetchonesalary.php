<?php
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/salarydb/Salary.php';
include '../../Controller/SalaryController/SelectOneSalary.php';
require '../../includes/sessions.php';
header('Content-Type: application/json');

$userid= isset($_GET['id']) ? htmlspecialchars($_GET['id']) : " ";



$oneSalary=new SelectOneSalary(null,$userid);

$salarydetails=$oneSalary->viewOneSalaryDetail();
$response=[
    'salary'=>$salarydetails,
    
    ];
echo json_encode($response);
exit;