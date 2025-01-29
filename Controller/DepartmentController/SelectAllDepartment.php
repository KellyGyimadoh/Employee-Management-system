<?php
class SelectAllDepartment extends Department{

private $limit;
private $offset;
private $search=null;

private $deptid=null;

private $account_type=null;
    public function __construct($limit,$offset,$search=null,$deptid=null){
        parent::__construct();
        $limit=!empty($limit)?$this->sanitizeNumber($limit):null;
        $this->limit=$this->sanitizeData($limit);

        $offset=!empty($offset)?$this->sanitizeNumber($offset):null;
        $this->offset=!empty($offset)? $this->sanitizeData($offset) : null;

        $this->search= !empty($search) ? $this->sanitizeData($search) : null;
        $this->deptid= !empty($deptid) ? $this->sanitizeData($deptid) : null;
       
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

    public function getAllDepartmentCount(){
            return $this->getDepartmentCount();
    }
   
    

    public function getDepartmentProfileDetails(){
        $result= $this->getDepartmentDetails($this->limit,$this->offset,$this->search);
        if($result){
           return $result;
        }
        return [];
    }
public function getHeadOfDepartments(){
    return $this->getAllDepartmentHeads($this->deptid);
}

public function AllDepartments(){
    return $this->AllDepartmentCountAndDetail();
}
   
}