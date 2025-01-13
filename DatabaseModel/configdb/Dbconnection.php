<?php
require_once __DIR__. '/../../config/init.php';
class Dbconnection{
    private $hostname;
    private $dbname;
    private $username;
    private $password='';

    public function __construct(){
        $this->hostname=$_ENV['DB_HOSTNAME'];
        $this->dbname=$_ENV['DB_DATABASE'];
        $this->username=$_ENV['DB_USERNAME'];
        $this->password=$_ENV['DB_PASSWORD'];
    }

    public function connect_to_database(){
        try {
           
            $conn= new PDO("mysql:host=$this->hostname;dbname=$this->dbname",$this->username,$this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            
            return $conn;
        } catch (PDOException $e) {
            die('error connecting to database'.$e->getMessage());
        }
    }

}