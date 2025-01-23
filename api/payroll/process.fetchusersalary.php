<?php
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/payrolldb/Payroll.php';
include '../../Controller/PayrollController/WorkerTotalSalary.php';
require '../../includes/sessions.php';
header('Content-Type: application/json');
 
    $id = isset($_GET['id']) ? (int)$_GET['id'] : '';
 
    // Calculate offset
   
    $usersalary= new WorkerTotalSalary($id);
    $userdetails=$usersalary->getWorkerSalary();
    $response = [
        'salary' => $userdetails,
       
    ];

   
    echo json_encode($response);
    exit;
