<?php
class UpdatePayroll extends Payroll{

private $payrollid;
private $status;
private $date=null;

private $duedate=null;
private $errors;
public function __construct($payrollid,$date,$status,$duedate){
    parent::__construct();
    $this->payrollid=$this->sanitizeData($payrollid);
    
    $this->status=$this->sanitizeData($status);
    
    
    
   
    $this->date=!empty($date) ? $this->sanitizeData($date): null;
    $this->duedate=!empty($duedate) ? $this->sanitizeData($duedate): null;
    
}
private function isEmpty()
{
    if ( empty($this->payrollid) ||empty($this->status)
    ) {

        return true;
    } else {
        return false;
    }
}
private function dateFormat($date){
   return date_format($date,DATE_RSS);
}

private function sanitizeFloat($number){
$number=filter_var($number,FILTER_SANITIZE_NUMBER_FLOAT);
return $number;
}
private function validateFloat($value) {
    return is_numeric($value) && $value >= 0; // Ensures it's a valid number and not negative
}


private function sanitizeData($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
        $data = htmlspecialchars($data);
        return $data;
    }

   
   
    public function updateNewPayroll(){
    
        if($this->isEmpty()){
            $this->errors['empty']="Please fill all fields";
        }
      
        if(empty($this->errors)){
            $result=$this->updatePayroll($this->payrollid,$this->status,
            $this->date,$this->duedate);
            if($result['success']){
                $_SESSION['payrolldetails']=$result['result'];
                return['success'=>true,'message'=>'Worker Payroll Updated'];
            }else{
                return['success'=>false,'message'=>'Failed to add... try again'];
            }

        }else{
            return['success'=>false,'message'=>'Failed to add','errors'=>$this->errors];
        }
    }

}