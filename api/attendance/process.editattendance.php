<?php
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/attendancedb/Attendance.php';
include '../../Controller/AttendanceController/SelectOneAttendanceRecord.php';
require '../../includes/sessions.php';

if($_SERVER['REQUEST_METHOD']=='GET'){
    $attendanceid=$_GET['id'];
    $userid=$_GET['userid'];

   
    $selectAttendanceRecord=new SelectOneAttendanceRecord($attendanceid,$userid);
   $selectAttendanceRecord->viewAttendanceDetail();
}