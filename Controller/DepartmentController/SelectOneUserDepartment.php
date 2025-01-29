<?php
class SelectOneUserDepartment extends Department{

private $limit=null;
private $offset=null;
private $search=null;

private $userid;
    public function __construct($limit=null,$offset=null,$search=null,$userid){
        parent::__construct();
        $limit=!empty($limit)?$this->sanitizeNumber($limit): null;
        $this->limit=!empty($limit) ?$this->sanitizeData($limit) : null;

        $offset=!empty($offset)?$this->sanitizeNumber($offset): null;
        $this->offset=!empty($offset) ?$this->sanitizeData($offset) : null;

        $this->search= !empty($search) ? $this->sanitizeData($search) : null;
        $this->userid=  $this->sanitizeData($userid);
       
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

    public function getUserDepartmentCount(){
            return $this->getOneUserDepartmentCount($this->userid);
    }
   
    

    public function getUserDepartmentProfileDetails(){
        $result= $this->getOneUserDepartmentDetails($this->limit,$this->offset,
        $this->search,$this->userid);
        if($result){
           return $result;
        }
        return [];
    }
}