<?php
class CommentModel extends Model{
    private $id;
    private $message;
    private $create_time;
    private $users_id;
    private $galleries_id;
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->table = "comments";
    }

    public function getCommentByGallery($gallery_id)
    {
        if (isset($gallery_id) && !empty($gallery_id) && (ctype_digit($gallery_id) || is_numeric($gallery_id))){
            $statement = "SELECT ".$this->table.".id, ".$this->table.".message, ".$this->table.".create_time FROM " . $this->table . " WHERE galleries_id = ? ;";
            $attribute = array($gallery_id);
            $comments = $this->prepare($statement, $attribute, __CLASS__, false);
            if (!empty($comments)){
                $statement = "SELECT users.id, users.username FROM " . $this->table . " INNER JOIN users ON ".$this->table.".users_id = users_id WHERE ".$this->table.".id = ? ;";
                foreach ($comments as $key => $comment) {
                    $attribute = array($comment->getId());
                    $comments[$key]->user = $this->prepare($statement, $attribute, "UserModel", true);
                }
            }
            return ($comments);
        }
        return (false);
    }

    public function insertComment($message, $user_id ,$gallery_id)
    {
        $statement =  "INSERT INTO ".$this->table." ";
        $statement .= "VALUES (NULL, ?, CURRENT_TIMESTAMP, ?, ?);";
        $attributes = array($message, $user_id, $gallery_id);
        $req = $this->db->getPdo()->prepare($statement);
        $rep = $req->execute($attributes);
        return ($rep);
    }

    /**
     * Get the value of Id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of Id
     *
     * @param mixed id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of Message
     *
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of Message
     *
     * @param mixed message
     *
     * @return self
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the value of Create Time
     *
     * @return mixed
     */
    public function getCreateTime()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $this->create_time);
        return $date->format('d F Y à H:i:s');
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
     * Get the value of Users Id
     *
     * @return mixed
     */
    public function getUsersId()
    {
        return $this->users_id;
    }

    /**
     * Set the value of Users Id
     *
     * @param mixed users_id
     *
     * @return self
     */
    public function setUsersId($users_id)
    {
        $this->users_id = $users_id;

        return $this;
    }

    /**
     * Get the value of Galleries Id
     *
     * @return mixed
     */
    public function getGalleriesId()
    {
        return $this->galleries_id;
    }

    /**
     * Set the value of Galleries Id
     *
     * @param mixed galleries_id
     *
     * @return self
     */
    public function setGalleriesId($galleries_id)
    {
        $this->galleries_id = $galleries_id;

        return $this;
    }

    /**
     * Get the value of User
     *
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of User
     *
     * @param mixed user
     *
     * @return self
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

}


?>