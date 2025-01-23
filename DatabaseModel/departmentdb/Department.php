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
            die('error occured' . $e->getMessage());
        }
    }
    protected function getDepartmentWorkerCount($id)
    {
        try {
            $conn = parent::connect_to_database();

            // Query to count the total number of users in a department
            $sql = "SELECT COUNT(*) as total_users 
                    FROM users_departments 
                    WHERE users_departments.department_id = :id";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT); // Bind department id
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['total_users']; // Return total count
            }

            return 0; // Return 0 if no users found

        } catch (PDOException $e) {
            die('Error occurred: ' . $e->getMessage());
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
                    users.status
                FROM users_departments
                LEFT JOIN users 
                    ON users_departments.user_id = users.id
                
            ";
            if (!empty($search)) {

                $sql .= " WHERE users.firstname OR users.lastname LIKE :search  ";
            }

            $sql .= "
                AND WHERE users_departments.department_id =:id
                LIMIT :limit OFFSET :offset ";


            $stmt = $conn->prepare($sql);
            if (!empty($search)) {
                $stmt->bindValue(":search", "%$search%");
            }
            $stmt->bindValue(":offset", (int)$offset, PDO::PARAM_INT);
            $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
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
}
