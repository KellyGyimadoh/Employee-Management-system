<?php
class UpdateOneUserController extends Register
{
    private $firstname;
    private $lastname;
    private $email;
    private $phone;
    private $errors;
    private $id;

    private $account_type=null;
    private $status=null;
    public function __construct($id,$firstname, $lastname, $email, $phone,$account_type=null,$status=null)
    {
        parent::__construct();
        $this->id = $this->sanitizeData($id);
        $this->firstname = $this->sanitizeData($firstname);
        $this->lastname = $this->sanitizeData($lastname);
        $email = $this->sanitizeData($email);
        $this->email = $this->sanitizeEmail($email);
        $this->phone = $this->sanitizeData($phone);
        $this->account_type= !empty($account_type) ? $this->sanitizeData($account_type): null;
        $this->status= !empty($status) ? $this->sanitizeData($status): null;
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

    
   
    private function isEmpty()
    {
        if (
            empty($this->firstname) || empty($this->lastname) || empty($this->email) || empty($this->phone)
           
        ) {

            return true;
        } else {
            return false;
        }
    }
    private function emailIsUniqueExist()
    {
        if ($this->checkEmailUnique($this->email,$this->id)) {
            return true;
        } else {
            return false;
        }
    }

    public function updateUserProfile()
    {
        if ($this->isEmpty()) {
            $this->errors['emptyfield'] = 'Please fill all input';
        }
        if ($this->digitOnly()) {
            $this->errors['lowphone'] = 'Digit not up to 10';
        }
        if (!$this->emailIsUniqueExist()) {
            $this->errors['emailexist'] = 'Email already exist';
        }
      
        if ($this->inValidEmail()) {
            $this->errors['invalidemail'] = 'email is not valid';
        }
        if ($this->inValidPhone()) {
            $this->errors['invalidphone'] = 'Phone number not valid';
        }

        if (empty($this->errors)) {
            $result = $this->updateUser(
                $this->id,
                $this->email,
                $this->phone,
                $this->firstname,
                $this->lastname,
                $this->account_type,
                $this->status
            );
            if ($result['success']) {

                if(isset($_SESSION['userdetails'])){
                    unset($_SESSION['userdetails']);
                    $user=$result['user'];
                    $_SESSION['userdetails']=$user;
                }
               
                if (isloggedin() && isset($_SESSION['accounttype']) && in_array($_SESSION['accounttype'],['admin'])) {
                    
                    return ['success' => true, 'message' => 'user details successfully updated',];
                } else {

                    return ['success' => false, 'message' => 'Not allowed',];
                }
            } else{
                return ['success' => false, 'message' => 'Update failed', 'errors' => $this->errors, ];
    
            }
        }else {
            return ['success' => false, 'message' => 'Registration failed', 'errors' => $this->errors, 'redirecturl' => '../../auth/register.php'];
        }
    }
}
