<?php
namespace App;
/**
* Class Autoloader
*/
class Autoloader{

/**
* Enregister l'autoloader
*/
  static function register(){
    spl_autoload_register(array(__CLASS__, 'autoload'));
  }

  /**
  * Inclue le fichier correspondant à notre class
  * @param $class string Le nom de la classe à charger
  */
  static function autoload($class){
    if (strpos($class, __NAMESPACE__.'/') === 0){
      $class = str_replace(__NAMESPACE__.'/', '', $class);
    }
    require __DIR__.'/'.$class.'.php';
  }
}
?>
