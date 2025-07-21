<?php

class Login
{

    public function index()
    {

        Load::template("header", [
            "title" => "login",
            "css" => [
                "all",
                "login"
            ]
        ]);
        Load::view("login");
        Load::template("footer",[
            "js"=>["reusable/jquery.min","reusable/sweetalert2.all.min","reusable/all","login"]
        ]);
    }
}
