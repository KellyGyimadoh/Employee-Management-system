<?php
class SelectOnePayroll extends Payroll{

private $payrollid;
private $userid;
    public function __construct($payrollid,$userid)
    {
        parent::__construct();
        $payrollid=$this->sanitizeNumber($payrollid);
        $this->payrollid=$this->sanitizeData($payrollid);

        $userid=$this->sanitizeNumber($userid);
        $this->userid=$this->sanitizeData($userid);


    }
    private function sanitizeData($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
        return $data;
    }
    private function sanitizeNumber(int $number){
        $number=filter_var($number,FILTER_SANITIZE_NUMBER_INT);
        return $number;
    }

    public function viewPayrollDetail(){
        $result=$this->selectOnePayrollDetail($this->payrollid,$this->userid);
        if(!empty($result)){
           

                $_SESSION['payrolldetails']=$result;
            
            header("Location: ../../payroll/edit.php");
        }
    }
}