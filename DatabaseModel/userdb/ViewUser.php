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

    protected  function usersCountSummary(){
        try {
            $conn=parent::connect_to_database();
            $Staffsql="SELECT COUNT(*) AS staff_total FROM users WHERE account_type='staff'";
            
            $Staffstmt=$conn->prepare($Staffsql);
           
            $Staffstmt->execute();
            $Staffresult=$Staffstmt->fetch(PDO::FETCH_ASSOC);

            $adminsql="SELECT COUNT(*) AS admin_total FROM users WHERE account_type ='admin'";
            
            $adminstmt=$conn->prepare($adminsql);
           
            $adminstmt->execute();
            $adminresult=$adminstmt->fetch(PDO::FETCH_ASSOC);

            $activesql="SELECT COUNT(*) AS active_total FROM users WHERE status =1";
            
            $activestmt=$conn->prepare($activesql);
           
            $activestmt->execute();
            $activeresult=$activestmt->fetch(PDO::FETCH_ASSOC);

            $inactivesql="SELECT COUNT(*) AS inactive_total FROM users WHERE status =2";
            
            $inactivestmt=$conn->prepare($inactivesql);
           
            $inactivestmt->execute();
            $inactiveresult=$inactivestmt->fetch(PDO::FETCH_ASSOC);

            return[
                'staff_total'=>$Staffresult['staff_total'],
                'admin_total'=>$adminresult['admin_total'],
                'active_total'=>$activeresult['active_total'],
                'inactive_total'=>$inactiveresult['inactive_total']
            ];
            
        } catch (PDOException $e) {
            die('error fetching data for users count'.$e->getMessage());
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

protected function allUsersSalaryAndProfileDetails($limit,$offset,$search=null,$accountType=null){
    try {
        $conn=parent::connect_to_database();
        $sql="SELECT users.id AS userID,users.firstname,users.lastname,
        users.email,users.status,users.account_type,users.phone,users.created_at,
        salaries.total_salary,
         GROUP_CONCAT(DISTINCT CONCAT(deptlist.name) SEPARATOR ', ') AS user_departments
        FROM users
        LEFT JOIN users_departments ON
        users.id=users_departments.user_id
        LEFT JOIN departments ON
        users_departments.department_id=departments.id
        LEFT JOIN departments AS deptlist
         ON users_departments.department_id = deptlist.id
        LEFT JOIN salaries ON
        users.id=salaries.user_id
        ";
       // Add WHERE condition for search or account type
       $conditions = [];
       if (!empty($search)) {
           $conditions[] = " (users.firstname LIKE :search OR users.lastname LIKE :search OR users.email LIKE :search) ";
       }
       if (!empty($accountType)) {
           $conditions[] = " users.account_type =:account_type ";
       }

       // Append conditions to SQL
       if (count($conditions) > 0) {
           $sql .= " WHERE " . implode(" AND ", $conditions);
       }

       // Add LIMIT and OFFSET for pagination
       $sql .= " GROUP BY users.id
       LIMIT :limit OFFSET :offset";
        $stmt=$conn->prepare($sql);
        
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
        die('error fetching data'.$e->getMessage());
    }
}
protected function OneUsersSalaryAndProfileDetailsForCsv($userid){
    try {
        $conn=parent::connect_to_database();
        $sql="SELECT users.id,users.firstname,users.lastname,
        users.email,users.status,users.phone,users.created_at,
        salaries.total_salary,salaries.bonus,salaries.deductions,
         GROUP_CONCAT(DISTINCT CONCAT(deptlist.name) SEPARATOR ', ') AS user_departments
        FROM users
        LEFT JOIN users_departments ON
        users.id=users_departments.user_id
        LEFT JOIN departments ON
        users_departments.department_id=departments.id
        LEFT JOIN departments AS deptlist
         ON users_departments.department_id = deptlist.id
        LEFT JOIN salaries ON
        users.id=salaries.user_id
        WHERE users.id=:userid
        ";
      
        $stmt=$conn->prepare($sql);
        $stmt->bindValue(':userid', (int)$userid, PDO::PARAM_INT);
       
        // Execute query
        $stmt->execute();

        // Fetch all results
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die('error fetching data'.$e->getMessage());
    }
}

protected function AllUsersSalaryAndProfileDetailsForCsv(){
    try {
        $conn=parent::connect_to_database();
        $sql="SELECT users.id,users.firstname,users.lastname,
        users.email,users.phone,users.created_at,
        salaries.total_salary,salaries.bonus,salaries.deductions,
         GROUP_CONCAT(DISTINCT deptlist.name ORDER BY deptlist.name ASC SEPARATOR ', ') AS user_departments
        FROM users
        LEFT JOIN users_departments ON
        users.id=users_departments.user_id
        LEFT JOIN departments ON
        users_departments.department_id=departments.id
        LEFT JOIN departments AS deptlist
         ON users_departments.department_id = deptlist.id
        LEFT JOIN salaries ON
        users.id=salaries.user_id
        GROUP BY users.id, users.firstname, users.lastname, users.email, users.phone, users.created_at,
        salaries.total_salary, salaries.bonus, salaries.deductions
        ";
      
        $stmt=$conn->prepare($sql);
    
        // Execute query
        $stmt->execute();

        // Fetch all results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die('error fetching data'.$e->getMessage());
    }
}

}