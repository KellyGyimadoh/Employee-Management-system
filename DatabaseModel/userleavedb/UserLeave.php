<?php
class UserLeave extends Dbconnection{

protected function InsertNewLeave($userid,$startDate,$endDate,$type)
{
        try {
            $conn=parent::connect_to_database();
            $sql="INSERT INTO userleave (user_id,start_date,end_date,type)
            VALUES (:user_id,:start_date,:end_date,:type)";
            $stmt=$conn->prepare($sql);
            $stmt->bindParam(":user_id",$userid);
            $stmt->bindParam(":start_date",$startDate);
            $stmt->bindParam(":end_date",$endDate);
            $stmt->bindParam(":type",$type);
            return $stmt->execute()?:$stmt->execute();
            
        } catch (PDOException $e) {
            die('error inserting leave'.$e->getMessage());
        }
}

protected function SelectAllUserLeaveRequest($limit,$offset,$search=null,$date=null,$status=null)
{
        try {
            $conn=parent::connect_to_database();
            $sql="SELECT userleave.id,userleave.type,userleave.start_date,userleave.user_id,
            userleave.end_date,userleave.status,userleave.created_at,userleave.approved_by,
            approved_by_user.firstname AS approved_by_firstname,
            approved_by_user.lastname AS approved_by_lastname,
            requested_by_user.firstname AS requested_by_firstname,
            requested_by_user.lastname AS requested_by_lastname
            FROM userleave
            LEFT JOIN users AS approved_by_user ON 
            userleave.approved_by= approved_by_user.id
            LEFT JOIN users AS requested_by_user
            ON userleave.user_id=requested_by_user.id
        
            ";
            $conditions=[];
            if(!empty($search)){
                $conditions[]="( userleave.type LIKE :search OR requested_by_user.firstname LIKE :search
                OR requested_by_user.lastname LIKE :search
                )";
            }
            if(!empty($status)){
                $conditions[]="( userleave.status=:status )";
            }
            if(!empty($date)){
                $conditions[]="( userleave.start_date=:date )";
            }
            if(!empty($conditions)){
                $sql.= " WHERE ".implode(" AND ",$conditions);
            }

            $sql.=" ORDER BY userleave.created_at DESC
             LIMIT :limit OFFSET :offset";
            $stmt=$conn->prepare($sql);
            $stmt->bindValue(":limit",$limit,PDO::PARAM_INT);
            $stmt->bindValue(":offset",$offset,PDO::PARAM_INT);
            if(!empty($status)){

                $stmt->bindValue(":status",$status);
            }
            if(!empty($search)){

                $stmt->bindValue(":search","%$search%");
            }

            if(!empty($date)){

                $stmt->bindValue(":date",$date);
            }
            
           $stmt->execute();
           $result=$stmt->fetchAll(PDO::FETCH_ASSOC);

           return $result ?: $result ;
            
        } catch (PDOException $e) {
            die('error fetching leave'.$e->getMessage());
        }
}
protected function SelectOneUserLeaveRequest($limit,$offset,$search=null,$date=null,$userid,$status=null)
{
        try {
            $conn=parent::connect_to_database();
            $sql="SELECT userleave.id,userleave.type,userleave.start_date,userleave.user_id,
            userleave.end_date,userleave.status,userleave.created_at,userleave.approved_by,
            approved_by_user.firstname AS approved_by_firstname,
            approved_by_user.lastname AS approved_by_lastname,
            requested_by_user.firstname AS requested_by_firstname,
            requested_by_user.lastname AS requested_by_lastname
            FROM userleave
            LEFT JOIN users AS approved_by_user
            ON userleave.approved_by=approved_by_user.id
            LEFT JOIN users AS requested_by_user
            ON userleave.user_id=requested_by_user.id
            WHERE userleave.user_id=:userid
            ";
            $conditions=[];

            if(!empty($search)){
                $conditions[]="( userleave.type LIKE :search OR requested_by_user.firstname LIKE :search
                OR requested_by_user.lastname LIKE :search
                )";
            }
            if(!empty($status)){
                $conditions[]="( userleave.status=:status )";
            }
            if(!empty($date)){
                $conditions[]="( userleave.start_date=:date )";
            }
            if(!empty($conditions)){
                $sql.= " AND ".implode(" AND ",$conditions);
            }

            $sql.=" ORDER BY userleave.created_at DESC LIMIT :limit OFFSET :offset";
            $stmt=$conn->prepare($sql);
            $stmt->bindValue(":limit",$limit,PDO::PARAM_INT);
            $stmt->bindValue(":offset",$offset,PDO::PARAM_INT);
            $stmt->bindValue(":userid",$userid,PDO::PARAM_INT);
            if(!empty($status)){

                $stmt->bindValue(":status",$status);
            }
            if(!empty($search)){

                $stmt->bindValue(":search","%$search%");
            }
            if(!empty($date)){

                $stmt->bindValue(":date",$date);
            }
            
            $stmt->execute();
            $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
 
            return $result?: $result ;
            
        } catch (PDOException $e) {
            die('error fetching leave'.$e->getMessage());
        }
}



