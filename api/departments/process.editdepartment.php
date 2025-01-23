<?php
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/departmentdb/Department.php';
include '../../Controller/DepartmentController/SelectOneDepartment.php';
require '../../includes/sessions.php';

if($_SERVER['REQUEST_METHOD']=='GET'){
    $id=$_GET['id'];
    $selectuser=new SelectOneDepartment($id);
    $selectuser->viewDepartmentDetail();
}