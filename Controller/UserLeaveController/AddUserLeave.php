<?php
class AddUserLeave extends UserLeave{

private $userid;
private $startDate;
private $endDate;
private $type;

private $errors;
public function __construct($userid,$startDate,$endDate,$type){
    parent::__construct();
    $this->type=$this->sanitizeData($type);
   
    $this->userid=$this->sanitizeData($userid);
    
    $this->startDate=$this->sanitizeData($startDate);
    $this->endDate=$this->sanitizeData($endDate);


    
    
}
private function isEmpty()
{
    if ( empty($this->type) ||!isset($this->userid)
     ||empty($this->startDate) ||empty($this->endDate)
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
    return $this->checkLeave($this->userid);
     
   }
   
    public function AddNewUserLeaveRequest(){
    
        if($this->isEmpty()){
            $this->errors['empty']="Please fill all fields";
        }
        if($this->checkQualifications()){
            $this->errors['unqualified']="Already Have 2 leave request processing";
        }
       
        if(empty($this->errors)){
            $result=$this->InsertNewLeave($this->userid,$this->startDate,
            $this->endDate,$this->type);
            if($result){
                return['success'=>true,'message'=>'Leave Request Successful'];
            }else{
                return['success'=>false,'message'=>'Failed to request leave... try again'];
            }

        }else{
            return['success'=>false,'message'=>$this->errors['empty']??$this->errors['unqualified'],
            'errors'=>$this->errors];
        }
    }

}