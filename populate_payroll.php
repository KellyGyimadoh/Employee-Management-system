<?php
require_once 'DatabaseModel/configdb/Dbconnection.php'; // Adjust the path

$connection= new Dbconnection();
$conn=$connection->connect_to_database(); 

$currentMonth = date('Y-m');
$nextMonth = date('Y-m', strtotime('first day of +1 month'));

// Check if payroll records for the next month already exist
try{
$query = "SELECT COUNT(*) as count FROM payroll WHERE due_date LIKE :nextMonth";
$stmt = $conn->prepare($query);
$stmt->execute([':nextMonth' => "$nextMonth%"]);
$result = $stmt->fetch();

if ($result['count'] == 0) {
    // Insert payroll records for all users
    $insertQuery = "INSERT INTO payroll (user_id, total_salary, due_date, status)
                    SELECT id, salary, :dueDate, 'pending' FROM users";
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