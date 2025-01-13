<?php

class Register extends Dbconnection
{
  
    private function hashPassword($password){
        $options=['cost'=>12];
    $password=password_hash($password,PASSWORD_DEFAULT,$options );
    return $password;
    }
   protected function addNewUser($firstname,$lastname,$email,$password,$phone,$image){
        try {
            $conn=parent::connect_to_database();
            $sql="INSERT INTO users (firstname,lastname,email,password,phone,image,account_type) VALUES (:firstname,:lastname,:email,:password,:phone,:image,:accounttype)";
            $stmt=$conn->prepare($sql);
            $accounttype='staff';
            $hashedpassword=$this->hashPassword($password);
            $stmt->bindParam(':firstname',$firstname);
            $stmt->bindParam(':lastname',$lastname);
            $stmt->bindParam(':email',$email);
            $stmt->bindParam(':phone',$phone);
            $stmt->bindParam(':password',$hashedpassword);
            $stmt->bindParam(':image',$image);
            $stmt->bindParam(":accounttype",$accounttype);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
            
        } catch (PDOException $e) {
            die('error inserting data'.$e->getMessage());
        }
   }

   protected function checkEmailExist($email){
    try {
        $conn=parent::connect_to_database();
        $sql="SELECT email FROM users WHERE email= :email";
        $stmt=$conn->prepare($sql);
        $stmt->bindParam(":email",$email);
       $stmt->execute();
       
       $result=$stmt->fetch(PDO::FETCH_ASSOC);
       if($result && $email==$result['email']){
        return true;
       }else{
        return false;
       }

    } catch (PDOException $e) {
        die('error occured'.$e->getMessage());
    }
   }
}
