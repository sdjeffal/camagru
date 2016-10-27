``<?php
//chargement dynamique des classes
require '../app/Autoloader.php';
App\Autoloader::register();

if (isset($_GET['page'])){
  $page = $_GET['page'];
}
else {
  $page = 'home';
}
echo $_SERVER["DOCUMENT_ROOT"];
$db = new Database(array('config' => '../config/database.php'));
ob_start();
if ($page === 'home')
{
  $title = ucfirst(strtolower($page));
  require '../pages/home.php';
}
$content = ob_get_clean();
require '../pages/templates/default.php';
?>
