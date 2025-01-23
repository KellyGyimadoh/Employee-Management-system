<?php
class Attendance extends Dbconnection{
    protected function updateAttendance($userid,$checkinTime,$currentDate,$status){
        try {
            
            $conn=parent::connect_to_database();
            $sql="UPDATE attendance 
              SET status =:status, checkin_time =:checkinTime 
              WHERE user_id =:user_id AND date =:date";
              $stmt=$conn->prepare($sql);
              $stmt->bindParam(":status",$status);
              $stmt->bindParam(":user_id",$userid);
              $stmt->bindParam(":checkinTime",$checkinTime);
              $stmt->bindParam(":date",$currentDate);


              if($stmt->execute()){

                return true;
              }else{
                return false;
              }
        } catch (PDOException $e) {
            die('error updating'.$e->getMessage());
        }finally{
            $stmt->closeCursor();
        }
    }

    protected function alreadyCheckedIn($userid,$currentDate){
        try {
            
            $conn=parent::connect_to_database();
            $sql="SELECT COUNT(checkin_time) FROM attendance 
              WHERE user_id =:user_id AND date =:date";
              $stmt=$conn->prepare($sql);
             
              $stmt->bindParam(":user_id",$userid);
              $stmt->bindParam(":date",$currentDate);
            $stmt->execute();
            $result=$stmt->fetchColumn();
            if($result && $result>0){
                return true;
            }else{
                return false;
            }
        } catch (PDOException $e) {
            die('error updating'.$e->getMessage());
        }finally{
            $stmt->closeCursor();
        }
    }
}