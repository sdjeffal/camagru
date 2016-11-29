<?php
class UserController extends Controller{

    public function viewLoginAction()
    {
        if ($this->islog() !== false){
            $controller = new IndexController();
            return($controller->indexAction());
        }
        return ($this->view("login"));
    }

    public function loginAction()
    {
        if ($this->islog()){
            $controller = new IndexController();
            return($controller->indexAction());
        }
        $this->loader->helper("secure");
        $post = secureArray($_POST);
        if (!empty($post["login"]) && !empty($post["passwd"]) && $post["submit"] === "OK"){
            $user = new UserModel();
            if ($user->getUserByUsername($post["login"]) && password_verify($post["passwd"], $user->getPassword())){
                if ($user->isActive() === true){
                    $this->setUserToSession($user);
                    return ($this->view("success", array("message" => "Tu es connecté ;)")));
                }
                else {
                    $message = "Ton compte est inactif, tu n'as pas cliqué sur le lien dans l'email de confirmation ;(<br/>";
                    $message .= "<a href='index.php?controller=user&action=sendEmailConfirm&id=".$user->getId()."'>renvoyer l'email de confirmation</a>";
                    return ($this->view("error", array("message" => $message)));
                }
            }
            else {
                setFlush("errorNotFound", "Ce compte utilisateur n'existe pas ou le mot de passe n'est pas correct.");
            }
        }
        else
            setFlush("errorAll", "Tu n'as pas rempli tous les champs.");
        return ($this->viewLoginAction());
    }

    public function logoutAction()
    {
        unset($_SESSION["loggued_on_user"]);
        unset($_SESSION["user"]);
        $this->view("success", array("message" => "Tu as été déconnecté."));
    }

    public function viewRegistrationAction()
    {
        if ($this->islog() !== false)
            return($this->redirectReferer());
        else
            return($this->view("registration"));
    }

    public function registrationAction()
    {
        if ($this->islog())
            return($this->redirectReferer());
        if (!empty($_POST["login"]) && !empty($_POST["mail"]) && !empty($_POST["passwd"]) && !empty($_POST["passwdbis"])
            && !empty($_POST["submit"]) && $_POST["submit"] === "OK")
        {
            $this->loader->helper("secure");
            $post = secureArray($_POST);
            $this->loader->helper("validation");
            $b1 = validationLogin($post["login"]);
            $b2 = validationEmail($post["mail"]);
            $b3 = validationPassword($post["passwd"], $post["passwdbis"]);
            if ($b1 === true && $b2 === true && $b3 === true)
            {
                $user = new UserModel();
                $user->setUsername($post["login"]);
                $user->setEmail($post["mail"]);
                $b1 = $user->usernameExists($post["login"]);
                $b2 = $user->emailExists($post["mail"]);
                if ($b1 || $b2)
                {
                    if ($b1)
                        setFlush("errorLoginExists", "L'identifiant existe déja.");
                    if ($b2)
                        setFlush("errorEmailExists", "L'adresse E-mail existe déja.");
                }
                else
                {
                    $user->setPassword(password_hash($post["passwd"], PASSWORD_DEFAULT));
                    $user->setKey(password_hash(microtime(true) * 100000, PASSWORD_DEFAULT));
                    $bool = $this->sendMailConfirm($user);
                    if ($bool){
                        $user->insertUser();
                        $message = "ton inscription a été pris compte. Un email t'a été envoyé pour confirmer ton inscription afin d'activer ton compte.";
                        return ($this->view("success", array("message" => $message)));
                    }
                    else {
                        $message =  "ton insciption n'a pas été pris en compte.";
                        $message .= "Une erreur est survenue lors de l'envoie du email de confirmation.<br/>";
                        return ($this->view("error", array("message" => $message)));
                    }
                    $controller = new IndexController();
                    return($controller->indexAction());
                }
            }
        }
        else
            setFlush("errorAll", "Tu n'as pas rempli tous les champs.");
        return ($this->viewRegistrationAction());
    }

