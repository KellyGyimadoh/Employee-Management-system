<?php
class RegisterController extends Register
{
    private $firstname;
    private $lastname;
    private $email;
    private $password;
    private $password_confirmation;
    private $phone;
    private $errors;
    private $id;

    private $image = null;
    private $account_type;

    public function __construct($firstname, $lastname, $email, $phone, $password, $password_confirmation, $image)
    {
        $this->firstname = $this->sanitizeData($firstname);
        $this->lastname = $this->sanitizeData($lastname);
        $email = $this->sanitizeData($email);
        $this->email = $this->sanitizeEmail($email);
        $this->phone = $this->sanitizeData($phone);
        $this->password = $this->sanitizeData($password);
        $this->password_confirmation = $this->sanitizeData($password_confirmation);
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
        if ($this->password !== $this->password_confirmation) {
            return true;
        } else {
            return false;
        }
    }
    private function isEmpty()
    {
        if (
            empty($this->firstname) || empty($this->lastname) || empty($this->email) || empty($this->phone)
            || empty($this->password) || empty($this->password_confirmation)
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
            $this->errors['passwordmismatch'] = 'Passwords donot match';
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
                $this->password,
                $this->phone,
                $this->image
            );
            if ($result) {
                if (isloggedin() && isset($_SESSION['accounttype']) && $_SESSION['accounttype'] == 'admin') {
                    return ['success' => true, 'message' => 'user added successfully', 'redirecturl' => '../../manager/home.php'];
                } else {

                    return ['success' => true, 'message' => 'Registration successfully done', 'redirecturl' => '../../auth/login.php'];
                }
            } else {

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
}
