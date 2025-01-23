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
$totalsal= $allsalary->getAllSalaryCount();
$salarydetails=$allsalary->getSalaryProfileDetails();

$response=[
    'salaries'=>$salarydetails,
    'pagination'=>[
        'total_users'=>$totalsal,
        'current_page'=>$page,
        'total_pages'=>ceil($totalsal/$limit)
    ]
    ];
echo json_encode($response);
exit;