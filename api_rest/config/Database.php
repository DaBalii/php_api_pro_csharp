<?php
class Database{
    private $host = "localhost";
    private $dbname = "api";
    private $username = "root";
    private $password ="";

    public function getConnexion(){
        $conn=null;

        try {
            $conn = new PDO("mysql:host=$this->host;
            dbname=$this->dbname;charset=utf8",
            $this->username,
            $this->password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
        );
        } catch (\PDOException $e) {
            echo"erreur de connexion: $e -> getMessage()";
        }

        return $conn;
    }
}

?>