<?php
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
    $class = str_replace('Camagru', '', $class);
    $class = str_replace('\\', '/', $class);
    require 'class/'.$class.'.php';
  }
}
?>
