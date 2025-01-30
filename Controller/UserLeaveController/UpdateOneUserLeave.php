<?php
class UpdateOneUserLeave extends UserLeave{

private $leaveid;
private $approvedBy;

private $status;

private $errors;
public function __construct($leaveid,$status=null,$approvedBy=null){
    parent::__construct();
   
   
    $this->leaveid=$this->sanitizeData($leaveid);
    $this->approvedBy=!empty($approvedBy)?$this->sanitizeData($approvedBy):null;
    $this->status=!empty($status)?$this->sanitizeData($status): 2;


    
    
}
private function isEmpty()
{
    if ( empty($this->leaveid)
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
   
    public function ApproveUserLeaveRequest(){
    
        if($this->isEmpty()){
            $this->errors['empty']="Please fill all fields";
        }
        if($this->checkQualifications()){
            $this->errors['unqualified']="Already Have 2 leave request processing";
        }
       
        if(empty($this->errors)){
            $result=$this->UpdateOneNewLeaveStatus($this->leaveid,$this->status,$this->approvedBy);
            if($result){
              
                return['success'=>true,'message'=>'Leave Request Approved'];
            }else{
                return['success'=>false,'message'=>'Failed to update request leave... try again'];
            }

        }else{
            return['success'=>false,'message'=>$this->errors['empty']??$this->errors['unqualified'],
            'errors'=>$this->errors];
        }
    }

}