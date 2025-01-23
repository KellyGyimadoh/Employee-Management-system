<?php
class UpdateUserAttendance extends Attendance{

private $userid;
private $status;
private $currentdate;

private $checkinTime;
private $errors;
public function __construct($userid,$currentdate,$status,$checkinTime){
    parent::__construct();
    $this->userid=$this->sanitizeData($userid);
    
    $this->status=$this->sanitizeData($status); 
   
    $this->currentdate= $this->sanitizeData($currentdate);
    $this->checkinTime= $this->sanitizeData($checkinTime);
    
}
private function isEmpty()
{
    if ( empty($this->userid) ||empty($this->status)||!isset($this->checkinTime)
    ||!isset($this->currentdate)
    ) {

        return true;
    } else {
        return false;
    }
}

private function userCheckedInToday(){
    return $this->alreadyCheckedIn($this->userid,$this->currentdate);
}
private function dateTimeFormat($dateTimeString) {
    $format = 'Y-m-d H:i:s'; // Format for date and time
    $dateTime = DateTime::createFromFormat($format, $dateTimeString);

    // Check if the date-time string is valid
    if ($dateTime && $dateTime->format($format) === $dateTimeString) {
        return $dateTime->format(DATE_RSS); // Return RSS format
    } else {
        return "Invalid date-time: " . $dateTimeString;
    }
}


private function sanitizeFloat($number){
$number=filter_var($number,FILTER_SANITIZE_NUMBER_INT);
return $number;
}



private function sanitizeData($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
        $data = htmlspecialchars($data);
        return $data;
    }

   
   
    public function updateNewUserAttendance(){
    
        if($this->isEmpty()){
            $this->errors['empty']="Please fill all fields";
        }
        if($this->userCheckedInToday()){
            $this->errors['checkedin']='User already checked in';
        }
      
        if(empty($this->errors)){
            $result=$this->updateAttendance($this->userid,$this->checkinTime,
            $this->currentdate,$this->status);
            if($result && $this->status==2){
                return['success'=>true,'message'=>'Attendance Marked. Made it on time'];
            }
            elseif($result && $this->status==3){
                return['success'=>true,'message'=>'Attendance Marked. You are Late'];
            }
            else{
                return['success'=>false,'message'=>'Failed to mark... try again'];
            }

        }else{
            return['success'=>false,'message'=>$this->errors['checkedin'],'errors'=>$this->errors];
        }
    }

}