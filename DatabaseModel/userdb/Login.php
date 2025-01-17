<?php
class Login extends Dbconnection{
   
    protected function userlogin($email,$password){
        try {
            $conn=parent::connect_to_database();
            $query="SELECT id,firstname,lastname,email,password,phone,account_type,image FROM users WHERE email=:email";
            $stmt=$conn->prepare($query);
            $stmt->bindParam(":email",$email);
            $stmt->execute();
            $result=$stmt->fetch(PDO::FETCH_ASSOC);
            
                if($result &&(password_verify($password,$result['password']))){
                    return [
                        'success' => true,
                        'user' => [
                            'userid' => $result['id'],
                            'firstname' => $result['firstname'],
                            'lastname' => $result['lastname'],
                            'email'=>$result['email'],
                            'account_type' => $result['account_type'],
                            'image' => $result['image'],
                            'phone' => $result['phone']
                        ]
                    ];
                  
                   
                }

                return ['success' => false, 'message' => 'Invalid credentials'];

            
        } catch (PDOException $e) {
            die('error connecting to database '.$e->getMessage());
        }
    }
}