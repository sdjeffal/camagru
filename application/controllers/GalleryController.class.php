<?php
/**
 *
 */
class GalleryController extends Controller{

    public function viewGalleryAction()
    {
        if (isset($_GET["image_id"]) && !empty($_GET["image_id"])){
            $this->loader->helper("secure");
            $get = secureArray($_GET);
            $galleries = new GalleryModel();
            $gallery = $galleries->getGalleryWithComments($get["image_id"]);
            if (!empty($gallery) && $gallery !== false)
                return ($this->view("gallery", array("gallery" => $gallery)));
            else
                setFlush("error_empty_gallery", "L'image n'existe pas ou plus ;(");
        }
        return ($this->redirectReferer());
    }

    public function addCommentAction()
    {
        if ($this->isLog()){
            $bool = true;
            $this->loader->helper("secure");
            $post = secureArray($_POST);
            $post["message"] = nl2br(trim($post["message"]));
            if (empty($post["message"])){
                setFlush("error_empty_comment", "Ton commentaire est vide, tu ne sers à rien ;)");
                $bool = false;
            }
            $gallery = new GalleryModel();
            if (empty($post["gallery_id"]) || ($gallery = $gallery->galleryExists($post["gallery_id"])) === FALSE){
                setFlush("error_empty_gallery", "L'image n'existe pas ou plus ;(");
                $bool = false;
            }
            if ($bool === true){
                $comment = new CommentModel();
                $rep = $comment->insertComment($post["message"], $_SESSION["user"]["id"], $post["gallery_id"]);
                if ($rep){
                    if ($_SESSION["user"]["id"] !== $gallery->getUsersId()){
                        $this->loader->helper("mail");
                        $user = new UserModel($gallery->getUsersId());
                        $this->sendMailComment($user, $_SESSION["user"]["username"], $post["message"]);
                    }
                    setFlush("success_add_comment", "Ton commentaire a bien été ajouté :)");
                }
                else
                    setFlush("error_add_comment", "Une erreur s'est produit, réessaye plus tard :(");
            }
        }
        else{
            setFlush("error_not_login", "Tu dois te <a href='index.php?controller=user&action=viewLogin'>connecter</a> à ton compte ou <a href='index.php?controller=user&action=viewRegistration'>t'inscrire</a> si tu n'en as pas encore");
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

    function sendMailComment(UserModel $userDest, $usernameSrc, $comment)
    {
        $this->loader->helper("mail");
        $mail = new Mail();
        $path = rtrim(str_replace($_SERVER["DOCUMENT_ROOT"], '', ROOT), DIRECTORY_SEPARATOR);
        $message =  "Hello ".$userDest->getUsername()."\r\n\r\n";
        $message .= "Tu as reçu un commentaire de la part de ".$usernameSrc.":\r\n";
        $message .= $comment."\r\n";
        $message .= "---------------\r\nCeci est un mail automatique, Merci de ne pas y répondre.";
        $mail->setTo($userDest->getEmail());
        $mail->setSubject("Vous avez reçu un commentaire.");
        $mail->setMessage($message);
        $headers = "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "From: staff@camagru.fr";
        $mail->setHeaders($headers);
        $bool = $mail->send();
        return ($bool);
    }
}
