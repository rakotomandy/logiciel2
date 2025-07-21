<?php

class Load{

    public static function view($view,$data=[]){
        if(is_array($data) && !empty($data)){
            foreach($data as $key=>$value){
                $$key=$value;
            }
        }
        require_once "View/$view.php";
    }
    public static function template($view,$data=[]){
        if(is_array($data) && !empty($data)){
            foreach($data as $key=>$value){
                $$key=$value;
            }
        }
        require_once "View/template/$view.php";
    }

    public static function css($css){
        if(is_array($css) && !empty($css)){
            for($i=0;$i<count($css);$i++){
                echo "<link rel='stylesheet' href='".URL."Public/css/$css[$i].css'>";
            }
        }
    }
    public static function js($js){
        if(is_array($js) && !empty($js)){
            for($i=0;$i<count($js);$i++){
                echo "<script src='".URL."Public/js/$js[$i].js'></script>";
            }
        }
    }
}