<?php
class AddTaskController extends Task{

private $assignedBy;
private $assignedTo;
private $department;
private $name;
private $description=null;
private $duedate;

private $errors;
public function __construct($name,$description,$duedate,$assignedBy,$assignedTo,$department){
    parent::__construct();
    $this->assignedBy=$this->sanitizeData($assignedBy);
   
    $this->assignedTo=$this->sanitizeData($assignedTo);
    $this->department=$this->sanitizeData($department);

   
    $this->name=$this->sanitizeData($name);
    
    $this->description= !empty($description) ?  $this->sanitizeData($description) : null;
    
    $this->duedate=$this->sanitizeData($duedate);
    
    
}
private function isEmpty()
{
    if ( empty($this->name)||!isset($this->assignedBy) ||!isset($this->assignedTo)
     ||empty($this->duedate)
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
   
    public function AddNewTask(){
    
        if($this->isEmpty()){
            $this->errors['empty']="Please fill all fields";
        }
        if(!$this->checkQualifications()){
            $this->errors['unqualified']="Not Authorized";
        }
       
        if(empty($this->errors)){
            $result=$this->insertNewTask($this->name,$this->description,
            $this->duedate,$this->assignedBy,$this->assignedTo,
            $this->department);
            if($result){
                return['success'=>true,'message'=>'New Task Added'];
            }else{
                return['success'=>false,'message'=>'Failed to add... try again'];
            }

        }else{
            return['success'=>false,'message'=>$this->errors['empty']??$this->errors['unqualified'],
            'errors'=>$this->errors];
        }
    }

}