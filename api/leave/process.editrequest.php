<?php
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/userleavedb/UserLeave.php';
include '../../Controller/UserLeaveController/SelectOneUserLeave.php';
require '../../includes/sessions.php';

if($_SERVER['REQUEST_METHOD']=='GET'){
    $leaveid=$_GET['leaveid'];
   

   
    $selectUserLeaveRecord=new SelectOneUserLeave($leaveid);
  $selectUserLeaveRecord->viewUserLeaveDetail();

}