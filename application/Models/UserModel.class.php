<?php
class UserModel extends Model{

    private $id;
    private $username;
    private $email;
    private $password;
    private $key;
    private $create_time;
    private $is_active;

    public function __construct($id = NULL){
        parent::__construct();
        $this->table = "users";
        if (isset($id) && !empty($id) && (ctype_digit($id) || is_numeric($id))){
            $user = $this->getUserById($id);
            if($user !== false)
                $this->copyUser($user);
            else {
                return (false);
            }
            return($this);
        }
    }

    public function __sleep()
    {
        $ar = array();
        $ar["id"] = $this->getId();
        $ar["username"] = $this->getUsername();
        $ar["email"] = $this->getEmail();
        $ar["create_date"] = $this->getCreateTime();
        return($ar);
    }
    public function copyUser(UserModel $user){
            $this->id = $user->id;
            $this->username = $user->username;
            $this->email = $user->email;
            $this->password = $user->password;
            $this->key = $user->key;
            $this->create_time = $user->create_time;
            $this->is_active = $user->is_active;
    }

    public function isEmpty(){
        if ($this->id === null &&
            $this->username === null &&
            $this->email === null &&
            $this->password === null &&
            $this->key === null &&
            $this->create_time === null &&
            $this->is_active === null)
            return (true);
        else
            return (false);
    }

    public function getUserById($id){
        if (!empty($id) && (ctype_digit($id) || is_numeric($id))){
            $statement = "SELECT * FROM " . $this->table . " WHERE id = ? ";
            $attribute = array($id);
            $user = $this->prepare($statement, $attribute, __CLASS__, true);
            return ($user);
        }
        else
            return false;
    }

    public function insertUser(){
        $statement =  "INSERT INTO ".$this->table." ";
        $statement .= "VALUES (NULL, ?, ?, ?, ?, CURRENT_TIMESTAMP, 0);";
        $attributes = array($this->username, $this->email, $this->password, $this->key);
        $req = $this->db->getPdo()->prepare($statement);
        $rep = $req->execute($attributes);
        return ($rep);
    }

    public function getUserByUsername($username){
        $statement = "SELECT * FROM " . $this->table . " WHERE username = ? ";
        $attribute = array($username);
        $user = $this->prepare($statement, $attribute, __CLASS__, true);
        if ($user)
            $this->copyUser($user);
        else
            $user = false;
        return ($user);
    }

    public function getUserByEmail($email){
        $statement = "SELECT * FROM " . $this->table . " WHERE email = ? ";
        $attribute = array($email);
        $user = $this->prepare($statement, $attribute, __CLASS__, true);
        if ($user)
            $this->copyUser($user);
        else
            $user = false;
        return ($user);
    }

    public function emailExists($email){
        $user = $this->getUserByEmail($email);
        if($user !== false)
            return (true);
        else
            return (false);
    }

    public function usernameExists($username){
        $user = $this->getUserByUsername($username);
        if($user !== false)
            return (true);
        else
            return (false);
    }

    public function activeUserByid($id = null){
        if (!empty($id) && (ctype_digit($id) || is_numeric($id)))
            $attributes = array('1', $id);
        else if (!empty($this->id))
            $attributes = array('1', $this->id);
        else {
            die("ERROR: le parametre d'entrée ou l'attribut id de l'instance de l'objet UserModel appelant la méthode activeUserByid(id = null) ne contient pas de valeur.");
        }
        $statement =  "UPDATE ".$this->table." ";
        $statement .= "SET is_active = ? WHERE id = ?;";
        $req = $this->db->getPdo()->prepare($statement);
        $req->execute($attributes);
    }

    public function inactiveUserByid($id = null){
        if (!empty($id) && (ctype_digit($id) || is_numeric($id))){
            $statement =  "UPDATE ".$this->table." ";
            $statement .= "SET is_active = ? WHERE id = ?;";
            $attributes = array('0', $id);
            $req = $this->db->getPdo()->prepare($statement);
            $req->execute($attributes);
        }
    }

    public function updateKeydByid($key = null){
        if (!empty($this->id) && ctype_digit($this->id) || is_numeric($this->id)){
            if (!empty($key) && is_string($key))
                $attributes = array($key, $this->id);
            else if (!empty($this->key))
                $attributes = array($this->key, $this->id);
            else
                die("ERROR: Le parametre d'entrée ou l'attribut Keyord de l'instance de l'objet UserModel appelant la méthode updateKeydByid(key = null) ne contient pas de valeur.");
        }
        else
            die("ERROR: L'attribut id de l'instance de l'objet UserModel appelant la méthode updateKeydByid(key = null) ne contient pas de valeur.");
        $statement =  "UPDATE ".$this->table." ";
        $statement .= "SET users.key=? WHERE users.id=? ;";
        $req = $this->db->getPdo()->prepare($statement);
        $req->execute($attributes);
    }

    public function updatePasswordByid($passwd = null){
        if (!empty($this->id) && ctype_digit($this->id) || is_numeric($this->id)){
            if (!empty($passwd) && is_string($passwd))
                $attributes = array($passwd, $this->id);
            else if (!empty($this->password))
                $attributes = array($this->password, $this->id);
            else
                die("ERROR: Le parametre d'entrée ou l'attribut Password de l'instance de l'objet UserModel appelant la méthode updatePasswordByid(passwd = null) ne contient pas de valeur.");
        }
        else
            die("ERROR: L'attribut id de l'instance de l'objet UserModel appelant la méthode updatePasswordByid(passwd = null) ne contient pas de valeur.");
        $statement =  "UPDATE ".$this->table." ";
        $statement .= "SET password = ? WHERE id = ?;";
        $req = $this->db->getPdo()->prepare($statement);
        $req->execute($attributes);
    }

    /**
     * Get the value of Id
     *
     * @return Id
     */
    public function getId(){
        return $this->id;
    }

    /**
     * Get the value of Username
     *
     * @return Username
     */
    public function getUsername(){
        return $this->username;
    }

    /**
     * Get the value of Email
     *
     * @return Email
     */
    public function getEmail(){
        return $this->email;
    }

    /**
     * Get the value of Password
     *
     * @return Password
     */
    public function getPassword(){
        return $this->password;
    }

    /**
     * Get the value of Key
     *
     * @return Key
     */
    public function getKey(){
        return $this->key;
    }

    /**
     * Get the value of Create_time (create_date)
     *
     * @return Create_time
     */
    public function getCreateTime(){
        return $this->create_time;
    }

    /**
     * Get the value of Is_active
     *
     * @return boolean true or false
     */
    public function isActive(){
        return ($this->is_active === '1' ? true : false);
    }

    /**
     * Set the value of Id
     *
     * @param mixed id
     *
     * @return self
     */
    public function setId($id){
        $this->id = $id;
        return $this;
    }

    /**
     * Set the value of Username
     *
     * @param mixed username
     *
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set the value of Email
     *
     * @param mixed email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set the value of Password
     *
     * @param mixed password
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set the value of Key
     *
     * @param mixed key
     *
     * @return self
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Set the value of Create Time
     *
     * @param mixed create_time
     *
     * @return self
     */
    public function setCreateTime($create_time)
    {
        $this->create_time = $create_time;

        return $this;
    }

    /**
     * Set the value of Is Active
     *
     * @param mixed is_active
     *
     * @return self
     */
    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;

        return $this;
    }
}

