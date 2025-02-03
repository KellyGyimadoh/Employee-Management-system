<?php
class AttendanceCheckout extends Attendance{

private $userid;

private $currentdate;

private $attendanceid=null;
private $checkoutTime;
private $errors;
public function __construct($userid,$currentdate,$checkoutTime,$attendanceid=null){
    parent::__construct();
    $this->userid=$this->sanitizeData($userid);
    $this->currentdate= $this->sanitizeData($currentdate);
    $this->checkoutTime= $this->sanitizeData($checkoutTime);
    $this->attendanceid=!empty($attendanceid) ? $this->sanitizeData($attendanceid) : null;
    
}
private function isEmpty()
{
    if ( empty($this->userid) ||!isset($this->checkoutTime)
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
private function userCheckedOutToday(){
    return $this->checkUserCheckedOutToday($this->userid,$this->currentdate);
}
private function noRecordsYet(){
    return $this->hasRecordsForDate($this->currentdate);
}
private function userExist(){
    return $this->checkUserExist($this->userid);
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

   
   
    public function checkOutNewUser(){
    
        if($this->isEmpty()){
            $this->errors['empty']="Please fill all fields";
        }
        if(!$this->userCheckedInToday()){
            $this->errors['checkedin']='You havent Checked in today';
        }
        if($this->userCheckedOutToday()){
            $this->errors['checkedout']='You have Already Checked Out For  today';
        }
        if(!$this->userExist()){
            $this->errors['userexist']='User Not found';
        }
        if(!$this->noRecordsYet()){
            $this->errors['norecords']='Entry for  today not inserted yet';
        }

        if(empty($this->errors)){
            $result=$this->checkoutForToday($this->userid,$this->checkoutTime,
            $this->currentdate);
            if($result){
                return['success'=>true,'message'=>'CheckOut Successful. Cheers'];
            }
            else{
                return['success'=>false,'message'=>'Failed to checkout... try again'];
            }

        }else{
            return['success'=>false,'message'=>$this->errors['checkedin']??$this->errors['userexist']
            ??$this->errors['norecords']??$this->errors['checkedout'],'errors'=>$this->errors];
        }
    }

    public function checkOutOneUserAttendance(){
    
        if($this->isEmpty()){
            $this->errors['empty']="Please fill all fields";
        }
       
        if(!$this->userExist()){
            $this->errors['userexist']='User Not found';
        }
      
        if(empty($this->errors)){
            $result=$this->checkoutForToday($this->userid,$this->checkoutTime,
            $this->currentdate);
            if($result){
               
                return['success'=>true,'message'=>'User checked out for today.'];
            }
            else{
                return['success'=>false,'message'=>'Failed to Update... try again'];
            }

        }else{
            return['success'=>false,'message'=>$this->errors['userexist'],'errors'=>$this->errors];
        }
    }

}