<?php
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/userdb/ViewUser.php';
include '../../Controller/UserController/SelectOneUserProfile.php';
require '../../includes/sessions.php';

if($_SERVER['REQUEST_METHOD']=='GET'){
    $id=$_GET['userid'];
    $selectuser=new SelectOneUserProfile($id);
    $selectuser->viewUserDetail();
}