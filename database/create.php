<?php
include '../model/ConnectionDB.php';
$connection = new ConnectionDB();
$connection->connect();

echo $connection->execute('CREATE TABLE IF NOT EXISTS animes (id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(100) NOT NULL, description VARCHAR(255), cover_link VARCHAR(255))');

$connection->disconnect();