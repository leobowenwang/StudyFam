<?php
class DB {

    private $host = "localhost";
    private $db_name = "studyfam";
    // ** CHANGE USERNAME & PASSWORD FOR BETTER SECURITY **
    private $username = "root";
    private $password = "";
    public $conn;

    // Establishing the connection to DB (getting new PDO object)
    public function getConnection() {

        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8", $this->username, $this->password);
        } catch (PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }

        return $this->conn;
    }
    // prepare SQL query
    public function query($query) {
        $newCon = $this->getConnection();
        return $newCon->prepare($query);
    }
}