<?php
require_once "Core/autoload.php";

if(isset($_GET["action"])){
    Root::connect($_GET["action"]);
}else{
    echo "<h1> NOT FOUND</h1>";
}