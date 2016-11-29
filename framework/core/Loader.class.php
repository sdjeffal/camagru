<?php
class Loader{

    public function helper($helper)
    {
        include_once HELPER_PATH . $helper . "_helper.php";
    }
}