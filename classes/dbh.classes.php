<?php

class Dbh {
    private $host = "localhost";
    private $user = "root";
    private $pwd = "";
    private $dbName = "mywakili2";
    private $port = 3307; 

    protected function connect() {
        try {
            $dsn = 'mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->dbName;
            $pdo = new PDO($dsn, $this->user, $this->pwd);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exceptions
            return $pdo;
        } catch (PDOException $e) {
            die('Database Connection Failed: ' . $e->getMessage());
        }
    }

    public function getPdo() {
        return $this->connect(); // Return the PDO connection
    }
}

?>

