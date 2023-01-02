<?php
class Connection{
    // public $servername = "localhost";
    public $dbname = "mysql:host=localhost;dbname=assan";
    public $user = 'root';
    public $password = '';
    public $db_name = 'assan';
    public $conn;
    public $phone;


    public function __construct(){
        $this->conn = new PDO($this->dbname,$this->user,$this->password);
        // $this->conn = mysqli_connect($this->servername,$this->user,$this->password,$this->db_name);
    }

    public function get()
    {

        return $this->conn;
    }

}