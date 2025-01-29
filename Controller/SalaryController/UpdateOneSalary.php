<?php
class UpdateOneSalary extends Salary{


private $userid;
private $amount;

private $errors;
public function __construct($userid,$amount){
    parent::__construct();
   
    $this->userid=$this->sanitizeData($userid);
   
    $amount=$this->sanitizeFloat($amount);
    $this->amount=$this->sanitizeData($amount);
    $this->amount=$this->sanitizeUpdateFloat($amount);

    
}
private function isEmpty()
{
    if ( !isset($this->userid)||!isset($this->amount) 
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
if (!$this->validateFloat($this->amount))
   
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

  
   
    public function updateDeductionForSalary(){
    
        if($this->isEmpty()){
            $this->errors['empty']="Please fill all fields";
        }
        if($this->invalidFloat()){
            $this->errors['not float']='Not a valid number';
        }
       
        if(empty($this->errors)){
            $result=$this->updateSalaryDeduction($this->userid,$this->amount);
            if ($result) {
                if (isloggedin() && isset($_SESSION['accounttype']) && in_array($_SESSION['accounttype'], ['admin'])) {

                    return ['success' => true, 'message' => $this->amount.' deducted from Salary',];
                } else {

                    return ['success' => false, 'message' => 'Not allowed',];
                }
            } else {
                return ['success' => false, 'message' => 'Failed to deduct salary... try again'];
            }

        }else{
            return['success'=>false,'message'=>'Failed to deduct','errors'=>$this->errors];
        }
    }
    public function updateBonusForSalary(){
    
        if($this->isEmpty()){
            $this->errors['empty']="Please fill all fields";
        }
        if($this->invalidFloat()){
            $this->errors['not float']='Not a valid number';
        }
       
        if(empty($this->errors)){
            $result=$this->updateSalaryBonus($this->userid,$this->amount);
            if ($result) {
                if (isloggedin() && isset($_SESSION['accounttype']) && in_array($_SESSION['accounttype'], ['admin'])) {

                    return ['success' => true, 'message' => $this->amount.' added to Salary',];
                } else {

                    return ['success' => false, 'message' => 'Not allowed',];
                }
            } else {
                return ['success' => false, 'message' => 'Failed to add to salary... try again'];
            }

        }else{
            return['success'=>false,'message'=>'Failed to add','errors'=>$this->errors];
        }
    }

}