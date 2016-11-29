<?php
class IndexController extends Controller{

    public function indexAction(){
        $perSet = 8;
        $this->loader->helper("pagination");
        $gallery = new GalleryModel();
        if (isset($_GET["page"]) && !empty($_GET["page"]))
        {
            $this->loader->helper("secure");
            $get = secureArray($_GET);
            $pagination = new Pagination($get["page"], $perSet, $gallery->getNumberGallery());
        }
        else
            $pagination = new Pagination(1, $perSet, $gallery->getNumberGallery());
        $galleries = $gallery->getLimitImage($pagination->getOffset(), $pagination->getCount());
        return ($this->view("home", array('galleries' => $galleries, 'pagination' => $pagination)));
    }

    public function addLikeAction()
    {
        if ($this->isLog()){
            $this->loader->helper("secure");
            $get = secureArray($_GET);
            if ($this->isLike($get["image_id"]) === false){
                $like = new LikeModel();
                $like->insertLike($_SESSION["user"]["id"], $get["image_id"]);
            }
        }
        else{
            setFlush("error_not_login", "Tu dois te <a href='index.php?controller=user&action=viewLogin'>connecter</a> à ton compte ou <a href='index.php?controller=user&action=viewRegistration'>t'inscrire</a> si tu n'en as pas encore");
        }
        return ($this->redirectReferer());
    }

    public function delLikeAction()
    {
        if ($this->isLog()){
            $this->loader->helper("secure");
            $get = secureArray($_GET);
            if ($this->isLike($get["image_id"]) == true){
                $like = new LikeModel();
                $like->deleteLike($_SESSION["user"]["id"], $get["image_id"]);
            }
        }
        else{
            setFlush("error_not_login", "Tu dois te connecter à ton compte ou <a href='index.php?controller=user&action=viewRegistration'>t'inscrire</a> si tu n'en as pas encore");
        }
        return ($this->redirectReferer());
    }

    public function isLike($gallery_id)
    {
        if ($this->isLog())
        {
            $like = new LikeModel();
            $statement = "SELECT * FROM ".$like->getTable()." WHERE users_id = ? AND galleries_id = ?";
            $attributes = array($_SESSION["user"]["id"], $gallery_id);
            $rep = $like->prepare($statement, $attributes, get_class($like), true);
            return ($rep);
        }
    }
}