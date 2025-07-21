<?php

class User
{

    public function index()
    {

        Load::template("header", [
            "title" => "HOME",
            "css" => [
                "sweetalert.min",
                "all",
                "admin"
            ]
        ]);
        Load::template("sideAdmin");
        Load::view("user");
        Load::template("footer",[
            "js"=>["reusable/jquery.min","reusable/sweetalert2.all.min","reusable/all","reusable/insert","reusable/selectimg","user"]
        ]);
    }

    public function insert() {
     json_encode([
        "status"=>"success"
     ]);
}
}
