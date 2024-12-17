<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'pollify_db';
    private $username = 'root';
    private $password = '';
    private $conn = null;

    public function connect() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname}",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
            throw new Exception("Database connection failed. Please check your configuration.");
        }
    }
}
?> 