<?php
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/payrolldb/Payroll.php';
include '../../Controller/PayrollController/SelectOnePayroll.php';
require '../../includes/sessions.php';

if($_SERVER['REQUEST_METHOD']=='GET'){
    $payrollid=$_GET['payrollid'];
    $userid=$_GET['userid'];

   
    $selectPayroll=new SelectOnePayroll($payrollid,$userid);
   $selectPayroll->viewPayrollDetail();
}