    public function activationAction(){
        if (isset($_GET["login"], $_GET["key"])){
            $this->loader->helper("secure");
            $get = secureArray($_GET);
            $user = new UserModel();
            if ($user->getUserByUsername(urldecode($get["login"])) !== false){
                if ($user->getKey() === urldecode($get["key"])){
                    $user->activeUserByid();
                    $message = "Ton compte a bien été activé. Tu peux maintenant t'y connecter.<br/>";
                    return ($this->view("success", array("message" => $message)));
                }
                else {
                    $message = "Il y a eu une erreur interne.";
                    $message .= "<br/>Cliques sur ce lien pour <a href='index.php?controller=user&action=sendEmailConfirm&id=".$user->getId()."'>renvoyer un nouvel email de confirmation</a>.";
                    return ($this->view("error", array("message" => $message)));
                }
            }
            else {
                $message = "Ton compte n'existe pas ou plus.<br/>";
                return ($this->view("error", array("message" => $message)));
            }
        }
    }

    public function viewForgetPasswdAction()
    {
        if ($this->islog() !== false)
            return($this->redirectReferer());
        return ($this->view("forgetPasswd"));
    }

    public function forgetPasswdAction()
    {
        if ($this->islog() !== false)
            return($this->redirectReferer());
        if (isset($_POST["mail"], $_POST["submit"]) && $_POST["submit"] === "OK"){
            $this->loader->helper("secure");
            $post = secureArray($_POST);
            $this->loader->helper("validation");
            if (validationEmail($post["mail"]) !== false){
                $user = new UserModel();
                $user->getUserByEmail($post["mail"]);
                if ($user->isEmpty() !== true){
                    if ($this->sendMailChangePasswd($user)){
                        $message =  "Ta demande de changement de mot de passe a été prise en compte.";
                        $message .= "<br/>Un email t'a été envoyé pour confirmer et finaliser ta demande";
                        return ($this->view("success", array("message" => $message)));
                    }
                    else {
                        $message =  "Ton insciption n'a pas été pris en compte.";
                        $message .= "Une erreur est survenue lors de l'envoie du mail de confirmation. Réessaye plus tard<br/>";
                        return ($this->view("error", array("message" => $message)));
                    }
                }
                else
                    setFlush("errorEmailExists", "Cette adresse E-mail n'existe pas dans notre base de donnée.");
            }
        }
        else
            setFlush("errorAll", "ERREUR: Tu n'as pas rempli tous les champs.");
        return ($this->viewForgetPasswdAction());
    }

    public function viewChangePasswdAction()
    {
        if ($this->islog() !== false)
            return($this->redirectReferer());
        if (isset($_GET["login"], $_GET["key"])){
            $this->loader->helper("secure");
            $get = secureArray($_GET);
            $user = new UserModel();
            $user->getUserByUsername(urldecode($get["login"]));
            if ($user->isEmpty() !== true){
                if ($user->getKey() === urldecode($get["key"]))
                    return ($this->view("changePasswd", array("login" => $get["login"])));
                else
                    return ($this->view("error", array("message" => "Il y a une erreur dans le lien, récupère le lien original et réessayes.")));
            }
            else
                return ($this->view("error", array("message" => "Cette utilisateur n'existe pas ou plus.")));
        }
        else
            return ($this->view("error", array("message" => "Tu ne peux acceder à cette page.")));
    }

    public function changePasswdAction()
    {
        if ($this->islog() !== false)
            return($this->redirectReferer());
        if (!empty($_POST["login"]) && !empty($_POST["submit"]) && $_POST["submit"] === "OK"){
            $this->loader->helper("secure");
            $post = secureArray($_POST);
            $user = new UserModel();
            $user->getUserByUsername($post["login"]);
            if ($user->isEmpty() !== true){
                if (!empty($_POST["passwd"]) && !empty($_POST["passwdbis"])){

                        $this->loader->helper("validation");
                        if (validationPassword($post["passwd"], $post["passwdbis"])){
                            $user->setPassword(password_hash($post["passwd"], PASSWORD_DEFAULT));
                            $user->setKey(password_hash(microtime(true) * 100000, PASSWORD_DEFAULT));
                            $user->updatePasswordByid();
                            $user->updateKeydByid();
                            return ($this->view("success", array("message" => "Ton mot de passe a été modifié.")));
                        }
                }
                else
                    setFlush("errorAll", "ERREUR: Tu n'as pas rempli tous les champs.");
                return ($this->view("changePasswd", array("login" => $post["login"])));
            }
            else
                return ($this->view("error", array("message" => "Cette utilisateur n'existe pas ou plus.")));
        }
        else
            return ($this->view("error", array("message" => "ERREUR: Le formulaire a été modifié frauduleusement.")));

    }

