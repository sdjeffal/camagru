<?php
class Database{
	private $db_name;
	private $db_user;
	private $db_pass;
	private $db_host;
	private $db_driver;
	private $db_port;
	private $db_dsn;
	private $pdo;

	public function __construct(array $kargs)
	{
		if (array_key_exists("config", $kargs) && !empty($kargs["config"]) &&
		file_exists($kargs["config"]))
		{
			include($kargs["config"]);
			$this->setDbname($DB_NAME);
			$this->db_user = $DB_USER;
			$this->db_pass = $DB_PASSWORD;
			$this->db_driver = $DB_DRIVER;
			$this->setDbhost($DB_HOST);
			$this->db_port = $DB_PORT;
			$this->db_dsn = $DB_DSN;
		}
		else if (!empty($kargs))
		{
			if (array_key_exists("db_name", $kargs) && !empty($kargs["db_name"]))
				$this->setDbname($kargs["db_name"]);
			if (array_key_exists("db_user", $kargs) && !empty($kargs["db_user"]))
				$this->db_user = $kargs["db_user"];
			if (array_key_exists("db_pass", $kargs) && !empty($kargs["db_pass"]))
				$this->db_pass = $kargs["db_pass"];
			if (array_key_exists("db_host", $kargs) && !empty($kargs["db_host"]))
				$this->setDbhost($kargs["db_host"]);
			if (array_key_exists("db_driver", $kargs) && !empty($kargs["db_driver"]))
				$this->db_driver = $kargs["db_driver"];
			if (array_key_exists("db_port", $kargs) && !empty($kargs["db_port"]))
				$this->db_driver = $kargs["db_port"];
		}
		else
		{
			$this->db_user = 'root';
			$this->db_pass = 'root';
			$this->setDbhost('localhost');
			$this->db_port = '8080';
			$this->db_driver = 'mysql';
			$this->db_dsn = $this->db_driver.':'.$this->db_host.':'.$this->db_port.';';
		}
	}

	public function getPDO(){
		if ($this->pdo === NULL)
		{
			try{
				$param = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
				$pdo = new PDO($this->db_dsn.$this->db_name, $this->db_user, $this->db_pass, $param);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			catch(PDOException $e){
				if ($e->getCode() === 1049)
					die ("Connection failed: Unknown database 'camagru'");
				else
					die ("Connection failed: " . $e->getMessage());
			}
			$this->pdo = $pdo;
		}
		return ($this->pdo);
	}

	protected function setDbname($dbname)
	{
		if (!empty($dbname) && settype($dbname, "string"))
			$this->db_name = 'dbname='.$dbname;
	}

	protected function setDbhost($dbhost)
	{
		if (!empty($dbhost) && settype($dbhost, "string"))
			$this->db_host = 'host='.$dbhost;
	}
}
?>
