<?php
class ConnectionDB {
    private $conn;

    public static function connect($server="localhost", $database="animelist", $user="user", $pass="") {
        try {
            $conn = new PDO("mysql:host=".$server.";dbname=".$database, $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;
        }
        catch(PDOException $e) {
            echo $e->getMessage();

            return false;
        }
    }
}