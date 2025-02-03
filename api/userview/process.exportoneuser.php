<?php
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/userdb/ViewUser.php';
include '../../Controller/UserController/ExportOneUserProfile.php';
require '../../includes/sessions.php';


if (isset($_GET['id'])) {
    $export = new ExportOneUserProfile($_GET['id']);
    $export->exportUserDetail();
} else {
    echo "User ID is missing!";
}