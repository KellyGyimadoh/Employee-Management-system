<?php
class ExportOneUserProfile extends ViewUser {

    private $id;

    public function __construct($id) {
        parent::__construct();
        $id = $this->sanitizeNumber($id);
        $this->id = $this->sanitizeData($id);
    }

    private function sanitizeData($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
        return $data;
    }

    private function sanitizeNumber(int $number) {
        $number = filter_var($number, FILTER_SANITIZE_NUMBER_INT);
        return $number;
    }

    public function exportUserDetail() {
        $result = $this->OneUsersSalaryAndProfileDetailsForCsv($this->id);

        if (!empty($result)) {
            $fileName = "user_{$this->id}.csv";

            // Set CSV headers
            header('Content-Type: text/csv; charset=utf-8');
            header("Content-Disposition: attachment; filename=$fileName");

            // Open file stream
            $output = fopen('php://output', 'w');

            // Add column headers
            fputcsv($output, ['ID', 'FirstName','Lastname', 'Email','Phone', 'Department',
             'Salary','Bonuses','Deductions']);

            // Add user data
            fputcsv($output, [$result['id'], $result['firstname'],$result['lastname'],
             $result['email'],$result['phone'], $result['user_departments'],
              $result['total_salary'],$result['bonus'],$result['deductions']]);

            // Close output stream
            fclose($output);
            exit;
        } else {
            echo "User not found!";
        }
    }
}

