<?php
class SelectOneAttendanceRecord extends Attendance{

private $attendanceid;
private $userid;
    public function __construct($attendanceid,$userid)
    {
        parent::__construct();
        $attendanceid=$this->sanitizeNumber($attendanceid);
        $this->attendanceid=$this->sanitizeData($attendanceid);

        $userid=$this->sanitizeNumber($userid);
        $this->userid=$this->sanitizeData($userid);


    }
    private function sanitizeData($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
        return $data;
    }
    private function sanitizeNumber(int $number){
        $number=filter_var($number,FILTER_SANITIZE_NUMBER_INT);
        return $number;
    }

    public function viewAttendanceDetail(){
        $result=$this->selectOneAttendanceDetail($this->attendanceid,$this->userid);
        if(!empty($result)){
           

                $_SESSION['attendancedetails']=$result;
            
            header("Location: ../../attendance/edit.php");
        }
    }
}