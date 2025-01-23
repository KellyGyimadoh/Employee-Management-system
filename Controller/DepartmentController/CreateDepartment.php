<?php
class CreateDepartment extends Department{
private $name;
private $head;
private $email;
private $phone;
private $errors;
public function __construct($name,$head=null,$phone=null,$email=null){
    parent::__construct();
    $this->name=$this->sanitizeData($name);
    $this->head=!empty($head)?$this->sanitizeData($head):null;

    $email =!empty($email) ? $this->sanitizeData($email) : null;
    $this->email =!empty($email)? $this->sanitizeEmail($email) :null;
    $this->phone =!empty($phone)? $this->sanitizeData($phone) : null;
}

private function emailExist()
{
    if ($this->checkEmailExist($this->email)) {
        return true;
    } else {
        return false;
    }
}
private function isEmpty()
{
    if ( empty($this->name)
    ) {

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
    public function createNewDepartment(){
        if(!empty($this->email) && $this->inValidEmail()){
            $this->errors['invalid email']='invalid email address';
        }
        if(!empty($this->phone) && $this->digitOnly()){
            $this->errors['invalid phone']='digit not up to 10';

        }
        if(!empty($this->email) && $this->emailExist()){
            $this->errors['email exist']='email already exist';
        }
        if($this->isEmpty()){
            $this->errors['empty']='Please provide a name';
        }
        if(empty($this->errors)){
            $result=$this->addNewDepartment($this->name,$this->head,$this->email,$this->phone);
            if($result){
                return['success'=>true,'message'=>'Added New department'];
            }else{
                return['success'=>false,'message'=>'Failed to add... try again'];
            }

        }else{
            return['success'=>false,'message'=>'Failed to add','errors'=>$this->errors];
        }
    }

}