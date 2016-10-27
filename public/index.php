<?php
//chargement dynamique des classes
require '../app/Autoloader.php';
App\Autoloader::register();

if (isset($_GET['page'])){
  $page = $_GET['page'];
}
else {
  $page = 'home';
}
$db = new App\Database(array('config' => $_SERVER['DOCUMENT_ROOT'].'/config/database.php'));
$data = $db->prepare('SELECT * FROM users', array(), "App\\Users", TRUE);
var_dump($data);
ob_start();
if ($page === 'home')
{
  $title = ucfirst(strtolower($page));
  require $_SERVER["DOCUMENT_ROOT"].'/pages/home.php';
}
$content = ob_get_clean();
require $_SERVER["DOCUMENT_ROOT"].'/pages/templates/default.php';
?>
