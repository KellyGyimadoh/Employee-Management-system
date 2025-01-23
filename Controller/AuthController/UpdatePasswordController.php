<?php
class UpdatePasswordController extends Register
{
    private $oldpassword;
    private $newpassword;
  
    private $errors;
    private $id;

    private $account_type=null;

    public function __construct($id,$newpassword, $oldpassword)
    {
        parent::__construct();
        $this->id = $this->sanitizeData($id);
        $this->oldpassword = $this->sanitizeData($oldpassword);
        $this->newpassword = $this->sanitizeData($newpassword);
    }
    private function sanitizeData($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
        $data = htmlspecialchars($data);
        return $data;
    }
    
   
    private function isEmpty()
    {
        if (
            empty($this->oldpassword) || empty($this->id) || empty($this->newpassword)
           
        ) {

            return true;
        } else {
            return false;
        }
    }
   
    private function passwordMismatch(){
        if($this->checkPasswordMatch($this->id,$this->oldpassword)){
            return true;
        }else{
            return false;
        }
    }

    public function updatePassword()
    {
        if ($this->isEmpty()) {
            $this->errors['emptyfield'] = 'Please fill all input';
        }
        if(!$this->passwordMismatch()){
            $this->errors['passwordmismatch']='Wrong password input';
        }
       
       
        if (empty($this->errors)) {
            $result = $this->updateUserPassword(
                $this->id,
                $this->newpassword
            );
            if ($result['success']) {
                if(isset($_SESSION['userinfo'])){
                    unset($_SESSION['userinfo']);
                    $user=$result['user'];
                    $_SESSION['userinfo']=$user;
                }
                if(isset($_SESSION['accounttype'])){
                    $_SESSION['accounttype']=$user['account_type'];
                }
                if (isloggedin() && isset($_SESSION['accounttype']) && in_array($_SESSION['accounttype'],['admin','staff'])) {
                    
                    return ['success' => true, 'message' => 'Password successfully updated',];
                } else {

                    return ['success' => false, 'message' => 'Not allowed',];
                }
            } else{
                return ['success' => false, 'message' => 'Update failed', 'errors' => $this->errors, ];
    
            }
        }else {
            return ['success' => false, 'message' => 'Update failed', 'errors' => $this->errors];
        }
    }
}
