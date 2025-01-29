<?php
class SelectAllSalary extends Salary{

private $limit;
private $offset;
private $search=null;

    public function __construct($limit,$offset,$search=null){
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

    public function getAllSalaryCount(){
            return $this->getSalaryCount();
    }
   
    

    public function getSalaryProfileDetails(){
        $result= $this->getSalaryDetails($this->limit,$this->offset,$this->search);
        if($result){
           return $result;
        }
        return [];
    }

    public function allSalaryCount(){
        $result= $this->getTotalSalaryCount();
        if($result){
           return $result;
        }
        return [];
    }
    
}