<?php
class DepartmentUserJoinedController extends Department{

private $userid;
private $deptid;

private $errors;
public function __construct(int $deptid,int $userid){
    parent::__construct();
    $this->userid=$this->sanitizeData($userid);
    $this->deptid=$this->sanitizeData($deptid);

}
private function isEmpty()
{
    if ( empty($this->userid)||empty($this->deptid)
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

   private function userBelongsToDepartmentAlready(){
            return $this->checkUserAlreadyBelongsToDept($this->userid,$this->deptid);
                
            
   }
   
    public function AddUserToDepartment(){
    
        if($this->isEmpty()){
            $this->errors['empty']='Please provide a name';
        }
        if(!$this->userBelongsToDepartmentAlready()){
            $this->errors['has department']='User already belongs to department';
        }
        if(empty($this->errors)){
            $result=$this->addUserDepartment($this->userid,$this->deptid);
            if($result){
                return['success'=>true,'message'=>'User Added to department'];
            }else{
                return['success'=>false,'message'=>'Failed to add... try again'];
            }

        }else{
            return['success'=>false,'message'=>'Failed to add','errors'=>$this->errors];
        }
    }

}