<?php  
$host = "localhost";
$user = "root";
$password = "";
$dbname = "autoshop";
$dsn = "mysql:host={$host};dbname={$dbname}";
$pdo = new PDO($dsn, $user, $password);
?>