<?php
class SelectAllUserLeaveRequest extends UserLeave{

private $limit;
private $offset;
private $search=null;

private $status=null;
private $date=null;

private $userid=null;

    public function __construct($limit,$offset,$search=null,$date=null,$status=null,$userid=null){
        parent::__construct();
        $limit=$this->sanitizeNumber($limit);
        $this->limit=$this->sanitizeData($limit);

        $offset=$this->sanitizeNumber($offset);
        $this->offset=$this->sanitizeData($offset);

        $this->search= !empty($search) ? $this->sanitizeData($search) : null;
        $this->date= !empty($date) ? $this->sanitizeData($date) : null;
        $this->status= !empty($status) ? $this->sanitizeData($status) : null;
        $this->userid= !empty($userid) ? $this->sanitizeData($userid) : null;

        
       
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

    public function getAllUserLeaveCount(){
            return $this->SelectAllUserLeaveRequestCount();
    }
   
    
    public function getAllUserLeaveDetails(){
        $result= $this->SelectAllUserLeaveRequest($this->limit,$this->offset,
        $this->search,$this->date,$this->status);
        if($result){
           return $result;
        }
        return [];
    }

    //recent  month
    

    public function getOneUserLeaveRequest(){
        $result= $this->SelectOneUserLeaveRequest($this->limit,$this->offset,
        $this->search,$this->status,$this->userid);
        if($result){
           return $result;
        }
        return [];
    }

    public function getOneUserLeavecount(){
        return $this->SelectOneUserLeaveRequestCount($this->userid);
    }
   

    
}