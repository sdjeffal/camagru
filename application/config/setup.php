<?php
define("DS", DIRECTORY_SEPARATOR);
define("DIR_CURR", __DIR__ . DS);
define("DB_PATH", DIR_CURR ."database.php");
define("UPLOAD_PATH", __DIR__.DS.'../../public/uploads');

function DeleteUploads($dir_path) {
	if (file_exists($dir_path) === true && is_dir($dir_path) === true) {
		if (($dir = scandir($dir_path)) !== false){
			$dir = array_slice($dir, 2);
			if (!empty($dir)){
				foreach ($dir as $key => $value) {
					$file_path = $dir_path.DS.$value;
					if (is_file($file_path) === true) {
						if  (unlink($file_path) === true)
							echo "\033[32msuppression ".$value." reussi.\n\033[0m";
						else
							echo "\033[31mSuppression ".$value." echoue.\033[0m\n";
					}
				}
			}
			else
				echo "\033[32m".$dir_path." est vide.\033[0m\n";
		}
	}
	else
		echo "\033[31m".$dir_path." existe pas ou n'est pas un dossier.\033[0m\n";
}

function executefilesql($path, $dbname = false)
{
	require(DB_PATH);
	try{
		$param = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
		if ($dbname === TRUE)
			$pdo = new PDO($DB_DSN."dbname=".$DB_NAME.";", $DB_USER, $DB_PASSWORD, $param);
		else
			$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $param);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if (file_exists($path)){
			$file = file_get_contents($path);
			$array = explode("\n", $file);
			foreach($array as $line)
			{
				if (!empty($line) && $line[0] !== '-')
					$query .= $line;
			}
			$pdo->prepare($query)->execute();
			echo "\033[32mOperation reussi.\n\033[0m";
		}
		else
			echo "\033[31mThe file ".$path." not exists.\n\033[0m";
	}
	catch (PDOException $e){
		echo "\033[31mERREUR PDO dans ". $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage()."\033[0m\n";
	}
}

function print_menu()
{
	echo "1 - créer ou recreer le schema de la base de données.\n";
	echo "2 - dumper la base de donnees.\n";
	echo "3 - supprimer le répertoire uploads.\n";
	echo "0 - quitter.\n";
	echo "taper le numéro de votre choix:";
}

if (!file_exists(DB_PATH))
{
	echo "\033[31mLe fichier database.php n'existe pas.\033[0m\n";
}
else
{
	require_once(DB_PATH);
	print_menu();
	while ($choice = fgets(STDIN))
	{
		$choice = trim($choice);
		$choice = (ctype_digit($choice)) ? intval($choice) : 3 ;
		if ($choice === 0)
			break;
		else if ($choice > 3 || $choice < 1)
		{
			echo "\033[31mLa commande est invalide.\033[0m\n";
			echo "choisissez entre:\n";
			print_menu();
		}
		else
		{
			if ($choice === 1)
				executefilesql(DIR_CURR. $DB_SCHEMA_FILE, FALSE);
			else if ($choice === 2)
				executefilesql(DIR_CURR. $DB_DUMP_FILE, TRUE);
			else if ($choice === 3)
				DeleteUploads(UPLOAD_PATH);
			print_menu();
		}
	}
}
?>