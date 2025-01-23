<?php
class Salary extends Dbconnection
{

    protected function addSalary($userid, $baseSalary, $bonus, $deductions, $overtime, $totalSalary)
    {
        try {
            $baseSalary = (float)$baseSalary;
            $bonus = (float)$bonus;
            $deductions = (float)$deductions;
            $overtime = (float)$overtime;
            $totalSalary = (float)$totalSalary;
            $conn = parent::connect_to_database();
            $sql = "INSERT INTO salaries (user_id,base_salary,bonus,overtime,deductions,total_salary) 
            VALUES(:user_id,:base_salary,:bonus,:overtime,:deductions,:total_salary)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":user_id", $userid);
            $stmt->bindParam(":base_salary", $baseSalary);
            $stmt->bindParam(":bonus", $bonus);
            $stmt->bindParam(":total_salary", $totalSalary);
            $stmt->bindParam(":overtime", $overtime);
            $stmt->bindParam(":deductions", $deductions);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die('error adding data' . $e->getMessage());
        }
    }

    protected function checkUserSalary($userid)
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT COUNT(*) FROM salaries WHERE user_id=:user_id ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":user_id", $userid);
            $stmt->execute();
            $result = $stmt->fetchColumn();
            if ($result && $result > 0) {
                return false;
            }
            return true;
        } catch (PDOException $e) {
            die('error adding data' . $e->getMessage());
        }
    }


    protected function getSalaryCount()
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT COUNT(*) as total FROM salaries";
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
    protected function getSalaryDetails($limit, $offset, $search = null)
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT 
            salaries.id, 
            salaries.user_id,
            salaries.base_salary,
            salaries.bonus,
            salaries.overtime,
            salaries.deductions,
            salaries.total_salary,
            users.firstname,
            users.lastname,
            users.status,
            GROUP_CONCAT(departments.name SEPARATOR ', ') AS departments
        FROM salaries
        LEFT JOIN users 
            ON salaries.user_id = users.id
        LEFT JOIN users_departments 
            ON users.id = users_departments.user_id
        LEFT JOIN departments 
            ON users_departments.department_id = departments.id";
    

            if (!empty($search)) {
                // Add WHERE clause for a case-insensitive search
                $sql .= " WHERE users.firstname LIKE :search OR users.lastname LIKE :search";
            }

            $sql .= " GROUP BY salaries.id, users.id 
           LIMIT :limit OFFSET :offset";


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

    protected function selectOneSalaryDetail($salaryid, $userid)
    {
        try {
            
            $conn = parent::connect_to_database();
            $sql = "SELECT salaries.id, salaries.user_id,salaries.base_salary,salaries.bonus,
            salaries.overtime,salaries.deductions,salaries.total_salary,
            users.firstname,users.lastname
            FROM salaries
            LEFT JOIN users ON salaries.user_id=users.id
             WHERE salaries.id=:salaryid AND salaries.user_id=:userid";

            $stmt = $conn->prepare($sql);


            $stmt->bindParam(":salaryid", $salaryid);
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
        protected function updateTheSalary($salaryid, $userid, $baseSalary, $bonus, $deductions, $overtime, $totalSalary)
    {
        try {
            $baseSalary = (float)$baseSalary;
            $bonus = (float)$bonus;
            $deductions = (float)$deductions;
            $overtime = (float)$overtime;
            $totalSalary = (float)$totalSalary;
            $conn = parent::connect_to_database();
            $conn->beginTransaction();
            $sql = "UPDATE  salaries SET base_salary=:base_salary,bonus=:bonus,
            overtime=:overtime,deductions=:deductions,total_salary=:total_salary 
            WHERE id=:salaryid AND user_id=:user_id
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":salaryid", $salaryid);
            $stmt->bindParam(":user_id", $userid);
            $stmt->bindParam(":base_salary", $baseSalary);
            $stmt->bindParam(":bonus", $bonus);
            $stmt->bindParam(":total_salary", $totalSalary);
            $stmt->bindParam(":overtime", $overtime);
            $stmt->bindParam(":deductions", $deductions);
            $stmt->execute();

            $sql = "SELECT salaries.id, salaries.user_id,salaries.base_salary,salaries.bonus,
           salaries.overtime,salaries.deductions,salaries.total_salary,
           users.firstname,users.lastname
           FROM salaries
           LEFT JOIN users ON salaries.user_id=users.id
            WHERE salaries.id=:salaryid AND salaries.user_id=:userid";

            $stmt = $conn->prepare($sql);


            $stmt->bindParam(":salaryid", $salaryid);
            $stmt->bindParam(":userid", $userid);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($conn->commit()) {
                return [
                    'success' => true,
                    'salary' => [
                        'id' => $result['id'],
                        'firstname' => $result['firstname'],
                        'lastname' => $result['lastname'],
                        'bonus' => number_format((float)$result['bonus'], 2, '.', ''),
                        'overtime' => number_format((float)$result['overtime'], 2, '.', ''),
                        'deductions' => number_format((float)$result['deductions'], 2, '.', ''),
                        'user_id' => $result['user_id'],
                        'base_salary' => number_format((float)$result['base_salary'], 2, '.', ''),
                        'total_salary' => number_format((float)$result['total_salary'], 2, '.', ''),
                    ]
                ];
            } else {
                return ['success' => false];
            }
        } catch (PDOException $e) {
            $conn->rollBack();
            die('error adding data' . $e->getMessage());
        }
    }
}
