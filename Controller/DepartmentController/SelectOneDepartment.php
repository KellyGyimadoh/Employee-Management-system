<?php
class SelectOneDepartment extends Department{

private $id;
    public function __construct($id)
    {
        parent::__construct();
        $id=$this->sanitizeNumber($id);
        $this->id=$this->sanitizeData($id);

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

    public function viewDepartmentDetail(){
        $result=$this->selectOneDepartmentDetail($this->id);
        $workerdetails=$this->getAllDepartmentWorkersDetails($this->id);
        if(!empty($result)){
            $_SESSION['departmentdetails']=$result;
            if(!empty($workerdetails)){
                $_SESSION['departmentworkerdetails']=$workerdetails;
            }
            header("Location: ../../departments/edit.php");
        }
    }

   
}