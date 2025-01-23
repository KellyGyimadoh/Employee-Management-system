<?php
class DeleteDepartmentController extends Department{

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

    public function deleteDepartmentDetail(){
        $result=$this->deleteDepartment($this->id);
        if($result){
            return ['success'=>true,'message'=>'user deletion successful','redirecturl'=>'../../departments/index.php'];
        }else{
            return ['success'=>false,'message'=>'user deletion failed.. try again'];
        
        }
    }
    public function deleteDepartmentHead(){
        $result=$this->removeDepartmentHead($this->id);
        if($result){
            return ['success'=>true,'message'=>'HOD removed','redirecturl'=>'../../departments/index.php'];
        }else{
            return ['success'=>false,'message'=>' failed.. try again'];
        
        }
    }
}