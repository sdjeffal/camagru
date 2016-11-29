<?php

class Framework {

    public static function bootstrap() {
        self::init();
        self::autoload();
        self::dispatch();
    }

    public static function init(){
		//define path constants
        define("DS", DIRECTORY_SEPARATOR);

        define("ROOT", getcwd() . DS);

        define("APP_PATH", ROOT . 'application' . DS);

        define("FRAMEWORK_PATH", ROOT . "framework" . DS);

        define("PUBLIC_PATH", ROOT . "public" . DS);

        define("CSS_PATH", PUBLIC_PATH . "css" . DS);

        define("CONFIG_PATH", APP_PATH . "config" . DS);

        define("CONTROLLER_PATH", APP_PATH . "controllers" . DS);

        define("MODEL_PATH", APP_PATH . "models" . DS);

        define("VIEW_PATH", APP_PATH . "views" . DS);

        define("TEMPLATE_PATH", VIEW_PATH . "templates" . DS);

        define("CORE_PATH", FRAMEWORK_PATH . "core" . DS);

        define('DB_PATH', FRAMEWORK_PATH . "database" . DS);


        define("UPLOAD_PATH", PUBLIC_PATH . "uploads" . DS);

        define("FRAME_PATH", PUBLIC_PATH . "frames" . DS);

        define("HELPER_PATH", FRAMEWORK_PATH . "helpers" . DS);

        // Define option of configuration php.ini
        define('UPLOAD_MAX', self::return_bytes(ini_get('upload_max_filesize')));

        define('POST_MAX', self::return_bytes(ini_get('post_max_size')));

        // Define controller, action, for example:

        // index.php?controller=Goods&action=add

        define("CONTROLLER", isset($_REQUEST['controller']) ? $_REQUEST['controller'] : 'Index');

        define("ACTION", isset($_REQUEST['action']) ? $_REQUEST['action'] : 'index');

        define("CURR_CONTROLLER_PATH", CONTROLLER_PATH);

        define("CURR_VIEW_PATH", VIEW_PATH);

        define("BASE", rtrim(str_replace($_SERVER["DOCUMENT_ROOT"], '', ROOT), DS).DS);

        // Load core classes

        require CORE_PATH . "Controller.class.php";

        require CORE_PATH . "Loader.class.php";

        require DB_PATH . "Database.class.php";

        require CORE_PATH . "Model.class.php";


        // Load configuration file

        $GLOBALS['config'] = include CONFIG_PATH . "database.php";
        session_start();
    }

	//autoloading class
    public static function autoload(){
    	spl_autoload_register(array(__CLASS__, 'load'));
    }

	//function call by autoload
	private static function load($classname){
		if (substr($classname, -10) == "Controller"){
            if (file_exists(CURR_CONTROLLER_PATH.$classname.".class.php"))
                require_once CURR_CONTROLLER_PATH."$classname.class.php";
		}
		else if (substr($classname, -5) == "Model"){
            if (file_exists(MODEL_PATH."$classname.class.php"))
                require_once MODEL_PATH."$classname.class.php";
		}
	}

	//routing and dispatching
    public static function dispatch(){
		//instantiate the controller class and call its action method
		$controller_name = CONTROLLER."Controller";
		$action_name = ACTION."Action";
        if (class_exists($controller_name) === false){
            $controller = new Controller();
            header("HTTP/1.0 404 Not Found");
            return ($controller->view("404"));
        }
        else
            $controller = new $controller_name;
        if (method_exists($controller, $action_name) === false){
            $controller = new Controller();
            header("HTTP/1.0 404 Not Found");
            return ($controller->view("404"));
        }
        else
            $controller->$action_name();
    }

    public static function return_bytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        switch($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }
}
?>