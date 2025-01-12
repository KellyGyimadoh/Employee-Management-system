<?php
class LoginController extends Dbconnection
{
    private $firstname;
    private $lastname;
    private $email;
    private $password;
    private $password_confirmation;
    private $phone;
    private $errors;
    private $id;

    private $account_type;

    public function __construct($firstname, $lastname, $email, $phone, $password, $password_confirmation)
    {
        $this->firstname = $this->sanitizeData($firstname);
        $this->lastname = $this->sanitizeData($lastname);
        $email = $this->sanitizeData($email);
        $this->email = $this->sanitizeEmail($email);
        $this->phone = $this->sanitizeData($phone);
        $this->password = $this->sanitizeData($password);
        $this->password_confirmation = $this->sanitizeData($password_confirmation);
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
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            return true;
        }else{
            return false;
        }
     
    }

    private function inValidPhone($phone)
    {
         if(!filter_var($phone, FILTER_VALIDATE_INT)){
            return true;
         }else{
            return false;
         }
    }
    private function digitOnly()
    {
        $phoneNumber = preg_replace("/[^0-9]/", "", $this->phone);
        return strlen($phoneNumber) !== 10;
    }

    private function checkAccountType(){
        
    }
    private function emailExist(){

    }
}
