<?php
class CreateSalaryController extends Salary{

private $userid;
private $bonus;
private $overtime;
private $deductions;
private $baseSalary;

private $totalSalary;
private $errors;
public function __construct($userid,$baseSalary,$bonus,$deductions,$overtime,$totalSalary){
    parent::__construct();
    $this->userid=$this->sanitizeData($userid);
   
    $baseSalary=$this->sanitizeFloat($baseSalary);
    $this->baseSalary=$this->sanitizeData($baseSalary);

    $totalSalary=$this->sanitizeFloat($totalSalary);
    $this->totalSalary=$this->sanitizeData($totalSalary);
    
    
    $bonus=$this->sanitizeFloat($bonus);
    $this->bonus=$this->sanitizeData($bonus);
    
    $deductions=$this->sanitizeFloat($deductions);
    $this->deductions=$this->sanitizeData($deductions);
    
    $overtime=$this->sanitizeFloat($overtime);
    $this->overtime=$this->sanitizeData($overtime);
    
}
private function isEmpty()
{
    if ( !isset($this->userid)||!isset($this->baseSalary) ||!isset($this->bonus)
     ||!isset($this->deductions) ||!isset($this->overtime)||!isset($this->totalSalary)
    ) {

        return true;
    } else {
        return false;
    }
}


private function sanitizeFloat($number){
$number=filter_var($number,FILTER_SANITIZE_NUMBER_FLOAT);
return $number;
}
private function validateFloat($value) {
    return is_numeric($value) && $value >= 0; // Ensures it's a valid number and not negative
}

private function invalidFloat(){
if (!$this->validateFloat($this->baseSalary) || 
    !$this->validateFloat($this->bonus) || 
    !$this->validateFloat($this->deductions) || 
    !$this->validateFloat($this->overtime)) 
   
{
return true;
    }else{
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

   private function userHasSalaryAlready(){
            return $this->checkUserSalary($this->userid);
                
            
   }
   
    public function AddNewSalary(){
    
        if($this->isEmpty()){
            $this->errors['empty']="Please fill all fields";
        }
        if($this->invalidFloat()){
            $this->errors['not float']='Not a valid number';
        }
        if(!$this->userHasSalaryAlready()){
            $this->errors['has department']='User salary already set';
        }
       
        if(empty($this->errors)){
            $result=$this->addSalary($this->userid,$this->baseSalary,
            $this->bonus,$this->deductions,$this->overtime,$this->totalSalary);
            if($result){
                return['success'=>true,'message'=>'Worker Salary Added'];
            }else{
                return['success'=>false,'message'=>'Failed to add... try again'];
            }

        }else{
            return['success'=>false,'message'=>'Failed to add','errors'=>$this->errors];
        }
    }

}