    private function setUserToSession(UserModel $user){
        $_SESSION["loggued_on_user"] = true;
        $_SESSION["user"] = array();
        $_SESSION["user"]["id"] = $user->getId();
        $_SESSION["user"]["username"] = $user->getUsername();
        $_SESSION["user"]["email"] = $user->getEmail();
        $_SESSION["user"]["create_date"] = $user->getCreateTime();
        $_SESSION["user"]["object"] = serialize(clone($user));
    }

    public function sendEmailConfirmAction()
    {
        $this->loader->helper("secure");
        $get = secureArray($_GET);
        if (isset($get["id"]) && (ctype_digit($get["id"]) || is_numeric($get["id"]))){
            $user = new UserModel($get["id"]);
            $bool = $this->sendMailConfirm($user);
            if ($bool){
                $message = "Un email t'a été renvoyé pour confirmer ton inscription afin d'activer ton compte.";
                return ($this->view("success", array("message" => $message)));
            }
            else {
                $message = "Une erreur est survenue lors de l'envoie du email de confirmation.<br/>";
                return ($this->view("error", array("message" => $message)));
            }
        }
    }

    function sendMailConfirm(UserModel $user)
    {
        $this->loader->helper("mail");
        $mail = new Mail();
        $path = rtrim(str_replace($_SERVER["DOCUMENT_ROOT"], '', ROOT), DS);
        $message =  "Bienvenue sur Camagru, ".$user->getUsername()."<br/><br/>";
        $message .= "Pour activer ton compte, cliques sur le lien ci dessous ou copies/colles l'adresse du lien dans ton navigateur internet.<br/><br/>";
        $message .= "<a href='http://".$_SERVER["HTTP_HOST"].$path.DS."index.php?controller=User&action=activation&login=".urlencode($user->getUsername())."&key=".urlencode($user->getKey())."'>lien</a><br/><br/>";
        $message .= "---------------<br/>Ceci est un mail automatique, Merci de ne pas y répondre.";
        $mail->setTo($user->getEmail());
        $mail->setSubject("Confirmation d'inscription");
        $mail->setMessage($message);
        $headers = "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: inscription@camagru.fr";
        $mail->setHeaders($headers);
        $bool = $mail->send();
        return ($bool);
    }

    function sendMailChangePasswd(UserModel $user)
    {
        $this->loader->helper("mail");
        $mail = new Mail();
        $path = rtrim(str_replace($_SERVER["DOCUMENT_ROOT"], '', ROOT), DS);
        $message =  "Salut ".$user->getUsername().",<br/><br/>";
        $message .= "Pour changer ton mot de passe, cliques sur le lien ci dessous ou copies/colles dans ton navigateur internet.<br/>";
        $message .= "<a href='http://".$_SERVER["HTTP_HOST"].$path.DS."index.php?controller=User&action=viewChangePasswd&login=".urlencode($user->getUsername())."&key=".urlencode($user->getKey())."'>lien</a><br/><br/>";
        $message .= "---------------<br/>Ceci est un mail automatique, Merci de ne pas y répondre.";
        $mail->setTo($user->getEmail());
        $mail->setSubject("Changer son mot de passe");
        $mail->setMessage($message);
        $headers = "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: password@camagru.fr";
        $mail->setHeaders($headers);
        $bool = $mail->send();
        return ($bool);
    }
}