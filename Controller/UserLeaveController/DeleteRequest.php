<?php
class DeleteRequest extends UserLeave{

private $id;

private $errors;
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

    private function isempty(){
        return empty($this->id);
    }

    public function deleteUserLeave(){
        if($this->isempty()){
            $this->errors['emptyfield']='No valid Id';
        }
        if(empty($this->errors)){
        $result=$this->DeleteNewLeave($this->id);
        $accounttype=$_SESSION['accounttype'];
        if($result){
            return $accounttype=='admin'? 
            ['success'=>true,'message'=>'Request Deletion Successful',
            'redirecturl'=>'../../leaverequest/']
            : 
            ['success'=>true,'message'=>'Request Deletion Successful',
            'redirecturl'=>'../../leaverequest/show.php']
            ;
        }else{
            return ['success'=>false,'message'=>'Request Deletion failed.. try again'];
        
        }
    }else{
        return ['success'=>false,'message'=>'Request Deletion failed.. try again'??$this->errors['emptyfield'],
        'errors'=>$this->errors];
   
    }
    }
    
}