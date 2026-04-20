<?php
$host = "localhost";
$database = "organisation";
$username = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $pass);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur :".$e->getMessage();
}
?>