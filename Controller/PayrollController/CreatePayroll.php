<?php
class CreatePayroll extends Payroll{

private $userid;
private $status;
private $date=null;
private $duedate=null;

private $totalSalary;
private $errors;
public function __construct($userid,$date,$status,$totalSalary,$duedate){
    parent::__construct();
    $this->userid=$this->sanitizeData($userid);
   
    

    $totalSalary=$this->sanitizeFloat($totalSalary);
    $this->totalSalary=$this->sanitizeData($totalSalary);
    
    $this->status=$this->sanitizeData($status);
    
    
    
   
    $this->date=!empty($date) ? $this->sanitizeData($date) : null;
    $this->duedate=!empty($duedate) ? $this->sanitizeData($duedate) : null;
    
}
private function isEmpty()
{
    if ( empty($this->userid) ||empty($this->status)
      ||!isset($this->totalSalary)
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

private function invalidFloat(){
if (!$this->validateFloat($this->totalSalary)) 
   
{
return true;
    }else{
        return false;
    }
}

private function salariesUnmatch(){
    return $this->checkSalaryAmount($this->userid,$this->totalSalary);
}
private function sanitizeData($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
        $data = htmlspecialchars($data);
        return $data;
    }

   
   
    public function AddNewPayroll(){
    
        if($this->isEmpty()){
            $this->errors['empty']="Please fill all fields";
        }
        if($this->invalidFloat()){
            $this->errors['not float']='Not a valid number';
        }
        if(!$this->salariesUnMatch()){
            $this->errors['salary amount wrong']='Workers Salary has wrong amount';
        }
      
        if(empty($this->errors)){
            $result=$this->addPayroll($this->userid,$this->status,
            $this->date,$this->totalSalary,$this->duedate);
            if($result){
                return['success'=>true,'message'=>'Worker Payroll Added'];
            }else{
                return['success'=>false,'message'=>'Failed to add... try again'];
            }

        }else{
            return['success'=>false,'message'=>'Failed to add','errors'=>$this->errors];
        }
    }

}