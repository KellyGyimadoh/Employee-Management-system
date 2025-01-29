<?php
class Task extends Dbconnection
{

    protected function insertNewTask($name, $description, $duedate, $assignedBy, $assignedTo, $department)
    {

        try {
            $conn = parent::connect_to_database();
            $sql = "INSERT INTO tasks (name,description,due_date,assigned_by,assigned_to,department_id)
            VALUES(:name,:description,:due_date,:assigned_by,:assigned_to,:department_id)
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":due_date", $duedate);
            $stmt->bindParam(":assigned_by", $assignedBy);
            $stmt->bindParam(":assigned_to", $assignedTo);
            $stmt->bindParam(":department_id", $department);
            return $stmt->execute() ? true : false;
        } catch (PDOException $e) {
            die('error inserting data' . $e->getMessage());
        }
    }

    protected function selectOneTask($id)
    {

        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT tasks.id, tasks.name,tasks.description,
            tasks.due_date,tasks.department_id,
            tasks.assigned_by,
            tasks.assigned_to,
            tasks.status,tasks.date_completed,
            assigned_by_user.firstname AS assigned_by_firstname,
            assigned_by_user.lastname AS assigned_by_lastname,
            assigned_to_user.firstname AS assigned_to_firstname,
            assigned_to_user.lastname AS assigned_to_lastname,
            departments.name AS department_name
            FROM tasks 
            LEFT JOIN users AS assigned_by_user ON
            tasks.assigned_by=assigned_by_user.id
            LEFT JOIN users AS assigned_to_user ON
            tasks.assigned_to=assigned_to_user.id
            LEFT JOIN departments ON
            tasks.department_id=departments.id
            WHERE tasks.id=:id ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id", $id);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && $result > 0) {
                return [
                    'success' => true,
                    'result' => $result
                ];
            }
            return ['success' => false];
        } catch (PDOException $e) {
            die('error fetching data' . $e->getMessage());
        }
    }


    protected function selectAllTasks($limit, $offset, $search = null, $date = null,$status=null)
    {

        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT tasks.id, tasks.name,tasks.description,
            tasks.due_date,tasks.department_id,tasks.created_at,
            tasks.assigned_by,
            tasks.assigned_to,
            tasks.status,tasks.date_completed,
            assigned_by_user.firstname AS assigned_by_firstname,
            assigned_by_user.lastname AS assigned_by_lastname,
            assigned_to_user.firstname AS assigned_to_firstname,
            assigned_to_user.lastname AS assigned_to_lastname,
            departments.name AS department_name
            FROM tasks 
            LEFT JOIN users AS assigned_by_user ON
            tasks.assigned_by=assigned_by_user.id
            LEFT JOIN users AS assigned_to_user ON
            tasks.assigned_to=assigned_to_user.id
            LEFT JOIN departments ON
            tasks.department_id=departments.id
            ";
            $conditions = []; // To dynamically build conditions

            if (!empty($search)) {
                $conditions[] = "(assigned_to_user.firstname LIKE :search 
                OR assigned_to_user.lastname LIKE :search OR tasks.name LIKE :search 
                OR tasks.description LIKE :search)";
            }

            if (!empty($date)) {
                $conditions[] = "tasks.due_date = :date";
            }

            if (!empty($status)) {
                $conditions[] = "tasks.status = :status";
            }
            // Add conditions dynamically
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(" AND ", $conditions); // Combine conditions with AND
            }

            $sql .= " ORDER BY tasks.created_at DESC
           LIMIT :limit OFFSET :offset";


            $stmt = $conn->prepare($sql);
            if (!empty($search)) {
                $stmt->bindValue(":search", "%$search%");
            }
            if (!empty($date)) {
                $stmt->bindValue(":date", $date);
            }
            if (!empty($status)) {
                $stmt->bindValue(":status", $status);
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
            die('error inserting data' . $e->getMessage());
        }
    }
    protected function selectAllTasksForToday($limit, $offset, $search = null, $status = null)
    {

        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT tasks.name,tasks.id,
            tasks.description,
            tasks.due_date,tasks.department_id,
            tasks.assigned_by,
            tasks.assigned_to,
            tasks.status,tasks.date_completed,tasks.created_at,
            assigned_by_user.firstname AS assigned_by_firstname,
            assigned_by_user.lastname AS assigned_by_lastname,
            assigned_to_user.firstname AS assigned_to_firstname,
            assigned_to_user.lastname AS assigned_to_lastname,
            departments.name AS department_name
            FROM tasks 
            LEFT JOIN users AS assigned_by_user ON
            tasks.assigned_by=assigned_by_user.id
            LEFT JOIN users AS assigned_to_user ON
            tasks.assigned_to=assigned_to_user.id
            LEFT JOIN departments ON
            tasks.department_id=departments.id
            WHERE DATE(tasks.created_at)=CURDATE()
            ";
            $conditions = []; // To dynamically build conditions

            if (!empty($search)) {
                $conditions[] = "(assigned_to_user.firstname LIKE :search OR tasks.name LIKE :search)";
            }

            if (!empty($status)) {
                $conditions[] = "(tasks.status = :status)";
            }

            // Add conditions dynamically
            if (!empty($conditions)) {
                $sql .= " AND " . implode(" AND ", $conditions); // Combine conditions with AND
            }

            $sql .= " ORDER BY tasks.created_at DESC
           LIMIT :limit OFFSET :offset";


            $stmt = $conn->prepare($sql);
            if (!empty($search)) {
                $stmt->bindValue(":search", "%$search%");
            }
            if (!empty($date)) {
                $stmt->bindValue(":status", $status);
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
            die('error fetching data for today' . $e->getMessage());
        }
    }
    protected function selectAllUserTasksForToday($limit, $offset, $search = null, $status = null, $userid,$date=null)
    {

        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT tasks.id, tasks.name,
            tasks.description,
            tasks.due_date,tasks.department_id,
            tasks.assigned_by,
            tasks.assigned_to,
            tasks.status,tasks.date_completed,tasks.created_at,
            assigned_by_user.firstname AS assigned_by_firstname,
            assigned_by_user.lastname AS assigned_by_lastname,
            assigned_to_user.firstname AS assigned_to_firstname,
            assigned_to_user.lastname AS assigned_to_lastname,
            departments.name AS department_name
            FROM tasks 
            LEFT JOIN users AS assigned_by_user ON
            tasks.assigned_by=assigned_by_user.id
            LEFT JOIN users AS assigned_to_user ON
            tasks.assigned_to=assigned_to_user.id
            LEFT JOIN departments ON
            tasks.department_id=departments.id
            WHERE tasks.assigned_to=:userid 
            ";
            $conditions = []; // To dynamically build conditions

            if (!empty($search)) {
                $conditions[] = "(tasks.name LIKE :search OR tasks.description LIKE :search)";
            }

            if (!empty($status)) {
                $conditions[] = "(tasks.status = :status)";
            }
            if (!empty($date)) {
                $conditions[] = "(tasks.due_date = :date)";
            }

            // Add conditions dynamically
            if (!empty($conditions)) {
                $sql .= " AND " . implode(" AND ", $conditions); // Combine conditions with AND
            }

            $sql .= " ORDER BY tasks.created_at DESC
           LIMIT :limit OFFSET :offset";


            $stmt = $conn->prepare($sql);
            if (!empty($search)) {
                $stmt->bindValue(":search", "%$search%");
            }
            if (!empty($status)) {
                $stmt->bindValue(":status", $status);
            }
            if (!empty($date)) {
                $stmt->bindValue(":date", $date);
            }
            $stmt->bindValue(":offset", (int)$offset, PDO::PARAM_INT);
            $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
            $stmt->bindValue(":userid", $userid, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result && $result > 0) {
                return $result;
            } else {
                return [];
            }
        } catch (PDOException $e) {
            die('error fetching data for today' . $e->getMessage());
        }
    }

    protected function getAllTasksCount()
    {

        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT COUNT(*) as total FROM tasks";
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

    protected function getTodayTasksCount()
    {

        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT COUNT(*) as total FROM tasks WHERE created_at=CURDATE()";
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

    protected function getAllUserTasksCount($userid)
    {

        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT COUNT(*) as total FROM tasks WHERE assigned_to=:userid";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam("userid", $userid);
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

    protected function getAllUserTasksPendingCompletedCount($userid)
    {

        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT COUNT(status) as pendingtotal FROM tasks WHERE assigned_to=:userid AND status=1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam("userid", $userid);
            $stmt->execute();
            $resultPending = $stmt->fetch(PDO::FETCH_ASSOC);

            $completedSql = "SELECT COUNT(status) as completetotal FROM tasks WHERE assigned_to=:userid AND status=2";
            $completedStmt = $conn->prepare($completedSql);
            $completedStmt->bindParam("userid", $userid);
            $completedStmt->execute();
            $resultCompleted = $completedStmt->fetch(PDO::FETCH_ASSOC);
            
            $lateSql = "SELECT COUNT(status) as latetotal FROM tasks WHERE assigned_to=:userid AND status=3";
            $lateStmt = $conn->prepare($lateSql);
            $lateStmt->bindParam("userid", $userid);
            $lateStmt->execute();
            $resultlate = $lateStmt->fetch(PDO::FETCH_ASSOC);
            return[
                'pendingTotal'=>$resultPending['pendingtotal'],
                'completedTotal'=>$resultCompleted['completetotal'],
                'lateTotal'=>$resultlate['latetotal'],
            ];

           
        } catch (PDOException $e) {
            die('error occured' . $e->getMessage());
        }
    }
    protected function getAllTasksPendingCompletedCount()
    {

        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT COUNT(status) as pendingtotal FROM tasks WHERE status=1";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $resultPending = $stmt->fetch(PDO::FETCH_ASSOC);

            $completedSql = "SELECT COUNT(status) as completetotal FROM tasks WHERE status=2";
            $completedStmt = $conn->prepare($completedSql);
            $completedStmt->execute();
            $resultCompleted = $completedStmt->fetch(PDO::FETCH_ASSOC);
            
            $lateSql = "SELECT COUNT(status) as latetotal FROM tasks WHERE status=3";
            $lateStmt = $conn->prepare($lateSql);
            $lateStmt->execute();
            $resultlate = $lateStmt->fetch(PDO::FETCH_ASSOC);
            return[
                'pendingTotal'=>$resultPending['pendingtotal'],
                'completedTotal'=>$resultCompleted['completetotal'],
                'lateTotal'=>$resultlate['latetotal'],
            ];

           
        } catch (PDOException $e) {
            die('error occured getting count' . $e->getMessage());
        }
    }
    protected function getAllUserTasksCountForToday($userid)
    {

        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT COUNT(*) as total FROM tasks WHERE assigned_to=:userid AND due_date=CURDATE()";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam("userid", $userid);
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

    protected function updateTask(
        $name,
        $description,
        $duedate,
        $assignedBy,
        $assignedTo,
        $taskid,
        $status,
        $dateCompleted,
        $department
    ) {
        try {

            $conn = parent::connect_to_database();
            $conn->beginTransaction();
            $sql = "UPDATE tasks 
              SET status =:status, name =:name,description=:description,
              due_date=:due_date,assigned_by=:assigned_by,assigned_to=:assigned_to,
              date_completed=:date_completed,department_id=:departmentid
              WHERE id =:taskid";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":status", $status);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":due_date", $duedate);
            $stmt->bindParam(":assigned_by", $assignedBy);
            $stmt->bindParam(":assigned_to", $assignedTo);
            $stmt->bindParam(":taskid", $taskid);
            $stmt->bindParam(":departmentid", $department);
            $stmt->bindParam(":date_completed", $dateCompleted);
            $stmt->execute();
            $sql = "SELECT tasks.id, tasks.name,tasks.description,
            tasks.due_date,tasks.department_id,
            tasks.assigned_by,
            tasks.assigned_to,
            tasks.status,
            tasks.date_completed,
            assigned_by_user.firstname AS assigned_by_firstname,
            assigned_by_user.lastname AS assigned_by_lastname,
            assigned_to_user.firstname AS assigned_to_firstname,
            assigned_to_user.lastname AS assigned_to_lastname,
            departments.name AS department_name
            FROM tasks 
            LEFT JOIN users AS assigned_by_user ON
            tasks.assigned_by=assigned_by_user.id
            LEFT JOIN users AS assigned_to_user ON
            tasks.assigned_to=assigned_to_user.id
            LEFT JOIN departments ON
            tasks.department_id=departments.id
            WHERE tasks.id=:id ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id", $taskid);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
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
            die('error updating' . $e->getMessage());
        } finally {
            $stmt->closeCursor();
        }
    }
    protected function updateOneTask($dateCompleted, $taskid, $status)
    {
        try {

            $conn = parent::connect_to_database();
            $sql = "UPDATE tasks 
              SET status =:status, 
              date_completed=:date_completed
              WHERE id =:taskid";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":status", $status);

            $stmt->bindParam(":date_completed", $dateCompleted);
            $stmt->bindParam(":taskid", $taskid);
            return  $stmt->execute() ? true : false;
        } catch (PDOException $e) {

            die('error updating' . $e->getMessage());
        } finally {
            $stmt->closeCursor();
        }
    }
    protected function unMarkOneTask($taskid, $status)
    {
        try {

            $conn = parent::connect_to_database();
            $sql = "UPDATE tasks 
              SET status =:status, 
              date_completed=NULL
              WHERE id =:taskid";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":status", $status);
            $stmt->bindParam(":taskid", $taskid);
            return  $stmt->execute() ? true : false;
        } catch (PDOException $e) {

            die('error updating' . $e->getMessage());
        } finally {
            $stmt->closeCursor();
        }
    }
    protected function DeleteOneTask($taskid)
    {
        try {

            $conn = parent::connect_to_database();
            $sql = "DELETE FROM tasks 
              WHERE id =:taskid";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":taskid", $taskid);
            return  $stmt->execute() ? true : false;
        } catch (PDOException $e) {

            die('error updating' . $e->getMessage());
        } finally {
            $stmt->closeCursor();
        }
    }

    protected function checkHeadOfDepartment($departmentId, $assignedBy, $assignedTo)
    {
        try {
            $conn = parent::connect_to_database();

            // Check if the assigning user is an admin
            $adminSql = "SELECT account_type FROM users WHERE id = :assignedBy";
            $adminStmt = $conn->prepare($adminSql);
            $adminStmt->bindParam(":assignedBy", $assignedBy, PDO::PARAM_INT);
            $adminStmt->execute();
            $accountType = $adminStmt->fetchColumn();

            // If the user is an admin, allow the action
            if ($accountType === 'admin') {
                return true;
            }

            // Check if the assigning user is the head of the department
            $headSql = "SELECT COUNT(*) 
                        FROM departments 
                        WHERE id = :departmentId AND head = :assignedBy";
            $headStmt = $conn->prepare($headSql);
            $headStmt->bindParam(":departmentId", $departmentId, PDO::PARAM_INT);
            $headStmt->bindParam(":assignedBy", $assignedBy, PDO::PARAM_INT);
            $headStmt->execute();
            $isHead = $headStmt->fetchColumn();

            if (!$isHead) {
                // The assigning user is not the head of the department
                return false;
            }

            // Check if the assigned user belongs to the same department
            $memberSql = "SELECT COUNT(*) 
                          FROM users_departments 
                          WHERE user_id = :assignedTo AND department_id = :departmentId";
            $memberStmt = $conn->prepare($memberSql);
            $memberStmt->bindParam(":assignedTo", $assignedTo, PDO::PARAM_INT);
            $memberStmt->bindParam(":departmentId", $departmentId, PDO::PARAM_INT);
            $memberStmt->execute();
            $isMember = $memberStmt->fetchColumn();
           

                return $isMember > 0;
            

           
        } catch (PDOException $e) {
            die('Error checking head of department: ' . $e->getMessage());
        } finally {
            if (isset($adminStmt)) $adminStmt->closeCursor();
            if (isset($headStmt)) $headStmt->closeCursor();
            if (isset($memberStmt)) $memberStmt->closeCursor();
        }
    }
}
