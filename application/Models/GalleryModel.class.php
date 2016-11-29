<?php
class GalleryModel extends Model{
    private $id;
    private $title;
    private $url;
    private $users_id;
    private $user;
    private $create_time;
    private $comments = array();
    private $like = 0;
    private static $prefixMiniature = 'mini-';
    private static $width = 800;
    private static $height = 600;

    public function __construct()
    {
        parent::__construct();
        $this->table = "galleries";
    }

    public function getAllGalleryWithComments(){
        $statement = "SELECT * FROM " . $this->table . " ORDER BY create_time DESC;";
        $attribute = array();
        $galleries = $this->prepare($statement, $attribute, __CLASS__, false);
        if(!empty($galleries)){
            $comment = new CommentModel();
            $like = new LikeModel();
            foreach ($galleries as $key => $gallery){
                $galleries[$key]->comments = $comment->getCommentByGallery($gallery->getId());
                $galleries[$key]->like = $like->getNumberLikesByGallery($gallery->getId());
                $galleries[$key]->user = new UserModel($gallery->getUsersId());
            }
        }
        return ($galleries);
    }

    public function getGalleryWithComments($gallery_id){
        if (isset($gallery_id) && !empty($gallery_id) && (ctype_digit($gallery_id) || is_numeric($gallery_id))){
            $statement = "SELECT * FROM " . $this->table . " WHERE id = ?;";
            $attribute = array($gallery_id);
            $gallery = $this->prepare($statement, $attribute, __CLASS__, true);
            if(!empty($gallery)){
                $comment = new CommentModel();
                $like = new LikeModel();
                $gallery->comments = $comment->getCommentByGallery($gallery->getId());
                $gallery->likes = $like->getNumberLikesByGallery($gallery->getId());
                $gallery->user = new UserModel($gallery->getUsersId());
            }
            return ($gallery);
        }
        return (false);
    }

    public function getNumberGallery(){
        $statement = 'SELECT COUNT(id) as count FROM ' . $this->table . ' ;';
        $rep = $this->prepare($statement, $attribute, __CLASS__, true);
        return (intval($rep->count));
    }

    public function getImageByUserId($users_id){
        if (isset($users_id) && !empty($users_id) && (ctype_digit($users_id) || is_numeric($users_id))){
            $statement = 'SELECT url, id, title FROM ' . $this->table . ' WHERE users_id = ? ORDER BY create_time DESC';
            $attribute = array($users_id);
            $galleries = $this->prepare($statement, $attribute, __CLASS__, false);
            return ($galleries);
        }
        return (false);
    }

    public function galleryExists($gallery_id)
    {
        if (isset($gallery_id) && !empty($gallery_id) && (ctype_digit($gallery_id) || is_numeric($gallery_id))){
            $statement = "SELECT id, users_id FROM " . $this->table . " WHERE id = ?;";
            $attribute = array($gallery_id);
            $gallery = $this->prepare($statement, $attribute, __CLASS__, true);
            if ($gallery !== false)
                return ($gallery);
        }
        return (FALSE);
    }

    public function getLimitImage($offset, $limit){
        settype($offset, "integer");
        settype($limit, "integer");
        $statement = sprintf('SELECT * FROM ' . $this->table . ' ORDER BY create_time DESC LIMIT %d, %d', $offset, $limit);
        $attribute = null;
        $galleries = $this->prepare($statement, $attribute, __CLASS__, false);
        if(!empty($galleries)){
            $comment = new CommentModel();
            $like = new LikeModel();
            foreach ($galleries as $key => $gallery) {
                $galleries[$key]->comments = $comment->getCommentByGallery($gallery->getId());
                $galleries[$key]->like = $like->getNumberLikesByGallery($gallery->getId());
                $galleries[$key]->user = new UserModel($gallery->getUsersId());
            }
        }
        return ($galleries);
    }

    public function getImageById($id, $users_id)
    {
        if (isset($users_id, $id) && !empty($users_id) && !empty($id) && (ctype_digit($users_id) || is_numeric($users_id)) && (ctype_digit($id) || is_numeric($id))){
            $statement = 'SELECT id, url, title FROM ' . $this->table . ' WHERE id = ? AND users_id = ? ;';
            $attribute = array($id, $users_id);
            $gallery = $this->prepare($statement, $attribute, __CLASS__, true);
            return($gallery);
        }
    }

    public function deleteImageById($id, $url, $users_id)
    {
        if (isset($users_id, $id) && !empty($users_id) && !empty($id) && (ctype_digit($users_id) || is_numeric($users_id)) && (ctype_digit($id) || is_numeric($id))){
            $gallery = $this->getImageById($id, $users_id);
            $statement = 'DELETE FROM ' . $this->table . ' WHERE id = ? AND users_id = ? ;';
            $attribute = array($id, $users_id);
            $req = $this->db->getPdo()->prepare($statement);
            $rep = $req->execute($attribute);
            if ($rep && $url){
                //effacer les fichiers sur le disque dur (miniature compris)
                $path = end(explode(DS, $url));
                if($path){
                    $rep = $this->deleteUploadOnDisk($path);
                    return($rep);
                }
            }
        }
    }

