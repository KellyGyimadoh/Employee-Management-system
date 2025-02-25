<?php
class InsertNewPayrollRecord extends Payroll{

private $userid;
private $errors;
public function __construct($userid){
    parent::__construct();
    $this->userid=$this->sanitizeData($userid);
    
    
}
private function isEmpty()
{
    if ( empty($this->userid)
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

   
   
    public function AddNewPayrollRecord(){
    
        if($this->isEmpty()){
            $this->errors['empty']="Invalid User action";
        }
       
        if(empty($this->errors)){
            $result=$this->InsertNewPayrollRecord();

            return $result;
           

        }else{
            return['success'=>false,'message'=>$this->errors['empty'],'errors'=>$this->errors];
        }
    }

}