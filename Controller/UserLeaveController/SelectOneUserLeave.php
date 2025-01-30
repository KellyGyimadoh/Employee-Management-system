<?php
class SelectOneUserLeave extends UserLeave{


private $leaveid;
    public function __construct($leaveid)
    {
        parent::__construct();
      
        $leaveid=$this->sanitizeNumber($leaveid);
        $this->leaveid=$this->sanitizeData($leaveid);


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

    public function viewUserLeaveDetail(){
        $result=$this->ViewOneUserLeaveRequest($this->leaveid);
        if($result['success']){
           

          $_SESSION['userleavedetails']=$result['result'];
            
            header("Location: ../../leaverequest/edit.php");
        }
    }
}