    private function resizeAuto(&$x, &$y){
        $ratio = 1.000;
        if ($x > self::$width)
        {
            while($x > self::$width)
                $x *= ($ratio - 0.001);
        }
        elseif ($x < self::$width)
        {
            while($x < self::$width)
                $x *= ($ratio + 0.001);
        }
        $ratio = 1.000;
        if ($y > self::$height)
        {
            while($y > self::$height)
                $y *= ($ratio - 0.001);
        }
        elseif ($y < self::$height)
        {
            while($y < self::$height)
                $y *= ($ratio + 0.001);
        }
        $x = intVal($x);
        $y = intVal($y);
    }

    public function addImageByUserId($users_id = null)
    {
        $statement =  "INSERT INTO ".$this->table." ";
        $statement .= "VALUES (NULL, ?, ?, ?, CURRENT_TIMESTAMP);";
        $attributes = array($this->title, $this->url, $this->users_id);
        $req = $this->db->getPdo()->prepare($statement);
        $rep = $req->execute($attributes);
        return ($rep);
    }

    private function resizePng($img)
    {
        $width = imagesx($img);
        $height = imagesy($img);
        $n_width = $width;
        $n_height = $height;
        $this->resizeAuto($n_width, $n_height);
        $dest = imagecreatetruecolor($n_width, $n_height);
        imageAlphaBlending($dest, false);
        imageSaveAlpha($dest, true);
        imagecopyresized($dest, $img, 0, 0, 0, 0, $n_width, $n_height, $width, $height);
        return ($dest);
    }

    private function resizeImage($img)
    {
        $width = imagesx($img);
        $height = imagesy($img);
        $n_width = $width;
        $n_height = $height;
        $this->resizeAuto($n_width, $n_height);
        $dest = imagecreatetruecolor($n_width, $n_height);
        imagecopyresized($dest, $img, 0, 0, 0, 0, $n_width, $n_height, $width, $height);
        return ($dest);
    }

    private function minimizeImage($img, $ratio)
    {
        list($width, $height) = getimagesize($img);
        $n_width = $width * $ratio;
        $n_height = $height * $ratio;
        $dest = imagecreatetruecolor($n_width, $n_height);
        $src = imagecreatefrompng($img);
        imagecopyresized($dest, $src, 0, 0, 0, 0, $n_width, $n_height, $width, $height);
        imagedestroy($src);
        return ($dest);
    }

    public function doMounting($frame, $img, $format){
        $time = microtime(true) * 10000;
        settype($time, "integer");
        $imgGd = $this->resizeImage(imagecreatefromstring($img));
        $frameGd = $this->resizePng(imagecreatefrompng(FRAME_PATH.$frame.$format));
        $black = imagecolorallocate($frameGd, 0, 0, 0);
        imagecolortransparent($frameGd, $black);
        imagecopy($imgGd, $frameGd, 0, 0, 0, 0, imagesx($imgGd), imagesy($imgGd));
        imagedestroy($frameGd);
        $name = "image-".$_SESSION["user"]["id"]."-".$time;
        if ($this->saveUploadOnDisk($name.$format, $imgGd) === false)
            return (false);
        if ($this->saveUploadOnDb($name, $format) === false){
            $this->deleteUploadOnDisk($name);
            return (false);
        }
        ob_start();
        echo(file_get_contents(UPLOAD_PATH.$name.$format));
        $img = ob_get_contents();
        ob_end_clean();
        imagedestroy($imgGd);
        return ($img);
    }

    private function saveUploadOnDisk($name, $imgGd){
        if (!file_exists(UPLOAD_PATH))
            mkdir(UPLOAD_PATH, 0777, true);
        $rep = imagepng($imgGd, UPLOAD_PATH.$name);
        if ($rep){
            $mini = $this->minimizeImage(UPLOAD_PATH.$name, 0.2);
            $rep = imagepng($mini, UPLOAD_PATH.self::$prefixMiniature.$name);
            imagedestroy($mini);
        }
        return ($rep);
    }

    private function deleteUploadOnDisk($name)
    {
        $bool = false;
        if (file_exists(UPLOAD_PATH.$name) && file_exists(UPLOAD_PATH.self::$prefixMiniature.$name))
        {
            $bool = unlink(UPLOAD_PATH.$name);
            $bool = unlink(UPLOAD_PATH.self::$prefixMiniature.$name);
        }
        return ($bool);
    }

    private function saveUploadOnDb($name, $format){
        $mounting = new GalleryModel();
        $mounting->setTitle($name);
        $mounting->setUrl("public".DS."uploads".DS.$name.$format);
        $mounting->setUsersId($_SESSION["user"]["id"]);
        $rep = $mounting->addImageByUserId();
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
     * Get the value of Title
     *
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of Title
     *
     * @param mixed title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of Url
     *
     * @return mixed
     */
    public function getMiniUrl()
    {
        $res = explode(DS, $this->url);
        $res[count($res) - 1] = self::$prefixMiniature.$res[count($res) - 1];
        return implode(DS, $res);
    }

    /**
     * Get the value of Url
     *
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the value of Url
     *
     * @param mixed url
     *
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

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
     * Get the value of Create Time
     *
     * @return mixed
     */
    public function getCreateTime()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $this->create_time);
        return $date->format('H:i:s d/m/Y');
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
     * Get the value of Comments
     *
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set the value of Comments
     *
     * @param mixed comments
     *
     * @return self
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get the value of Like
     *
     * @return mixed
     */
    public function getLike()
    {
        return $this->like;
    }

    /**
     * Set the value of Like
     *
     * @param mixed like
     *
     * @return self
     */
    public function setLike($like)
    {
        $this->like = $like;
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
}