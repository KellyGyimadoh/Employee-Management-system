<?php
class SelectAllAttendance extends Attendance{

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
        $this->date= !empty($date) ? $this->sanitizeData($date) : null;

        
       
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

    public function getAllAttendanceCount(){
            return $this->getAttendanceCount();
    }
   
    
    public function getAttendanceRecords(){
        $result= $this->getAllAttendance($this->limit,$this->offset,$this->search,$this->date);
        if($result){
           return $result;
        }
        return [];
    }

    //recent  month
    
    public function getAllTodayAttendance(){
        $result= $this->getAttendanceToday($this->limit,$this->offset,$this->search,$this->date);
        if($result){
           return $result;
        }
        return [];
    }

    public function getTodayAttendanceCount(){
        return $this->getTodayTotalCount();
    }

    public function AllAttendanceTodayProfile(){
        return $this->getTodayTotalDetails() ?: $this->getTodayTotalDetails();
    }
}