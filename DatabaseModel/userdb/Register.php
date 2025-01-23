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

   protected function checkEmailUnique($newEmail, $id) {
    try {
        $conn = parent::connect_to_database();
        // Check if the new email exists in the database but exclude the current user's record
        $sql = "SELECT COUNT(*) FROM users WHERE email = :email AND id != :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":email", $newEmail);
        $stmt->bindParam(":id", $id);
        
        $stmt->execute();
        
        // Fetch the count of matching emails
        $emailExists = $stmt->fetchColumn();
        
        // If count > 0, the email is not unique
        if ($emailExists > 0) {
            return false; // Email is already in use by another user
        }
        
        return true; // Email is unique and can be used
    } catch (PDOException $e) {
        // Handle exceptions
        die('Error occurred: ' . $e->getMessage());
    }
}
protected function updateUser($id,$email,$phone,$firstname,$lastname,$account_type=null,$status=null){
    try {
        $conn=parent::connect_to_database();
        $conn->beginTransaction();
        $sql="UPDATE users SET firstname=:firstname, lastname=:lastname,email=:email,phone=:phone";

        if($account_type!==null){
            $sql.=" ,account_type=:account_type ";
        }
        if($status!==null){
            $sql.=" ,status=:status ";
        }
        $sql.=" WHERE id=:id";

       $stmt=$conn->prepare($sql);
       $stmt->bindParam(':firstname', $firstname);
       $stmt->bindParam(':lastname', $lastname);
       $stmt->bindParam(':email', $email);
       $stmt->bindParam(':phone', $phone);
       if($account_type!==null){
        $stmt->bindParam(":account_type",$account_type);
       }
       if($status!==null){
        $stmt->bindParam(":status",$status);
       }
       $stmt->bindParam(':id', $id);
      
       $stmt->execute();

       $sql = "SELECT id, firstname, lastname, email, phone,account_type,image,status FROM users WHERE id = :id";
       $stmt = $conn->prepare($sql);
       $stmt->bindParam(':id', $id);
       $stmt->execute();
       $result = $stmt->fetch(PDO::FETCH_ASSOC);

       if($conn->commit()){
        return [
            'success' => true,
             'user' => [
                            'id' => $result['id'],
                            'firstname' => $result['firstname'],
                            'lastname' => $result['lastname'],
                            'email'=>$result['email'],
                            'account_type' => $result['account_type'],
                            'image' => $result['image'],
                            'phone' => $result['phone'],
                            'status' => $result['status'],
                        ],
        ];
       }else{
        return [
            'success' => false,
            
        ];
       }

      
       
        }

    catch (PDOException $e) {
        $conn->rollBack();
        die('error updating user '.$e->getMessage());
    }

}

protected function updateUserPassword($id,$newpassword){
    try {
        $conn=parent::connect_to_database();
        $conn->beginTransaction();

        $sql="UPDATE users SET password=:password WHERE id=:id";



       $stmt=$conn->prepare($sql);
       $hashedpassword=$this->hashPassword($newpassword);
       $stmt->bindParam(':password', $hashedpassword);
       $stmt->bindParam(':id', $id); 
       $stmt->execute();

       $sql = "SELECT id, firstname, lastname, email, phone,account_type,image FROM users WHERE id = :id";
       $stmt = $conn->prepare($sql);
       $stmt->bindParam(':id', $id);
       $stmt->execute();
       $result = $stmt->fetch(PDO::FETCH_ASSOC);

       if($conn->commit()){
        return [
            'success' => true,
             'user' => [
                            'userid' => $result['id'],
                            'firstname' => $result['firstname'],
                            'lastname' => $result['lastname'],
                            'email'=>$result['email'],
                            'account_type' => $result['account_type'],
                            'image' => $result['image'],
                            'phone' => $result['phone'],
                        ],
        ];
       }else{
        return [
            'success' => false,
            
        ];
       }

      
       
        }

    catch (PDOException $e) {
        $conn->rollBack();
        die('error updating user password '.$e->getMessage());
    }

}
protected function checkPasswordMatch($id,$oldpassword) {
    try {
        $conn = parent::connect_to_database();
        // Check if the new email exists in the database but exclude the current user's record
        $sql = "SELECT password FROM users WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        
        $stmt->execute();
        $result=$stmt->fetch(PDO::FETCH_ASSOC);

        if(password_verify($oldpassword,$result['password'])){

            return true; //password matches
        }else{
            return false;
        }
    } catch (PDOException $e) {
        // Handle exceptions
        die('Error occurred: ' . $e->getMessage());
    }
}

}
