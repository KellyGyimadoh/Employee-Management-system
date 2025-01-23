<?php
class DepartmentUserDetails extends Department{

private $limit;
private $offset;
private $search=null;

private $id;

    public function __construct($limit,$offset,$search=null,$id){
        parent::__construct();
        //$id=$this->sanitizeNumber($id);
        $this->id=$this->sanitizeData($id);

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

    public function getAllDepartmentWorkersCount(){
            return $this->getDepartmentWorkerCount($this->id);
    }
   
    

    public function getDepartmentWorkersProfileDetails(){
        $departmentresult=$this->selectOneDepartmentDetail($this->id);
        $result= $this->getDepartmentWorkersDetails($this->limit,$this->offset,$this->search,$this->id);
        
        if($result){
            $_SESSION['departmentdetails']=$departmentresult;
           return $result;
        }
        return [];
    }
}