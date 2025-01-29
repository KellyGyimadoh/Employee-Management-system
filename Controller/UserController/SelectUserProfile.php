<?php
class SelectUserProfile extends ViewUser{

private $limit;
private $offset;
private $search=null;

private $account_type=null;
    public function __construct($limit,$offset,$search=null,$account_type=null){
        parent::__construct();
        $limit=$this->sanitizeNumber($limit);
        $this->limit=$this->sanitizeData($limit);

        $offset=$this->sanitizeNumber($offset);
        $this->offset=$this->sanitizeData($offset);

        $this->search= !empty($search) ? $this->sanitizeData($search) : null;
        $this->account_type= !empty($account_type) ? $this->sanitizeData($account_type) : null;
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

    public function getUserCount(){
            return $this->usersCount($this->account_type);
    }

    public function getUserProfileDetails(){
        $result= $this->getUserDetails($this->limit,$this->offset,$this->search);
        if($result){
           return $result;
        }
        return [];
    }

    public function getAllUsers(){
        $result=$this->allUsers();
        if($result){
            return $result;
        }
        return [];
    }

    public function AllUsersSummary(){
        return $this->usersCountSummary()?:$this->usersCountSummary();
    }
}