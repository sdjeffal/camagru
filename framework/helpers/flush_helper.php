<?php

function setFlush($key, $msg)
{
    $_SESSION[$key] = $msg;
}

function putFlush($key)
{
    if (isset($_SESSION[$key]))
    {
        if (strpos($key, "error") === 0){
            $class = 'alert warning';
            $type = 'Erreur';
        }
        else{
            $class = 'alert success';
            $type = 'Success';
        }
        echo "<div class='".$class."'><span class='closebtn' onclick='this.parentElement.style.display=\"none\";''>&times;</span><strong>".$type."</strong> ".$_SESSION[$key]."</div>";
        unset($_SESSION[$key]);
    }
}

function existsFlush($key)
{
    if (isset($_SESSION[$key]) && !empty($_SESSION[$key]))
        return true;
    return false;
}