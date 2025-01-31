<?php
class SelectOneSalary extends Salary{

private $salaryid;
private $userid;
    public function __construct($salaryid=null,$userid)
    {
        parent::__construct();
        $salaryid=!empty($salaryid)?$this->sanitizeNumber($salaryid):'';
        $this->salaryid=!empty($salaryid)?$this->sanitizeData($salaryid):'';

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

    public function viewSalaryDetail(){
        $result=$this->selectOneSalaryDetail($this->salaryid,$this->userid);
        if(!empty($result)){
           

                $_SESSION['salarydetails']=$result;
            
            header("Location: ../../salaries/edit.php");
        }
    }

    public function viewOneSalaryDetail(){
        $result=$this->selectUserSalaryDetail($this->userid);
        return $result ? $result : [];
    }
}