<?php
class RegisterController extends Register
{
    private $firstname;
    private $lastname;
    private $email;
    private $userpassword;
    private $userpassword_confirmation;
    private $phone;
    private $errors;
    private $id;

    private $image = null;
    private $account_type;

    public function __construct($firstname, $lastname, $email, $phone, $userpassword, $userpassword_confirmation, $image)
    {
        parent::__construct();
        $this->firstname = $this->sanitizeData($firstname);
        $this->lastname = $this->sanitizeData($lastname);
        $email = $this->sanitizeData($email);
        $this->email = $this->sanitizeEmail($email);
        $this->phone = $this->sanitizeData($phone);
        $this->userpassword = $this->sanitizeData($userpassword);
        $this->userpassword_confirmation = $this->sanitizeData($userpassword_confirmation);
        $this->image = $this->sanitizeData($image);
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

    private function inValidPhone()
    {
        if (!filter_var($this->phone, FILTER_VALIDATE_INT)) {
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

    private function checkAccountType() {}

    private function password_mismatch()
    {
        if ($this->userpassword !== $this->userpassword_confirmation) {
            return true;
        } else {
            return false;
        }
    }
   
    private function isEmpty()
    {
        if (
            empty($this->firstname) || empty($this->lastname) || empty($this->email) || empty($this->phone)
            || empty($this->userpassword) || empty($this->userpassword_confirmation)
        ) {

            return true;
        } else {
            return false;
        }
    }
    private function emailExist()
    {
        if ($this->checkEmailExist($this->email)) {
            return true;
        } else {
            return false;
        }
    }

    public function registerUser()
    {
        if ($this->isEmpty()) {
            $this->errors['emptyfield'] = 'Please fill all input';
        }
        if ($this->digitOnly()) {
            $this->errors['lowphone'] = 'Digit not up to 10';
        }
        if ($this->emailExist()) {
            $this->errors['emailexist'] = 'Email already exist';
        }
        if ($this->password_mismatch()) {
            $this->errors['passwordmismatch'] = 'passwords donot match';
        }
        if ($this->inValidEmail()) {
            $this->errors['invalidemail'] = 'email is not valid';
        }
        if ($this->inValidPhone()) {
            $this->errors['invalidphone'] = 'Phone number not valid';
        }

        if (empty($this->errors)) {
            $result = $this->addNewUser(
                $this->firstname,
                $this->lastname,
                $this->email,
                $this->userpassword,
                $this->phone,
                $this->image
            );
            if ($result) {
                if(isset($_SESSION['signupdata'])){
                    unset($_SESSION['signupdata']);
                }
                if (isloggedin() && isset($_SESSION['accounttype']) && $_SESSION['accounttype'] == 'admin') {
                    
                    return ['success' => true, 'message' => 'user added successfully', 'redirecturl' => '../../manager/home.php'];
                } else {

                    return ['success' => true, 'message' => 'Registration successfully done', 'redirecturl' => '../../auth/login.php'];
                }
            } else{
                return ['success' => false, 'message' => 'Registration failed', 'errors' => $this->errors, 'redirecturl' => '../../auth/register.php'];
    
            }
        }else {

            $signupdata = [
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'email' => $this->email,
                'phone' => $this->phone,
                'image' => $this->image,
            ];
            $_SESSION['signupdata'] = $signupdata;
            $_SESSION['signuperrors'] = $this->errors;
            return ['success' => false, 'message' => 'Registration failed', 'errors' => $this->errors, 'redirecturl' => '../../auth/register.php'];
        }
    }
}
