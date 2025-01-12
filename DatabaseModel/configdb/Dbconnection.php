<?php
class Dbconnection{
    private $hostname='localhost';
    private $dbname='employeedatabase';
    private $username='root';
    private $password='';

    protected function connect_to_database(){
        try {
            $conn= new PDO("mysql:host=$this->hostname;dbname=$this->dbname",$this->username,$this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die('error connecting to database'.$e->getMessage());
        }
    }

}