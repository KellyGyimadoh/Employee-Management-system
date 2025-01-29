<?php
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/salarydb/Salary.php';
include '../../Controller/SalaryController/SelectAllSalary.php';
require '../../includes/sessions.php';
header('Content-Type: application/json');
$page= isset($_GET['page']) ? (int)($_GET['page']) : 1;
$limit=isset($_GET['limit']) ? (int)($_GET['limit']) : 10;
$search= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : " ";

$offset=($page-1) * $limit;

$allsalary=new SelectAllSalary($limit,$offset,$search);
$salarydetailsCount=$allsalary->allSalaryCount();

$response=[
    'salaries'=>$salarydetailsCount,
    ]
    ;
echo json_encode($response);
exit;