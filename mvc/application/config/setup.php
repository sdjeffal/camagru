<?php
require_once("./database.php");
try{
	$param= array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
	$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $param);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	if (file_exists($DB_FILE)){
		$file = file_get_contents($DB_FILE);
		$array = explode("\n", $file);
		foreach($array as $line)
		{
			if (!empty($line) && $line[0] !== '-')
				$query .= $line;
		}
		$pdo->prepare($query)->execute();
	}
	else
		echo "The file ".$DB_FILE." not exists\n";
}
catch (PDOException $e){
	$msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
	die($msg);
}
?>
