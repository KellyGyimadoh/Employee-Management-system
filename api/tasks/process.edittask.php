<?php
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/taskdb/Task.php';
include '../../Controller/TasksController/SelectOneTask.php';
require '../../includes/sessions.php';

if($_SERVER['REQUEST_METHOD']=='GET'){
    $taskid=$_GET['taskid'];
   

   
    $selectTaskRecord=new SelectOneTask($taskid);
  $selectTaskRecord->viewTaskDetail();


}