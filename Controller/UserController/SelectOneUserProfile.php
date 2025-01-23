<?php
class SelectOneUserProfile extends ViewUser{

private $id;
    public function __construct($id)
    {
        parent::__construct();
        $id=$this->sanitizeNumber($id);
        $this->id=$this->sanitizeData($id);

    }
    private function sanitizeData($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = filter_var($data, FILTER_SANITIZE_SPECIAL_CHARS);
        return $data;
    }
    private function sanitizeNumber(int $number){
        $number=filter_var($number,FILTER_SANITIZE_NUMBER_INT);
        return $number;
    }

    public function viewUserDetail(){
        $result=$this->selectOneUserDetail($this->id);
        if(!empty($result)){
            $_SESSION['userdetails']=$result;
            header("Location: ../../users/usersprofile.php");
        }
    }
}