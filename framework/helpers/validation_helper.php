<?php

function validationLogin($name)
{
    if (preg_match("/^[a-z0-9]{1,64}$/i", $name))
        return true;
    $_SESSION["errorLogin"] = "Votre identifiant doit contenir entre 1 et 64 caractères alphanumériques.";
    return false;
}

function validationEmail($mail)
{
    $pattern = "/([a-z0-9_]+|[a-z0-9_]+\.[a-z0-9_]+)@(([a-z0-9]|[a-z0-9]+\.[a-z0-9]+)+\.([a-z]{2,4}))/i";
    if (preg_match($pattern, $mail))
        return true;
    $_SESSION["errorEmail"] = "Veuillez entrer une adresse valide.";
    return false;
}

function validationPassword($passwd, $passwdbis)
{
    if ($passwd === $passwdbis)
        $bool = true;
    else
    {
        $_SESSION["errorPasswdBis"] = "Le mot de passe retapé n'est pas le même.";
        $bool = false;
    }
    if (preg_match("/^[a-z0-9]{6,24}$/i", $passwd) && preg_match("/[a-z]+[A-Z]+[0-9]+|[a-z]+[0-9]+[A-Z]+|[A-Z]+[a-z]+[0-9]+|[A-Z]+[0-9]+[a-z]+|[0-9]+[a-z]+[A-Z]+|[0-9]+[A-Z]+[a-z]+/", $passwd))
        $boolMatch = true;
    else
    {
        $_SESSION["errorPasswd"] = "Votre mot de passe doit contenir entre 6 et 24 caractères alphanumériques avec au moins une lettre minuscule, une majuscule et un chiffre.";
        $boolMatch = false;
    }
    if ($bool && $boolMatch)
       return (true);
    return false;
}