<?php
class Department extends Dbconnection
{
    protected function addNewDepartment($name, $head = null, $email = null, $phone = null)
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "INSERT INTO departments (name";
            if (!empty($head)) {
                $sql .= " ,head ";
            }
            if (!empty($email)) {
                $sql .= " ,email ";
            }
            if (!empty($phone)) {
                $sql .= " ,phone ";
            }
            $sql .= " ) VALUES (:name";
            if (!empty($head)) {
                $sql .= " ,:head ";
            }
            if (!empty($email)) {
                $sql .= " ,:email ";
            }
            if (!empty($phone)) {
                $sql .= " ,:phone ";
            }
            $sql .= " )";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":name", $name);
            if (!empty($head)) {
                $stmt->bindParam(":head", $head);
            }
            if (!empty($email)) {
                $stmt->bindParam(":email", $email);
            }
            if (!empty($phone)) {
                $stmt->bindParam(":phone", $phone);
            }
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die('error adding data' . $e->getMessage());
        }
    }
    protected function checkEmailExist($email)
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT email FROM departments WHERE email= :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && $email == $result['email']) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die('error occured' . $e->getMessage());
        }
    }
    protected function getDepartmentCount()
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT COUNT(*) as total FROM departments";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {

                return $result['total'];
            }

            return [];
        } catch (PDOException $e) {
            die('error occured' . $e->getMessage());
        }
    }
    protected function getDepartmentDetails($limit, $offset, $search = null)
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT departments.email,departments.name,
            departments.id,departments.head,departments.phone,departments.status,
            departments.created_at,users.firstname,users.lastname, 
            COUNT(users_departments.user_id) AS user_count
            FROM departments 
            LEFT JOIN users ON departments.head=users.id
            LEFT JOIN users_departments ON departments.id=users_departments.department_id
            
           
            ";
            if (!empty($search)) {

                $sql .= " WHERE departments.name LIKE :search  ";
            }

            $sql .= "
                GROUP BY departments.id,departments.email,departments.name,
                departments.head,departments.phone,departments.status,
                departments.created_at,users.firstname,users.lastname
                LIMIT :limit OFFSET :offset ";


            $stmt = $conn->prepare($sql);
            if (!empty($search)) {
                $stmt->bindValue(":search", "%$search%");
            }
            $stmt->bindValue(":offset", (int)$offset, PDO::PARAM_INT);
            $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result && $result > 0) {
                return $result;
            } else {
                return [];
            }
        } catch (PDOException $e) {
            die('error occured here' . $e->getMessage());
        }
    }
    //get department heads
    protected function getAllDepartmentHeads($deptid)
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT departments.name,
            departments.id,departments.head,users.id AS user_id,
            users.firstname,users.lastname
            FROM departments 
            LEFT JOIN users ON departments.head=users.id
            ";
            if (!empty($deptid)) {

                $sql .= " WHERE departments.id=:id";
            }

            $sql .= "
                GROUP BY departments.id,departments.name,
                departments.head,user_id,users.firstname,users.lastname
                 ";


            $stmt = $conn->prepare($sql);
            if (!empty($deptid)) {
                $stmt->bindParam(":id", $deptid);
            }
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result && $result > 0) {
                return $result;
            } else {
                return [];
            }
        } catch (PDOException $e) {
            die('error occured' . $e->getMessage());
        }
    }

    protected function getDepartmentWorkerCount($id)
    {
        try {
            $conn = parent::connect_to_database();
    
            $sql = "SELECT COUNT(*) as total_users 
                    FROM users_departments 
                    WHERE department_id = :id";
    
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
    
            return $stmt->fetchColumn() ?: 0; // Fetch column directly, return 0 if null
    
        } catch (PDOException $e) {
            die('Error occurred: ' . $e->getMessage());
        }
    }
    

    protected function getAllDepartmentWorkersDetails($id)
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT 
                    users.id AS user_id,
                    users.firstname,
                    users.lastname,
                    users.email,
                    users.phone,
                    users.status
                FROM users_departments
                LEFT JOIN users 
                    ON users_departments.user_id = users.id
                WHERE users_departments.department_id = :id
            ";



            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result && $result > 0) {
                return $result;
            } else {
                return [];
            }
        } catch (PDOException $e) {
            die('error occured' . $e->getMessage());
        }
    }
    protected function selectOneDepartmentDetail($id)
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT 
                    departments.id,
                    departments.name,
                    departments.head,
                    departments.email,
                    departments.status,
                    departments.phone,
                    users.firstname AS head_firstname,
                    users.lastname AS head_lastname,
                    GROUP_CONCAT(DISTINCT CONCAT(userlist.firstname, ' ', userlist.lastname) SEPARATOR ', ') AS users
                FROM departments
                LEFT JOIN users 
                    ON departments.head = users.id
                LEFT JOIN users_departments 
                    ON departments.id = users_departments.department_id
                LEFT JOIN users AS userlist 
                    ON users_departments.user_id = userlist.id
                WHERE departments.id = :id
                GROUP BY departments.id";


            $stmt = $conn->prepare($sql);


            $stmt->bindParam(":id", $id);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return $result;
            } else {
                return [];
            }
        } catch (PDOException $e) {
            die('error fetching data' . $e->getMessage());
        }
    }
    protected function checkEmailUnique($id, $email)
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT COUNT(*) FROM departments WHERE email=:email AND id!=:id ";

            $stmt = $conn->prepare($sql);


            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":email", $email);

            $stmt->execute();
            $result = $stmt->fetchColumn();

            if ($result > 0) {
                return false;
            }
            return true;
        } catch (PDOException $e) {
            die('error fetching data' . $e->getMessage());
        }
    }
    protected function checkNameUnique($id, $name)
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT COUNT(*) FROM departments WHERE name=:name AND id!=:id ";

            $stmt = $conn->prepare($sql);


            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":name", $name);

            $stmt->execute();
            $result = $stmt->fetchColumn();

            if ($result > 0) {
                return false;
            }
            return true;
        } catch (PDOException $e) {
            die('error fetching data' . $e->getMessage());
        }
    }
    protected function deleteDepartment($id)
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "DELETE FROM departments WHERE id=:id";

            $stmt = $conn->prepare($sql);


            $stmt->bindParam(":id", $id);


            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die('error fetching data' . $e->getMessage());
        }
    }
    protected function removeDepartmentHead($id)
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "UPDATE  departments SET head=NULL WHERE id=:id";

            $stmt = $conn->prepare($sql);


            $stmt->bindParam(":id", $id);


            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die('error fetching data' . $e->getMessage());
        }
    }


    protected function updateDepartment($id, $name, $status, $email = null, $phone = null, $head = null)
    {
        try {
            $conn = parent::connect_to_database();
            $conn->beginTransaction();
            $sql = "UPDATE departments SET name=:name,status=:status,head=:head,email=:email,phone=:phone
            WHERE id=:id ";



            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":status", $status);

            $stmt->bindParam(":head", $head);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            $sql = "SELECT 
            departments.id,
            departments.name,
            departments.head,
            departments.email,
            departments.status,
            departments.phone,
            users.firstname AS head_firstname,
            users.lastname AS head_lastname,
            GROUP_CONCAT(DISTINCT CONCAT(userlist.firstname, ' ', userlist.lastname) SEPARATOR ', ') AS users
        FROM departments
        LEFT JOIN users 
            ON departments.head = users.id
        LEFT JOIN users_departments 
            ON departments.id = users_departments.department_id
        LEFT JOIN users AS userlist 
            ON users_departments.user_id = userlist.id
        WHERE departments.id = :id
        GROUP BY departments.id";

            $stmt = $conn->prepare($sql);


            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($conn->commit()) {
                return [
                    'success' => true,
                    'department' => [
                        'id' => $result['id'],
                        'name' => $result['name'],
                        'head' => $result['head'],
                        'email' => $result['email'],
                        'phone' => $result['phone'],
                        'status' => $result['status'],
                        'head_firstname' => $result['head_firstname'],
                        'head_lastname' => $result['head_lastname'],

                        'users' => $result['users']
                    ]
                ];
            } else {
                return ['success' => false];
            }
        } catch (PDOException $e) {
            $conn->rollBack();
            die('error fetching data' . $e->getMessage());
        }
    }

    protected function addUserDepartment($userid, $deptid)
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "INSERT INTO users_departments (user_id,department_id) VALUES(:userid,:departmentid)";

            $stmt = $conn->prepare($sql);


            $stmt->bindParam(":userid", $userid);
            $stmt->bindParam(":departmentid", $deptid);


            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die('error fetching data' . $e->getMessage());
        }
    }
    protected function checkUserAlreadyBelongsToDept($userid, $deptid)
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT COUNT(*) FROM users_departments WHERE user_id=:userid AND department_id=:departmentid ";

            $stmt = $conn->prepare($sql);


            $stmt->bindParam(":userid", $userid);
            $stmt->bindParam(":departmentid", $deptid);

            $stmt->execute();
            $result = $stmt->fetchColumn();

            if ($result > 0) {
                return false;
            }
            return true;
        } catch (PDOException $e) {
            die('error fetching data' . $e->getMessage());
        }
    }

    protected function getDepartmentWorkersDetails($limit, $offset, $search = null, $id)
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT 
                users.id AS user_id,
                users.firstname,
                users.lastname,
                users.email,
                users.phone,
                users.status,
                departments.id AS department_id
            FROM users
            LEFT JOIN users_departments 
                ON users.id = users_departments.user_id
            LEFT JOIN departments 
                ON users_departments.department_id = departments.id
            WHERE users_departments.department_id = :id";

            if (!empty($search)) {
                $sql .= " AND (users.firstname LIKE :search OR users.lastname LIKE :search)";
            }

            $sql .= " LIMIT :limit OFFSET :offset"; // Always include LIMIT & OFFSET

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            if (!empty($search)) {
                $search = "%$search%"; // Add wildcards for LIKE search
                $stmt->bindValue(":search", $search, PDO::PARAM_STR);
            }

            $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
            $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Directly return the result

        } catch (PDOException $e) {
            die('Error occurred: ' . $e->getMessage());
        }
    }

    protected function getOneUserDepartmentDetails($limit = null, $offset = null, $search = null, $id)
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT 
                departments.id,
                departments.name, 
                departments.head,
                departments.email,departments.phone,
                users.firstname,users.lastname
                FROM departments
                LEFT JOIN users_departments 
                ON departments.id = users_departments.department_id
                LEFT JOIN users ON
                departments.head=users.id
                WHERE users_departments.user_id = :userid";

            // Dynamically append conditions
            if (!empty($search)) {
                $sql .= " AND departments.name LIKE :search";
            }
            if (!empty($limit) && !empty($offset)) {
                $sql .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":userid", $id, PDO::PARAM_INT);

            // Bind only if the condition is met
            if (!empty($search)) {
                $search = "%$search%"; // Add wildcards for LIKE
                $stmt->bindParam(":search", $search, PDO::PARAM_STR);
            }
            if (!empty($limit) && !empty($offset)) {
                $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
                $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
            }

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ? $result : [];
        } catch (PDOException $e) {
            die('Error fetching data: ' . $e->getMessage());
        }
    }


    protected function getOneUserDepartmentCount($userid)
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT COUNT(*) as total FROM users_departments WHERE user_id=:userid";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":userid", $userid);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {

                return $result['total'];
            }

            return [];
        } catch (PDOException $e) {
            die('error occured' . $e->getMessage());
        }
    }

    protected function AllDepartmentCountAndDetail()
    {
        try {
            $conn = parent::connect_to_database();
            $allsql = "SELECT COUNT(*) as total FROM departments";
            $allstmt = $conn->prepare($allsql);

            $allstmt->execute();

            $allResult = $allstmt->fetch(PDO::FETCH_ASSOC);

            $headsql = "SELECT COUNT(*) as head_total FROM departments WHERE head IS NOT NULL";
            $headstmt = $conn->prepare($headsql);

            $headstmt->execute();
            $headResult = $headstmt->fetch(PDO::FETCH_ASSOC);

            $activesql = "SELECT COUNT(*) as active_total FROM departments WHERE status=1";
            $activestmt = $conn->prepare($activesql);

            $activestmt->execute();
            $activeResult = $activestmt->fetch(PDO::FETCH_ASSOC);

            $inactivesql = "SELECT COUNT(*) as inactive_total FROM departments WHERE status=2";
            $inactivestmt = $conn->prepare($inactivesql);
            $inactivestmt->execute();
            $inactiveResult = $inactivestmt->fetch(PDO::FETCH_ASSOC);

            return [
                'departments_total' => $allResult['total'],
                'has_head_total' => $headResult['head_total'],
                'active_total' => $activeResult['active_total'],
                'inactive_total' => $inactiveResult['inactive_total']

            ];
        } catch (PDOException $e) {
            die('error occured' . $e->getMessage());
        }
    }
}
