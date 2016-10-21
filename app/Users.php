<?php
namespace App;
/**
* Class Users
*/
class Users{
  private $login;
  private $email;
  private $type;

/**
* Constructeur de la classe Users
*/
  public function __construct(array $kwargs){
    $this->login = "";
    $this->email = "";
    $this->type = "";
  }
}
?>
