<?php
require_once 'DatabaseModel/configdb/Dbconnection.php'; // Adjust the path

try {
    $connection = new Dbconnection();
    $conn = $connection->connect_to_database(); 

    $currentMonth = date('Y-m');
    $nextMonth = date('Y-m', strtotime('first day of +1 month'));

    // Check if payroll records for the next month already exist using YEAR() and MONTH()
    $query = "SELECT COUNT(*) as count FROM payroll WHERE YEAR(due_date) = YEAR(:nextMonth)
     AND MONTH(due_date) = MONTH(:nextMonth)";
    $stmt = $conn->prepare($query);
    $stmt->execute([':nextMonth' => "$nextMonth-01"]); // Passing the first day of the next month
    $result = $stmt->fetch();

    if ($result['count'] == 0) {
        // Insert payroll records for all users
        $insertQuery = "INSERT INTO payroll (user_id, total_salary, due_date)
                        SELECT u.id, s.total_salary, :dueDate
                        FROM users u
                        JOIN salaries s ON u.id = s.user_id"; // Join the users and salary tables
        $stmt = $conn->prepare($insertQuery);
        $stmt->execute([':dueDate' => "$nextMonth-28"]); // Example: End of the month
        echo "Payroll records for $nextMonth created.";
    } else {
        echo "Payroll records for $nextMonth already exist.";
    }
    

} catch (PDOException $e) {
    // Handle database-related exceptions
    echo "Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    // Handle other exceptions
    echo "General error: " . $e->getMessage() . "\n";
}
