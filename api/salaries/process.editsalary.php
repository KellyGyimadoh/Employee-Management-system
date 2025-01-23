<?php
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/salarydb/Salary.php';
include '../../Controller/SalaryController/SelectOneSalary.php';
require '../../includes/sessions.php';

if($_SERVER['REQUEST_METHOD']=='GET'){
    $salaryid=$_GET['salaryid'];
    $userid=$_GET['userid'];

   
    $selectSalary=new SelectOneSalary($salaryid,$userid);
   $selectSalary->viewSalaryDetail();
}