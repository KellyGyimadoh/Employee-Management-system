<?php
class UpdateUserLeave extends UserLeave{

private $leaveid;
private $approvedBy;
private $startDate;
private $endDate;
private $type;
private $status;

private $errors;
public function __construct($leaveid,$startDate,$endDate,$type,$status=null,$approvedBy=null){
    parent::__construct();
    $this->type=$this->sanitizeData($type);
   
    $this->leaveid=$this->sanitizeData($leaveid);
    $this->approvedBy=!empty($approvedBy)?$this->sanitizeData($approvedBy):null;
    $this->status=!empty($status)?$this->sanitizeData($status): null;
    
    $this->startDate=$this->sanitizeData($startDate);
    $this->endDate=$this->sanitizeData($endDate);


    
    
}
private function isEmpty()
{
    if ( empty($this->leaveid)||!isset($this->type)
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
    return $this->checkLeave($this->leaveid);
     
   }
   
    public function UpdateNewUserLeaveRequest(){
    
        if($this->isEmpty()){
            $this->errors['empty']="Please fill all fields";
        }
        if($this->checkQualifications()){
            $this->errors['unqualified']="Already Have 2 leave request processing";
        }
       
        if(empty($this->errors)){
            $result=$this->UpdateNewLeave($this->leaveid,$this->startDate,
            $this->endDate,$this->type,$this->status,$this->approvedBy);
            if($result['success']){
                $_SESSION['userleavedetails']=$result['result'];
                return['success'=>true,'message'=>'Leave Request Update Successful'];
            }else{
                return['success'=>false,'message'=>'Failed to update request leave... try again'];
            }

        }else{
            return['success'=>false,'message'=>$this->errors['empty']??$this->errors['unqualified'],
            'errors'=>$this->errors];
        }
    }

}