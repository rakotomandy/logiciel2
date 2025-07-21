<?php
session_start();
define("URL","http://localhost/logiciel/");

function autoload($file){
if(file_exists("Controller/$file.php")){
    require_once "Controller/$file.php";
}elseif(file_exists("Model/$file.php")){
    require_once "Model/$file.php";
}elseif(file_exists("Core/$file.php")){
    require_once "Core/$file.php";
}
}

spl_autoload_register("autoload");