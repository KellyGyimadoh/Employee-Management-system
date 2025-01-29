<?php
class Payroll extends Dbconnection
{

    protected function addPayroll($userid, $status, $date = null, $totalSalary, $duedate = null)
    {
        try {

            $conn = parent::connect_to_database();
            $sql = "INSERT INTO payroll (user_id,status";
            if (!empty($date)) {
                $sql .= " ,date";
            }
            if (!empty($duedate)) {
                $sql .= " ,due_date";
            }
            $sql .= " ,total_salary) VALUES
           (:userid,:status";
            if (!empty($date)) {
                $sql .= " ,:date";
            }
            if (!empty($duedate)) {
                $sql .= " ,:due_date";
            }
            $sql .= " ,:total_salary)";



            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":userid", $userid);
            $stmt->bindParam(":status", $status);
            if (!empty($date)) {

                $stmt->bindParam(":date", $date);
            }
            if (!empty($duedate)) {

                $stmt->bindParam(":due_date", $duedate);
            }
            $stmt->bindParam(":total_salary", $totalSalary);


            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die('error fetching data' . $e->getMessage());
        }
    }
    protected function checkSalaryAmount($userid, $totalSalary)
    {
        try {

            $conn = parent::connect_to_database();
            $sql = "SELECT total_salary,user_id FROM  salaries WHERE user_id=:userid";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":userid", $userid);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && $result['total_salary'] == $totalSalary) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die('error fetching data' . $e->getMessage());
        }
    }


    protected function getSalaryDetail($userid)
    {
        try {

            $conn = parent::connect_to_database();
            $sql = "SELECT salaries.id, salaries.user_id,salaries.total_salary,
            users.firstname,users.lastname
            FROM salaries
            LEFT JOIN users ON salaries.user_id=users.id
             WHERE salaries.user_id=:userid";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":userid", $userid);

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
    protected function getPayrollCount()
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT COUNT(*) as total FROM payroll";
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

    protected function getPayrollDetails($limit, $offset, $search = null, $date = null)
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT 
            payroll.id,
            payroll.status,
            payroll.date,
            payroll.total_salary,
            payroll.user_id,
            payroll.due_date,
            users.firstname,
            users.lastname
        FROM payroll
        LEFT JOIN users 
            ON payroll.user_id = users.id";

            $conditions = []; // To dynamically build conditions

            if (!empty($search)) {
                $conditions[] = "(users.firstname LIKE :search OR users.lastname LIKE :search)";
            }

            if (!empty($date)) {
                $conditions[] = "payroll.due_date = :date";
            }

            // Add conditions dynamically
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(" AND ", $conditions); // Combine conditions with AND
            }

            $sql .= " ORDER BY payroll.due_date DESC
           LIMIT :limit OFFSET :offset";


            $stmt = $conn->prepare($sql);
            if (!empty($search)) {
                $stmt->bindValue(":search", "%$search%");
            }
            if (!empty($date)) {
                $stmt->bindValue(":date", $date);
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


    protected function selectOnePayrollDetail($payrollid, $userid)
    {
        try {

            $conn = parent::connect_to_database();
            $sql = "SELECT 
            payroll.id,
            payroll.status,
            payroll.date,
            payroll.due_date,
            payroll.total_salary,
            payroll.user_id,
            users.firstname,
            users.lastname
        FROM payroll
        LEFT JOIN users 
            ON payroll.user_id = users.id
             WHERE payroll.id=:payrollid AND payroll.user_id=:userid";

            $stmt = $conn->prepare($sql);


            $stmt->bindParam(":payrollid", $payrollid);
            $stmt->bindParam(":userid", $userid);

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

    protected function updatePayroll($payrollid, $status, $date = null, $duedate = null)
    {
        try {

            $conn = parent::connect_to_database();
            $conn->beginTransaction();
            $sql = "UPDATE payroll SET status=:status
           
            ";
            if (!empty($date)) {
                $sql .= " ,date=:date ";
            }
            if (!empty($duedate)) {
                $sql .= " ,due_date=:duedate ";
            }
            $sql .= "  WHERE id=:payrollid";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":status", $status);
            if (!empty($date)) {

                $stmt->bindParam(":date", $date);
            }
            if (!empty($duedate)) {

                $stmt->bindParam(":duedate", $duedate);
            }
            $stmt->bindParam(":payrollid", $payrollid);
            $stmt->execute();

            $sql = "SELECT 
            payroll.id,
            payroll.status,
            payroll.date,
            payroll.due_date,
            payroll.total_salary,
            payroll.user_id,
            users.firstname,
            users.lastname
        FROM payroll
        LEFT JOIN users 
            ON payroll.user_id = users.id
             WHERE payroll.id=:payrollid";

            $stmt = $conn->prepare($sql);


            $stmt->bindParam(":payrollid", $payrollid);


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
            die('error fetching data' . $e->getMessage());
        }
    }

    protected function makePaymentForPayroll($payrollid, $date)
    {
        $status = 'paid';
        try {
            $conn = parent::connect_to_database();
            $sql = "UPDATE payroll SET status=:status,date=:date WHERE id=:payrollid ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":status", $status);
            $stmt->bindParam(":date", $date);
            $stmt->bindParam(":payrollid", $payrollid);

            if ($stmt->execute()) {

                return true;
            } else {

                return false;
            }
        } catch (PDOException $e) {
            die('error occured' . $e->getMessage());
        }
    }
    protected function checkPaymentStatus($payrollid)
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT status FROM payroll WHERE id=:payrollid";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":payrollid", $payrollid);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result['status'] == 'paid') {

                return true;
            }

            return false;
        } catch (PDOException $e) {
            die('error occured' . $e->getMessage());
        }
    }
    //get recent month payroll

    protected function getPayrollCountForRecentMonth()
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT COUNT(*) as total FROM payroll WHERE 
            MONTH(due_date)=MONTH(CURDATE()) AND 
            YEAR(due_date)=YEAR(CURDATE())
            
            ";
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

    protected function getPayrollDetailsForRecentMonth($limit, $offset, $search = null, $date = null)
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "
            SELECT 
                payroll.id,
                payroll.status,
                payroll.date,
                payroll.total_salary,
                payroll.user_id,
                payroll.due_date,
                users.firstname,
                users.lastname
            FROM payroll
            LEFT JOIN users 
                ON payroll.user_id = users.id
            WHERE MONTH(due_date) = MONTH(CURDATE()) 
              AND YEAR(due_date) = YEAR(CURDATE())
            ";

            if (!empty($search)) {
                // Add conditions for search
                $sql .= " AND (users.firstname LIKE :search OR users.lastname LIKE :search)";
            }

            if (!empty($date)) {
                // Add condition for specific date
                $sql .= " AND payroll.date = :date";
            }

            // Add pagination
            $sql .= " LIMIT :limit OFFSET :offset";

            $stmt = $conn->prepare($sql);

            // Bind values
            if (!empty($search)) {
                $stmt->bindValue(":search", "%$search%");
            }
            if (!empty($date)) {
                $stmt->bindValue(":date", $date);
            }
            $stmt->bindValue(":offset", (int)$offset, PDO::PARAM_INT);
            $stmt->bindValue(":limit", (int)$limit, PDO::PARAM_INT);

            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: []; // Return result or empty array if no rows found
        } catch (PDOException $e) {
            die('Error occurred: ' . $e->getMessage());
        }
    }
}
