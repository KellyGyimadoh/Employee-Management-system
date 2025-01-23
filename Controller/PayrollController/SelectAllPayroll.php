<?php
class SelectAllPayroll extends Payroll{

private $limit;
private $offset;
private $search=null;

private $date;

    public function __construct($limit,$offset,$search=null,$date=null){
        parent::__construct();
        $limit=$this->sanitizeNumber($limit);
        $this->limit=$this->sanitizeData($limit);

        $offset=$this->sanitizeNumber($offset);
        $this->offset=$this->sanitizeData($offset);

        $this->search= !empty($search) ? $this->sanitizeData($search) : null;
       
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

    public function getAllPayrollCount(){
            return $this->getPayrollCount();
    }
   
    

    public function getPayrollProfileDetails(){
        $result= $this->getPayrollDetails($this->limit,$this->offset,$this->search,$this->date);
        if($result){
           return $result;
        }
        return [];
    }
}