<?php
class UpdatePayrollPayment extends Payroll{

private $payrollid;
private $date;

private $errors;
public function __construct($payrollid,$date){
    parent::__construct();
    $this->payrollid=$this->sanitizeData($payrollid);
    
    $this->date= $this->sanitizeData($date);
    
    
}
private function isEmpty()
{
    if ( empty($this->payrollid) ||empty($this->date)
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
private function checkStatus(){
    return $this->checkPaymentStatus($this->payrollid);
}
   
   
    public function makeNewPayrollPayment(){
    
        if($this->isEmpty()){
            $this->errors['empty']="Please fill all fields";
        }
        if($this->checkStatus()){
            $this->errors['paidalready']='Status already completed';
        }
      
        if(empty($this->errors)){
            $result=$this->makePaymentForPayroll($this->payrollid,$this->date);
            if($result){
                return['success'=>true,'message'=>'Payment Status Successful'];
            }else{
                return['success'=>false,'message'=>'Failed..try again'];
            }

        }else{
            return['success'=>false,'message'=>$this->errors['paidalready'],'errors'=>$this->errors];
        }
    }

}