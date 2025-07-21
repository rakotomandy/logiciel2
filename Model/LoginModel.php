<?php

class LoginModel
{

    private $loginModel;

    public function __construct()
    {
        $this->loginModel = new Query();
    }

    // check if correct credentials
    public function login($email, $pwd)
    {
        $data = $this->loginModel->custom("SELECT * FROM user WHERE EMAIL=? AND PASSWORD=?", "select")->execute([$email, sha1($pwd)]);
        if ($data) {
            return "match";
        } else {
            return "mismatch";
        }
    }

    // for admin to insert inexistant user or new employee
    public function insertUser($name, $firstname, $gender, $email, $password, $function, $cin, $photo)
    {
        $data = $this->loginModel->custom("SELECT * FROM user WHERE EMAIL=? AND PASSWORD=?", "select")->execute([$email, sha1($password)]);
        if ($data) {
            return "alreadyRegistered";
        } else {
            $this->loginModel->custom("INSERT INTO user (NAME,FIRSTNAME,GENDER,EMAIL,PASSWORD,FUNCTION,CIN,PHOTO) VALUES (?,?,?,?,?,?,?,?)", "insert")->execute([$name, $firstname, $gender, $email, $password, $function, $cin, $photo]);
        }
    }
}
