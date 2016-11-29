<?php

class MountingController extends Controller
{
    public function indexAction()
    {
        if ($this->islog())
        {
            $gallery = new GalleryModel();
            $last = $gallery->getImageByUserId($_SESSION["user"]["id"]);
            $array = array('last' => $last);
            return($this->view("mounting", $array));
        }
        setFlush("errorAccessForbidden",
        "Tu dois être connecté à ton compte afin d'accéder à cette partie du site.<br/>
         Si tu n'as pas de compte, tu peux t'inscrire
         <a href='index.php?controller=user&action=viewRegistration'>ici</a>.");
        return ($this->view("login"));
    }

    public function listMountingAction(){
        if ($this->islog())
        {
            $gallery = new GalleryModel();
            $last = $gallery->getImageByUserId($_SESSION["user"]["id"]);
            foreach ($last as $key => $img){
                echo "<div class='miniature'>";
                echo "<a href='index.php?controller=mounting&action=delMounting&id=".$img->getId()."'>&times;</a>";
                echo "<img src='".BASE.$img->getMiniUrl()."'>";
                echo "</div>";
            }
        }
    }

    public function addMountingAction(){
        $format = ".png";
        $this->loader->helper("secure");
        $post = secureArray($_POST);
        if(!isset($post["frame"]) || empty($post["frame"]) || $post["frame"] === "undefined")
            $post["frame"] = 'noel';
        if(!isset($post["image"]) || empty($post["image"]) || isset($post["submit"]))
            return ($this->indexAction());
        if (preg_match("/^data:image\/png;base64,/", $post["image"]) && checkExtension(FRAME_PATH.$post["frame"].$format, "png") === true ){
            $imgBase64 = str_replace("data:image/png;base64,", '', $post["image"]);
            $imgBase64 = str_replace(' ', '+', $imgBase64);
            $img = base64_decode($imgBase64);
            $gallery = new GalleryModel();
            $img = $gallery->doMounting($post["frame"], $img, $format);
            $imgBase64 = "data:image/jpeg;base64, " . base64_encode($img);
            header ("Content-type: image/png");
            echo($imgBase64);
        }
        else {
            return ($this->indexAction());
        }
    }

    public function delMountingAction()
    {
        if ($this->islog() && isset($_GET["id"]))
        {
            $this->loader->helper("secure");
            $get = secureArray($_GET);
            $gallery = new GalleryModel();
            $gallery = $gallery->getImageById($get["id"], $_SESSION["user"]["id"]);
            if ($gallery)
            {
                $rep = $gallery->deleteImageById($gallery->getId(), $gallery->getUrl() ,$_SESSION["user"]["id"]);
                if ($rep && isset($get["type"]) !== true)
                    setFlush("success_status", "l'image a bien été effacé");
                else if(isset($get["type"]) !== true)
                    setFlush("error_status", "une erreur technique s'est produite.");
            }
            else if (isset($get["type"]) !== true)
                setFlush("error_status", "l'image n'existe pas ou plus");
            if (isset($get["type"]) && $get["type"] === "ajax")
                return(true);
            return ($this->indexAction());
        }
        $controller = new IndexController();
        return($controller->indexAction());
    }
    private function secureFile($filename)
    {
        $spl = new SplFileObject();
    }
}
