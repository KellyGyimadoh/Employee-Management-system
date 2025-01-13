<?php
require_once __DIR__. '/../../config/init.php';
trait Initializer {
    protected $hostname;
    protected $dbname;
    protected $username;
    protected $password;
 
    protected function initializeDbProperties() {
        $this->hostname = $_ENV['DB_HOSTNAME'] ?? null;
        $this->dbname = $_ENV['DB_DATABASE'] ?? null;
        $this->username = $_ENV['DB_USERNAME'] ?? null;
        $this->password = $_ENV['DB_PASSWORD'] ?? null;
    }
}
class Dbconnection{
    use Initializer;
 
    public function __construct(){
       $this->initializeDbProperties();
        
    }

    public function connect_to_database(){
        try {
           
            $conn= new PDO("mysql:host=$this->hostname;dbname=$this->dbname",$this->username,$this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
             return $conn;
        } catch (PDOException $e) {
            die('error connecting to database and more '.$e->getMessage());
        }
    }

}