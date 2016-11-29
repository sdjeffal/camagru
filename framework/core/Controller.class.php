<?php
class Controller{

    protected $loader;

    public function __construct()
    {
        $this->loader = new Loader();
        $this->loader->helper("flush");
    }

    public function redirect($url, $message, $wait = 0){
        if ($wait == 0){
            header("Location:$url");
        }
        else {
            $this->view($message);
        }
    }

    public function redirectReferer(){
        if (isset($_SERVER["HTTP_REFERER"]) && !empty($_SERVER["HTTP_REFERER"]))
            $url = $_SERVER["HTTP_REFERER"];
        else if (isset($_SESSION["HTTP_REFERER"]) && !empty($_SESSION["HTTP_REFERER"]))
            $url = $_SESSION["HTTP_REFERER"];
        if (isset($url) && !empty($url))
            header("Location:$url");
    }

    public function view($pathview, array $vars = null)
    {
        ob_start();
        if (isset($vars))
            extract($vars);
        require CURR_VIEW_PATH . $pathview . ".php";
        $content = ob_get_clean();
        $_SESSION["HTTP_REFERER"] = $_SERVER["REQUEST_URI"];
        require (TEMPLATE_PATH."default.php");
    }

    public function isLog()
    {
        if (isset($_SESSION["loggued_on_user"]) && $_SESSION["loggued_on_user"] === true)
            return (true);
        return (false);
    }
}
