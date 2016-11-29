<?php
class LikeModel extends Model{
    private $id;
    private $users_id;
    private $galleries_id;

    public function __construct()
    {
        parent::__construct();
        $this->table = "likes";
    }

    public function getNumberLikesByGallery($gallery_id)
    {
        if (isset($gallery_id) && !empty($gallery_id) && (ctype_digit($gallery_id) || is_numeric($gallery_id))){
            $statement = "SELECT COUNT(".$this->table.".id) as count FROM ".$this->table." WHERE galleries_id = ?";
            $attribute = array($gallery_id);
            $reponse = $this->prepare($statement, $attribute, __CLASS__, true);
            return(intval($reponse->count));
        }
        else
            return (false);
    }

    public function insertLike($user_id, $gallery_id)
    {
        $statement =  "INSERT INTO ".$this->table." ";
        $statement .= "VALUES (NULL, ?, ?);";
        $attributes = array($user_id, $gallery_id);
        $req = $this->db->getPdo()->prepare($statement);
        $rep = $req->execute($attributes);
        if ($rep !== true)
            $rep = false;
        return ($rep);
    }

    public function deleteLike($user_id, $gallery_id)
    {
        $statement =  "DELETE FROM ".$this->table." ";
        $statement .= "WHERE users_id = ? AND galleries_id = ?";
        $attributes = array($user_id, $gallery_id);
        $req = $this->db->getPdo()->prepare($statement);
        $rep = $req->execute($attributes);
        if ($rep !== true)
            $rep = false;
        return ($rep);
    }
}