<?php

class Home
{

    public function index()
    {

        Load::template("header", [
            "title" => "HOME",
            "css" => [
                "all",
                "home"
            ]
        ]);
        Load::view("home");
        Load::template("footer",[
            "js"=>["reusable/jquery.min","reusable/all","reusable/home"]
        ]);
    }
}