protected function ViewOneUserLeaveRequest($leaveid)
{
        try {
            $conn=parent::connect_to_database();
            $sql="SELECT userleave.id,userleave.type,userleave.start_date,userleave.user_id,
            userleave.end_date,userleave.status,userleave.created_at,userleave.approved_by,
            approved_by_user.firstname AS approved_by_firstname,
            approved_by_user.lastname AS approved_by_lastname,
            requested_by_user.firstname AS requested_by_firstname,
            requested_by_user.lastname AS requested_by_lastname
            FROM userleave
            LEFT JOIN users AS approved_by_user
            ON userleave.approved_by=approved_by_user.id
            LEFT JOIN users AS requested_by_user
            ON userleave.user_id=requested_by_user.id
            WHERE userleave.id=:id
            ";
           
            
            $stmt=$conn->prepare($sql);
           
            $stmt->bindValue(":id",$leaveid);
            
            $stmt->execute();
            $result=$stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && $result > 0) {
                return [
                    'success' => true,
                    'result' => $result
                ];
            }
            return ['success' => false];
        } catch (PDOException $e) {
            die('error fetching leave'.$e->getMessage());
        }
}

protected function SelectAllUserLeaveRequestCount()
{
        try {
            $conn=parent::connect_to_database();
            $Allsql="SELECT COUNT(*) AS leave_total FROM userleave";
            $Allstmt=$conn->prepare($Allsql);
            $Allstmt->execute();
            $allResult=$Allstmt->fetch(PDO::FETCH_ASSOC);

            $pendingsql="SELECT COUNT(*) AS pending_total FROM userleave WHERE status=1";
            $pendingstmt=$conn->prepare($pendingsql);
            $pendingstmt->execute();
            $pendingResult=$pendingstmt->fetch(PDO::FETCH_ASSOC);

            $approvedsql="SELECT COUNT(*) AS approved_total FROM userleave WHERE status=2";
            $approvedstmt=$conn->prepare($approvedsql);
            $approvedstmt->execute();
            $approvedResult=$approvedstmt->fetch(PDO::FETCH_ASSOC);

            $rejectedsql="SELECT COUNT(*) AS rejected_total FROM userleave WHERE status=3";
            $rejectedstmt=$conn->prepare($rejectedsql);
            $rejectedstmt->execute();
            $rejectedResult=$rejectedstmt->fetch(PDO::FETCH_ASSOC);

            return[
                'leave_total'=>$allResult['leave_total'],
                'pending_total'=>$pendingResult['pending_total'],
                'approved_total'=>$approvedResult['approved_total'],
                'rejected_total'=>$rejectedResult['rejected_total']
            ];
            
        } catch (PDOException $e) {
            die('error inserting leave'.$e->getMessage());
        }
}

protected function SelectOneUserLeaveRequestCount($userid)
{
        try {
            $conn=parent::connect_to_database();
            $Allsql="SELECT COUNT(*) AS leave_total FROM userleave WHERE user_id=:userid";
            $Allstmt=$conn->prepare($Allsql);
            $Allstmt->bindParam(":userid",$userid);
            $Allstmt->execute();
            $allResult=$Allstmt->fetch(PDO::FETCH_ASSOC);

            $pendingsql="SELECT COUNT(*) AS pending_total FROM userleave WHERE user_id=:userid AND status=1";
            $pendingstmt=$conn->prepare($pendingsql);
            $pendingstmt->bindParam(":userid",$userid);
            $pendingstmt->execute();
            $pendingResult=$pendingstmt->fetch(PDO::FETCH_ASSOC);

            $approvedsql="SELECT COUNT(*) AS approved_total FROM userleave WHERE user_id=:userid AND status=2 ";
            $approvedstmt=$conn->prepare($approvedsql);
            $approvedstmt->bindParam(":userid",$userid);
            $approvedstmt->execute();
            $approvedResult=$approvedstmt->fetch(PDO::FETCH_ASSOC);

            $rejectedsql="SELECT COUNT(*) AS rejected_total FROM userleave WHERE user_id=:userid AND status=3";
            $rejectedstmt=$conn->prepare($rejectedsql);
            $rejectedstmt->bindParam(":userid",$userid);
            $rejectedstmt->execute();
            $rejectedResult=$rejectedstmt->fetch(PDO::FETCH_ASSOC);

            return[
                'leave_total'=>$allResult['leave_total'],
                'pending_total'=>$pendingResult['pending_total'],
                'approved_total'=>$approvedResult['approved_total'],
                'rejected_total'=>$rejectedResult['rejected_total']
            ];
            
        } catch (PDOException $e) {
            die('error inserting leave'.$e->getMessage());
        }
}


