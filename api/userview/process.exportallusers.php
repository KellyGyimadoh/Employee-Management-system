<?php
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/userdb/ViewUser.php';
include '../../Controller/UserController/ExportAllUsers.php';
require '../../includes/sessions.php';


$export = new ExportAllUsers();
echo $export->exportUsersCSV();