<?php
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/payrolldb/Payroll.php';
include '../../Controller/PayrollController/SelectAllPayroll.php';
require '../../includes/sessions.php';
header('Content-Type: application/json');
$page= isset($_GET['page']) ? (int)($_GET['page']) : 1;
$limit=isset($_GET['limit']) ? (int)($_GET['limit']) : 10;
$search= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : " ";
$searchdate= isset($_GET['searchdate']) ? htmlspecialchars($_GET['searchdate']) : " ";

$offset=($page-1) * $limit;

$allpayroll=new SelectAllPayroll($limit,$offset,$search,$searchdate);
$totalpayroll= $allpayroll->getrecentMonthcount();
$payrolldetails=$allpayroll->getRecentMonthPayrolldetails();
$response=[
    'payrolls'=>$payrolldetails,
    'pagination'=>[
        'total_users'=>$totalpayroll,
        'current_page'=>$page,
        'total_pages'=>ceil($totalpayroll/$limit)
    ]
    ];
echo json_encode($response);
exit;