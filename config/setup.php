<?php
require_once("./database.php");
$file = file_get_contents("./schemaCamagru.sql");
$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
$pdo->prepare("SELECT * FROM USERS");
$pdo->exec();
print_r($pdo);
print_r($file);
?>
