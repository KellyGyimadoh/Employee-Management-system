<?php
class Attendance extends Dbconnection
{
    protected function updateAttendance($userid, $checkinTime, $currentDate, $status)
    {
        try {

            $conn = parent::connect_to_database();
            $sql = "UPDATE attendance 
              SET status =:status, checkin_time =:checkinTime 
              WHERE user_id =:user_id AND date =:date";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":status", $status);
            $stmt->bindParam(":user_id", $userid);
            $stmt->bindParam(":checkinTime", $checkinTime);
            $stmt->bindParam(":date", $currentDate);


            if ($stmt->execute()) {

                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die('error updating' . $e->getMessage());
        } finally {
            $stmt->closeCursor();
        }
    }

    protected function updateUserAttendance($userid, $checkinTime, $date, $status,$attendanceid)
    {
        try {

            $conn = parent::connect_to_database();
            $conn->beginTransaction();
            $sql = "UPDATE attendance 
              SET status =:status, checkin_time =:checkinTime,date=:date
              WHERE id =:attendanceid AND user_id =:user_id  ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":status", $status);
            $stmt->bindParam(":user_id", $userid);
            $stmt->bindParam(":attendanceid", $attendanceid);
            $stmt->bindParam(":checkinTime", $checkinTime);
            $stmt->bindParam(":date", $date);
            $stmt->execute();
            $sql = "SELECT 
            attendance.id,
            attendance.status,
            attendance.date,
            attendance.user_id,
            attendance.checkin_time,
            users.firstname,
            users.lastname
            FROM attendance
            LEFT JOIN users 
            ON attendance.user_id = users.id
            WHERE attendance.id=:attendanceid AND attendance.user_id=:userid";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":attendanceid", $attendanceid);
            $stmt->bindParam(":userid", $userid);
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

    protected function alreadyCheckedIn($userid, $currentDate)
    {
        try {

            $conn = parent::connect_to_database();
            $sql = "SELECT COUNT(checkin_time) FROM attendance 
              WHERE user_id =:user_id AND date =:date";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(":user_id", $userid);
            $stmt->bindParam(":date", $currentDate);
            $stmt->execute();
            $result = $stmt->fetchColumn();
            if ($result && $result > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die('error updating' . $e->getMessage());
        } finally {
            $stmt->closeCursor();
        }
    }

    protected function hasRecordsForDate($currentDate)
{
    try {
        $conn = parent::connect_to_database();
        $sql = "SELECT COUNT(*) FROM attendance WHERE date = :date";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(":date", $currentDate, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn() > 0; // Return true if records exist
    } catch (PDOException $e) {
        die('Error checking attendance records: ' . $e->getMessage());
    } finally {
        $stmt->closeCursor();
    }
}

    protected function checkUserExist($userid)
    {
        try {

            $conn = parent::connect_to_database();
            $sql = "SELECT COUNT(user_id) FROM attendance 
              WHERE user_id =:user_id";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(":user_id", $userid);
            $stmt->execute();
            $result = $stmt->fetchColumn();
            if ($result && $result > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die('error updating' . $e->getMessage());
        } finally {
            $stmt->closeCursor();
        }
    }

    protected function getAllAttendance($limit, $offset, $search = null, $date = null)
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT 
           attendance.id,
           attendance.status,
           attendance.date,
           attendance.user_id,
           attendance.checkin_time,
            users.firstname,
            users.lastname
        FROM attendance
        LEFT JOIN users 
            ON attendance.user_id = users.id";

            $conditions = []; // To dynamically build conditions

            if (!empty($search)) {
                $conditions[] = "(users.firstname LIKE :search OR users.lastname LIKE :search)";
            }

            if (!empty($date)) {
                $conditions[] = "attendance.date = :date";
            }

            // Add conditions dynamically
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(" AND ", $conditions); // Combine conditions with AND
            }

            $sql .= " ORDER BY attendance.date DESC, attendance.id ASC 
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

    protected function getAttendanceCount()
    {

        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT COUNT(*) as total FROM attendance";
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

    //for today

    protected function getAttendanceToday($limit, $offset, $search = null, $date = null)
    {
        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT 
           attendance.id,
           attendance.status,
           attendance.date,
           attendance.user_id,
           attendance.checkin_time,
            users.firstname,
            users.lastname
        FROM attendance
        LEFT JOIN users 
            ON attendance.user_id = users.id
           WHERE date=CURDATE() 
            ";


            $conditions = []; // To dynamically build conditions

            if (!empty($search)) {
                $conditions[] = "(users.firstname LIKE :search OR users.lastname LIKE :search)";
            }

            if (!empty($date)) {
                $conditions[] = "attendance.date = :date";
            }

            // Add conditions dynamically
            if (!empty($conditions)) {
                $sql .= " AND " . implode(" AND ", $conditions); // Combine conditions with AND
            }

            $sql .= " ORDER BY attendance.date DESC
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

    protected function getTodayTotalCount()
    {
        $today = date('Y-m-d');
        try {
            $conn = parent::connect_to_database();
            $sql = "SELECT COUNT(*) as total FROM attendance WHERE date=CURDATE()";
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
    protected function selectOneAttendanceDetail($attendanceid, $userid)
    {
        try {

            $conn = parent::connect_to_database();
            $sql = "SELECT 
                    attendance.id,
                    attendance.status,
                    attendance.date,
                    attendance.user_id,
                    attendance.checkin_time,
                    users.firstname,
                    users.lastname
                    FROM attendance
                    LEFT JOIN users 
                    ON attendance.user_id = users.id
                    WHERE attendance.id=:attendanceid AND attendance.user_id=:userid";



            $stmt = $conn->prepare($sql);


            $stmt->bindParam(":attendanceid", $attendanceid);
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


    protected function getTodayTotalDetails()
    {
        try {
            $conn = parent::connect_to_database();
            $presentSql = "SELECT COUNT(*) as total_present FROM attendance WHERE date=CURDATE() AND status=2";
            $presentStmt = $conn->prepare($presentSql);
            $presentStmt->execute();
            $presentResult = $presentStmt->fetch(PDO::FETCH_ASSOC);
            

            $absentSql = "SELECT COUNT(*) as total_absent FROM attendance WHERE date=CURDATE() AND status=1";
            $absentStmt = $conn->prepare($absentSql);
            $absentStmt->execute();
            $absentResult = $absentStmt->fetch(PDO::FETCH_ASSOC);


            $lateSql = "SELECT COUNT(*) as total_late FROM attendance WHERE date=CURDATE() AND status=3";
            $lateStmt = $conn->prepare($lateSql);
            $lateStmt->execute();
            $lateResult = $lateStmt->fetch(PDO::FETCH_ASSOC);




            return [
                'total_present'=>$presentResult['total_present'],
                'total_absent'=>$absentResult['total_absent'],
                'total_late'=>$lateResult['total_late']
            ];
        } catch (PDOException $e) {
            die('error occured' . $e->getMessage());
        }
    }
}
