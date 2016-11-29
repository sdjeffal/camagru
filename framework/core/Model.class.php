<?php

class Model{
    protected $db = false;
    protected $table;

    public function __construct()
    {
        $this->db = new Database(array('config' => CONFIG_PATH . "database.php"));
    }

    public function query($statement, $class)
    {
        try{
            $req = $this->db->getPDO()->query($statement);
            $datas = $req->fetchAll(PDO::FETCH_CLASS, $class);
            return ($datas);
        }
        catch (PDOException $e){
    		die ("ERREUR PDO dans ". $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage());
    	}
    }

    public function getTable(){
        return ($this->table);
    }

    public function prepare($statement, $attributes, $class, $one = false)
    {
        $req = $this->db->getPDO()->prepare($statement);
        $req->setFetchMode(PDO::FETCH_CLASS, $class);
        try{
            if ($req->execute($attributes)){
                if ($one)
                    $data = $req->fetch();
                else
                    $data = $req->fetchAll();
                return ($data);
            }
            else
                return(FALSE);
        }
        catch (PDOException $e){
    		die ("ERREUR PDO dans ". $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage());
    	}
    }
}