<?php
namespace App;
use \PDO;

class Database{
	private $db_name;
	private $db_user;
	private $db_pass;
	private $db_host;
	private $pdo;

	public function __construct(array $kargs)
	{
		if (array_key_exists("config", $kargs) && !empty($kargs["config"]) &&
		file_exists($kargs["config"]))
		{
			include_once($kargs["config"]);
			$this->db_name = $DB_NAME;
			$this->db_user = $DB_USER;
			$this->db_pass = $DB_PASSWORD;
			$this->db_host = $DB_DRIVER.$DB_HOST.$DB_PORT.";";
		}
		else if (!empty(kargs))
		{
			if (array_key_exists("db_name", $kargs) && !empty($kargs["db_name"]))
				$this->db_name = $kargs["db_name"];
			if (array_key_exists("db_user", $kargs) && !empty($kargs["db_user"]))
				$this->db_user = $kargs["db_user"];
			if (array_key_exists("db_pass", $kargs) && !empty($kargs["db_pass"]))
				$this->db_pass = $kargs["db_pass"];
			if (array_key_exists("db_host", $kargs) && !empty($kargs["db_host"]))
				$this->db_host = $kargs["db_host"];
		}
		else
		{
				$this->db_user = 'root';
				$this->db_pass = 'root';
				$this->db_host = 'localhost';
		}
	}

	private function getPDO(){
		if ($this->pdo === NULL)
		{
			$param = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
			$pdo = new PDO($this->db_host.$this->db_name, $this->db_user, $this->db_pass, $param);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->pdo = $pdo;
		}
		return ($this->pdo);
	}

	public function query()
	{
		$req = $this->pdo->getPDO()->query($statement);
		$datas = $req->fetchAll(PDO::FETCH_OBJ);
		return ($datas);
	}
}
?>
