<?php
class WorkerTotalSalary extends Payroll{

private $userid;


    public function __construct($userid){
        parent::__construct();
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

   
    

    public function getWorkerSalary(){
        $result= $this->getSalaryDetail($this->userid);
        if($result){
           return $result;
        }
        return [];
    }
}