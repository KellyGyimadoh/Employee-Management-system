<?php
// populate_attendance.php

// Include database connection file
require_once 'DatabaseModel/configdb/Dbconnection.php'; // Update this with your actual DB connection file

try {
    // Set the current date
    $currentDate = date('Y-m-d');

    // Create a database connection
    $connect = new Dbconnection();
    $conn = $connect->connect_to_database();

    // Check if the attendance table is already populated for today
    $query = "SELECT COUNT(*) AS count FROM attendance WHERE date = :date";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':date', $currentDate);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC); // Use fetch instead of fetchAll for a single row

    if ($result['count'] == 0) {
        // Populate the attendance table for all users
        $insertQuery = "INSERT INTO attendance (user_id, date, status) 
                        SELECT id, :date, 1 FROM users"; // Default status is 1 (Absent)
        $stmt = $conn->prepare($insertQuery);
        $stmt->bindParam(':date', $currentDate);

        if ($stmt->execute()) {
            echo "Attendance table populated for date: $currentDate\n";
        } else {
            echo "Error populating attendance.\n";
        }
    } else {
        echo "Attendance already populated for today ($currentDate).\n";
    }

} catch (PDOException $e) {
    // Handle database-related exceptions
    echo "Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    // Handle other exceptions
    echo "General error: " . $e->getMessage() . "\n";
}

