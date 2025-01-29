<?php
class SelectOneTask extends Task{


private $taskid;
    public function __construct($taskid)
    {
        parent::__construct();
      
        $taskid=$this->sanitizeNumber($taskid);
        $this->taskid=$this->sanitizeData($taskid);


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

    public function viewTaskDetail(){
        $result=$this->selectOneTask($this->taskid);
        if($result['success']){
           

          $_SESSION['taskdetails']=$result['result'];
            
            header("Location: ../../tasks/edit.php");
        }
    }
}