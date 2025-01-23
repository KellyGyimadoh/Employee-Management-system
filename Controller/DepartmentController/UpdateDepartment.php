<?php
class UpdateDepartment extends Department
{

    private $id;
    private $name;
    private $head;
    private $email;
    private $phone;
    private $status;
    private $errors;
    public function __construct($id, $name, $head = null, $phone = null, $email = null, $status)
    {
        parent::__construct();
        $this->id = $this->sanitizeData($id);
        $this->name = $this->sanitizeData($name);
        $this->head = !empty($head) ? $this->sanitizeData($head) : null;

        $email = !empty($email) ? $this->sanitizeData($email) : null;
        $this->email = !empty($email) ? $this->sanitizeEmail($email) : null;
        $this->phone = !empty($phone) ? $this->sanitizeData($phone) : null;
        $this->status = !empty($status) ? $this->sanitizeData($status) : null;
    }

    private function emailIsUniqueExist()
    {
        if ($this->checkEmailUnique($this->id, $this->email)) {
            return true;
        } else {
            return false;
        }
    }
    private function nameExist()
    {
        if ($this->checkNameUnique($this->id, $this->name)) {
            return true;
        } else {
            return false;
        }
    }
    private function isEmpty()
    {
        if (empty($this->name)) {

            return true;
        } else {
            return false;
        }
    }
    private function sanitizeData($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
        $data = htmlspecialchars($data);
        return $data;
    }

    private function sanitizeEmail($email)
    {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        return $email;
    }

    private function inValidEmail()
    {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }


    private function digitOnly()
    {
        $phoneNumber = preg_replace("/[^0-9]/", "", $this->phone);
        return strlen($phoneNumber) !== 10;
    }
    public function updateNewDepartment()
    {
        if (!empty($this->email) && $this->inValidEmail()) {
            $this->errors['invalid email'] = 'invalid email address';
        }
        if (!empty($this->phone) && $this->digitOnly()) {
            $this->errors['invalid phone'] = 'digit not up to 10';
        }
        if (!empty($this->email) && !$this->emailIsUniqueExist()) {
            $this->errors['email exist'] = 'email already exist';
        }
        if (!empty($this->email) && !$this->nameExist()) {
            $this->errors['name exist'] = 'department name already exist';
        }
        if ($this->isEmpty()) {
            $this->errors['empty'] = 'Please provide a name';
        }
        if (empty($this->errors)) {
            $result = $this->updateDepartment(
                $this->id,
                $this->name,
                $this->status,
                $this->email,
                $this->phone,
                $this->head
            );
            if ($result['success']) {
                if (isset($_SESSION['departmentdetails'])) {
                    unset($_SESSION['departmentdetails']);
                    $_SESSION['departmentdetails'] = $result['department'];
                }
                if (isloggedin() && isset($_SESSION['accounttype']) && in_array($_SESSION['accounttype'], ['admin'])) {

                    return ['success' => true, 'message' => 'Department details successfully updated',];
                } else {

                    return ['success' => false, 'message' => 'Not allowed',];
                }
            } else {
                return ['success' => false, 'message' => 'Failed to update... try again'];
            }
        } else {
            return ['success' => false, 'message' => 'Failed to add', 'errors' => $this->errors];
        }
    }
}
