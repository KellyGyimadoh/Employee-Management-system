<?php
class ExportAllUsers extends ViewUser {

    public function __construct() {
        parent::__construct();
       
    }
    public function exportUsersCSV() {
        $users = $this->AllUsersSalaryAndProfileDetailsForCsv();

        if (!empty($users)) {
            $fileName = "all_users.csv";

            // Set CSV headers
            header('Content-Type: text/csv; charset=utf-8');
            header("Content-Disposition: attachment; filename=$fileName");

            // Open file stream
            $output = fopen('php://output', 'w');

            // Add column headers
            fputcsv($output, ['ID', 'FirstName','Lastname', 'Email','Phone', 
            'Department', 'Total Salary','Bonuses','Deductions']);

            // Add users' data
            foreach ($users as $user) {
                fputcsv($output, [$user['id'], $user['firstname'],$user['lastname'],
                $user['email'],$user['phone'], $user['user_departments'], $user['total_salary'],$user['bonus'],$user['deductions']]);
            }

            fclose($output);
            exit;
        } else {
            echo "No users found!";
        }
    }
}

