<?php
class UpdateOneTask extends Task{

    private $taskid;
    private $status;
    private $dateCompleted;
  

private $errors;
public function __construct($taskid,$dateCompleted=null,$status=null){
    parent::__construct();
    
    $this->taskid=$this->sanitizeData($taskid);
    
    $this->dateCompleted=!empty($dateCompleted)?$this->sanitizeData($dateCompleted) : null;
    $this->status=!empty($status)?$this->sanitizeData($status):1;
}
private function isEmpty()
{
    if ( empty($this->taskid)
    ) {

        return true;
    } else {
        return false;
    }
}

private function sanitizeData($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
        $data = htmlspecialchars($data);
        return $data;
    }

  
   
    public function UpdateNewTask(){
    
        if($this->isEmpty()){
            $this->errors['empty']="Please fill all fields";
        }
       
        if(empty($this->errors)){
            $result=$this->updateOneTask($this->dateCompleted,$this->taskid,
            $this->status);
            if($result){
                return['success'=>true,'message'=>'Task Marked As Done Successfully'];
            }else{
                return['success'=>false,'message'=>'Failed to update... try again'];
            }

        }else{
            return['success'=>false,'message'=>$this->errors['empty']??$this->errors['unqualified'],
            'errors'=>$this->errors];
        }
    }

    public function UnMarkNewTask(){
    
        if($this->isEmpty()){
            $this->errors['empty']="Please fill all fields";
        }
       
        if(empty($this->errors)){
            $result=$this->unMarkOneTask($this->taskid,
            $this->status);
            if($result){
                return['success'=>true,'message'=>'Task UnMarked'];
            }else{
                return['success'=>false,'message'=>'Failed to unmark... try again'];
            }

        }else{
            return['success'=>false,'message'=>$this->errors['empty'],
            'errors'=>$this->errors];
        }
    }

}