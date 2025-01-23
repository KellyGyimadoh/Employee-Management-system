<?php
class ViewUser extends Dbconnection{



    protected  function usersCount($accounttype=null){
            try {
                $conn=parent::connect_to_database();
                $sql="SELECT COUNT(*) AS total FROM users";
                if(!empty($accounttype)){
                    $sql.=" WHERE account_type = :account_type";
                }
                $stmt=$conn->prepare($sql);
                if(!empty($accounttype)){

                    $stmt->bindParam(":account_type",$accounttype);
                }
                $stmt->execute();
                $result=$stmt->fetch(PDO::FETCH_ASSOC);
                if($result){
                    return $result['total'];
                }else{
                    return [];
                }
            } catch (PDOException $e) {
                die('error fetching data'.$e->getMessage());
            }
    }
    protected function getUserDetails($limit, $offset, $search = '', $accountType = null)
{
    try {
        $conn = parent::connect_to_database();

        // Base SQL query
        $sql = "SELECT id,firstname, lastname, email, phone, account_type,created_at, status 
                FROM users";

        // Add WHERE condition for search or account type
        $conditions = [];
        if (!empty($search)) {
            $conditions[] = " (firstname LIKE :search OR lastname LIKE :search OR email LIKE :search) ";
        }
        if (!empty($accountType)) {
            $conditions[] = " account_type =:account_type ";
        }

        // Append conditions to SQL
        if (count($conditions) > 0) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        // Add LIMIT and OFFSET for pagination
        $sql .= " LIMIT :limit OFFSET :offset";

        $stmt = $conn->prepare($sql);

        // Bind parameters
        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%");
        }
        if (!empty($accountType)) {
            $stmt->bindValue(':account_type', $accountType);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        // Execute query
        $stmt->execute();

        // Fetch all results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die('Error fetching data: ' . $e->getMessage());
    }


}

protected function selectOneUserDetail($id){
    try {
        $conn=parent::connect_to_database();
        $sql="SELECT id,firstname,lastname,email,status,account_type,phone FROM users WHERE id=:id";
       
        $stmt=$conn->prepare($sql);
       

            $stmt->bindParam(":id",$id);
        
        $stmt->execute();
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
           
            return $result;
        }else{
            return [];
        }
    } catch (PDOException $e) {
        die('error fetching data'.$e->getMessage());
    }
}
protected function allUsers(){
    try {
        $conn=parent::connect_to_database();
        $sql="SELECT id,firstname,lastname,email,status,account_type,phone FROM users";
       
        $stmt=$conn->prepare($sql);
        
        $stmt->execute();
        $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
        if($result){
            return $result;
        }else{
            return [];
        }
    } catch (PDOException $e) {
        die('error fetching data'.$e->getMessage());
    }
}

}