protected function UpdateNewLeave($leaveid,$startDate,$endDate,$type,$status=null,$approvedBy=null)
{
        try {
            $conn=parent::connect_to_database();
            $conn->beginTransaction();
            $sql="UPDATE userleave SET start_date=:start_date,end_date=:end_date,
            type=:type";
           
            $conditions=[];
            if(!empty($status)){
                $conditions[]=" ,status=:status ";
            }
            if(!empty($approvedBy)){
                $conditions[]=" ,approved_by=:approved_by ";
            }
            if(!empty($conditions)){
                $sql.=" ".implode(" ",$conditions);
            }
            $sql.=" WHERE id=:leaveid";
            $stmt=$conn->prepare($sql);
            $stmt->bindParam(":leaveid",$leaveid);
            $stmt->bindParam(":start_date",$startDate);
            $stmt->bindParam(":approved_by",$approvedBy);
            $stmt->bindParam(":end_date",$endDate);
            $stmt->bindParam(":type",$type);
            if(!empty($status)){
                $stmt->bindParam(":status",$status);
            }
            $stmt->execute();

            $Selectsql="SELECT userleave.id,userleave.type,userleave.start_date,userleave.user_id,
            userleave.end_date,userleave.status,userleave.created_at,userleave.approved_by,
            approved_by_user.firstname AS approved_by_firstname,
            approved_by_user.lastname AS approved_by_lastname,
            requested_by_user.firstname AS requested_by_firstname,
            requested_by_user.lastname AS requested_by_lastname
            FROM userleave
            LEFT JOIN users AS approved_by_user
            ON userleave.approved_by=approved_by_user.id
            LEFT JOIN users AS requested_by_user
            ON userleave.user_id=requested_by_user.id
            WHERE userleave.id=:id
            ";
            $Selectstmt=$conn->prepare($Selectsql);
            $Selectstmt->bindParam(":id",$leaveid);
            $Selectstmt->execute();
           $result= $Selectstmt->fetch(PDO::FETCH_ASSOC);
            if ($conn->commit()) {
                return [
                    'success' => true,
                    'result' => $result
                ];
            } else {
                return [
                    'success' => false
                ];
            }
            
        } catch (PDOException $e) {
            $conn->rollBack();
            die('error updating leave'.$e->getMessage());
        }
}
protected function DeleteNewLeave($leaveid)
{
        try {
            $conn=parent::connect_to_database();
            $sql="DELETE FROM userleave WHERE id=:id";
            $stmt=$conn->prepare($sql);
            $stmt->bindParam(":id",$leaveid);
           
            return $stmt->execute()?:$stmt->execute();
            
        } catch (PDOException $e) {
            die('error removing data'.$e->getMessage());
        }
}

protected function checkLeave($userid)
{
        try {
            $conn=parent::connect_to_database();
            $sql="SELECT COUNT(status) FROM userleave WHERE status=1 AND user_id=:userid 
            AND YEAR(created_at)=YEAR(CURDATE()) ";
            $stmt=$conn->prepare($sql);
            $stmt->bindParam(":userid",$userid);
           
             $stmt->execute();
             $result=$stmt->fetch(PDO::FETCH_ASSOC);
             if($result && (int)$result==(int)2){
                return true;
             }else{
                return false;
             }
            
        } catch (PDOException $e) {
            die('error removing data'.$e->getMessage());
        }
}
protected function UpdateOneNewLeaveStatus($leaveid,$status,$approvedBy)
{
        try {
            $conn=parent::connect_to_database();
            $sql="UPDATE userleave SET status=:status,approved_by=:approved_by WHERE id=:leaveid ";
            $stmt=$conn->prepare($sql);
            $stmt->bindParam(":leaveid",$leaveid);
            $stmt->bindParam(":status",$status);
            $stmt->bindParam(":approved_by",$approvedBy);
           
             if($stmt->execute()){
                return true;
             }else{
                return false;
             }
            
        } catch (PDOException $e) {
            die('error removing data'.$e->getMessage());
        }
}

}