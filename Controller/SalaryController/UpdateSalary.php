<?php
class UpdateSalary extends Salary{

private $salaryid;
private $userid;
private $bonus;
private $overtime;
private $deductions;
private $baseSalary;

private $totalSalary;
private $errors;
public function __construct($salaryid,$userid,$baseSalary,$bonus,$deductions,$overtime,$totalSalary){
    parent::__construct();
    $this->salaryid=$this->sanitizeData($salaryid);
    $this->userid=$this->sanitizeData($userid);
   
    $baseSalary=$this->sanitizeFloat($baseSalary);
    $this->baseSalary=$this->sanitizeData($baseSalary);
    $this->baseSalary=$this->sanitizeUpdateFloat($baseSalary);

    $totalSalary=$this->sanitizeFloat($totalSalary);
    $this->totalSalary=$this->sanitizeData($totalSalary);
    $this->totalSalary=$this->sanitizeUpdateFloat($totalSalary);
    
    
    $bonus=$this->sanitizeFloat($bonus);
    $this->bonus=$this->sanitizeData($bonus);
    $this->bonus=$this->sanitizeUpdateFloat($bonus);
    
    $deductions=$this->sanitizeFloat($deductions);
    $this->deductions=$this->sanitizeData($deductions);
    $this->deductions=$this->sanitizeUpdateFloat($deductions);
    
    $overtime=$this->sanitizeFloat($overtime);
    $this->overtime=$this->sanitizeData($overtime);
    $this->overtime=$this->sanitizeUpdateFloat($overtime);
    
}
private function isEmpty()
{
    if ( !isset($this->userid)||!isset($this->baseSalary) ||!isset($this->bonus)
     ||!isset($this->deductions) ||!isset($this->overtime)||!isset($this->totalSalary)||!isset($this->salaryid)
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
private function sanitizeUpdateFloat($float)
{
    return number_format((float)$float, 2, '.', '');
}
private function sanitizeData($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
        $data = htmlspecialchars($data);
        return $data;
    }

  
   
    public function updateNewSalary(){
    
        if($this->isEmpty()){
            $this->errors['empty']="Please fill all fields";
        }
        if($this->invalidFloat()){
            $this->errors['not float']='Not a valid number';
        }
       
        if(empty($this->errors)){
            $result=$this->updateTheSalary($this->salaryid,$this->userid,$this->baseSalary,
            $this->bonus,$this->deductions,$this->overtime,$this->totalSalary);
            if ($result['success']) {
                if (isset($_SESSION['salarydetails'])) {
                    unset($_SESSION['salarydetails']);
                    $_SESSION['salarydetails'] = $result['salary'];
                }
                if (isloggedin() && isset($_SESSION['accounttype']) && in_array($_SESSION['accounttype'], ['admin'])) {

                    return ['success' => true, 'message' => 'Worker Salary details successfully updated',];
                } else {

                    return ['success' => false, 'message' => 'Not allowed',];
                }
            } else {
                return ['success' => false, 'message' => 'Failed to update... try again'];
            }

        }else{
            return['success'=>false,'message'=>'Failed to add','errors'=>$this->errors];
        }
    }

}