<?php
require '../DatabaseModel/configdb/Dbconnection.php';
$test=new Dbconnection();
echo ($test->connect_to_database() ? 'connected' : 'not connected');