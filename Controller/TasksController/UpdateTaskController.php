<?php
class UpdateTaskController extends Task{

    private $taskid;
    private $dateCompleted=null;

    private $status;
private $assignedBy;
private $assignedTo;
private $department;
private $name;
private $description=null;
private $duedate;

private $errors;
public function __construct($taskid,$name,$description,$duedate,$assignedBy,$assignedTo
,$department,$status,$dateCompleted=null){
    parent::__construct();
    $this->assignedBy=$this->sanitizeData($assignedBy);
    $this->taskid=$this->sanitizeData($taskid);
    $this->status=$this->sanitizeData($status);
   
    $this->assignedTo=$this->sanitizeData($assignedTo);
    $this->department=$this->sanitizeData($department);

   
    $this->name=$this->sanitizeData($name);
    
    $this->description= !empty($description) ?  $this->sanitizeData($description) : null;
    
    $this->duedate=$this->sanitizeData($duedate);
    $this->dateCompleted=!empty($dateCompleted)? $this->sanitizeData($dateCompleted):null;
    
}
private function isEmpty()
{
    if ( empty($this->name)||!isset($this->assignedBy) ||!isset($this->assignedTo)
     ||empty($this->duedate) ||empty($this->taskid)
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

   private function checkQualifications(){
    return $this->checkHeadOfDepartment($this->department, $this->assignedBy,
     $this->assignedTo);
     
   }
   
    public function UpdateNewTask(){
    
        if($this->isEmpty()){
            $this->errors['empty']="Please fill all fields";
        }
        if(!$this->checkQualifications()){
            $this->errors['unqualified']="Not Authorized";
        }
       
        if(empty($this->errors)){
            $result=$this->updateTask($this->name,$this->description,
            $this->duedate,$this->assignedBy,$this->assignedTo,
            $this->taskid,$this->status,$this->dateCompleted,$this->department);
            if($result['success']){
                $_SESSION['taskdetails']=$result['result'];
                return['success'=>true,'message'=>'Task Updated Successfully'];
            }else{
                return['success'=>false,'message'=>'Failed to update... try again'];
            }

        }else{
            return['success'=>false,'message'=>$this->errors['empty']??$this->errors['unqualified'],
            'errors'=>$this->errors];
        }
    }

}