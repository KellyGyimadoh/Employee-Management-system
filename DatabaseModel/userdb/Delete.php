<?php
class Delete extends Dbconnection{
    protected function deleteUserAccount($id){
        try {
            $conn=parent::connect_to_database();
            $sql="DELETE FROM users WHERE id=:id";
            $stmt=$conn->prepare($sql);
            $stmt->bindParam(":id",$id);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }

            
        } catch (PDOException $e) {
        die("error deleting".$e->getMessage());
        }
    }
}