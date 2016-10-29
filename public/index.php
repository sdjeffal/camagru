<?php
//chargement dynamique des classes
require '../app/Autoloader.php';
App\Autoloader::register();

if (isset($_GET['view'])){
  settype($_GET['view'], 'string');
  $view = $_GET['view'];
}
else {
  $view = 'home';
}
$db = new App\Database(array('config' => $_SERVER['DOCUMENT_ROOT'].'/config/database.php'));
ob_start();
if (file_exists('../view/'.$view.'.php'))
{
  $title = ucfirst(strtolower($view));
  require('../view/'.$view.'.php');
}
else
{
  header("HTTP/1.0 404 Not Found");
  $title = "404 Not Found";
  require('../view/404.php');
}
$content = ob_get_clean();
require('../view/templates/default.php');
?>
