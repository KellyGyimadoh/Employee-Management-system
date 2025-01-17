<?php
class LoginController extends Login
{

    private $email;
    private $userpassword;

    private $errors;
    private $id;

    private $account_type;

    public function __construct($email, $userpassword)
    {
        parent::__construct();
        $email = $this->sanitizeData($email);
        $this->email = $this->sanitizeEmail($email);
        $this->userpassword = $this->sanitizeData($userpassword);
    }
    private function sanitizeData($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
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
    private function isEmpty()
    {
        if (empty($this->email) || empty($this->userpassword)) {
            return true;
        } else {
            return false;
        }
    }


    private function checkAccountType($account)
    {
        $allowedTypes = ['admin', 'staff'];
        $_SESSION['accounttype'] = in_array($account, $allowedTypes) ? $account : 'guest';
    }


    public function verifyUser()
{
    if ($this->inValidEmail()) {
        $this->errors['invalid email'] = 'Please enter a valid email';
    }
    if ($this->isEmpty()) {
        $this->errors['emptyfields'] = 'Please fill all fields';
    }

    if (empty($this->errors)) {
        $loginResult = $this->userlogin($this->email, $this->userpassword);
        if ($loginResult['success']) {
            $user = $loginResult['user'];
            $_SESSION['userinfo'] = $user;
            $_SESSION['loggedin'] = true;
            $_SESSION['userid'] = $user['userid'];

            $this->account_type = $user['account_type'];
            $this->checkAccountType($this->account_type);

            session_regenerate_id(true); // Prevent session fixation attacks

            return [
                'success' => true,
                'message' => "Login successful",
                'redirecturl' => $this->account_type == 'admin' ? "../../manager/home.php" : "../../user/home.php"
            ];
        } else {
            return [
                'success' => false,
                'message' => $loginResult['message'],
                'redirecturl' => '../../auth/login.php'
            ];
        }
    } else {
        return [
            'success' => false,
            'message' => 'Error occurred',
            'errors' => $this->errors,
            'redirecturl' => '../../auth/login.php'
        ];
    }
}

}
