<?php

class LoginModel
{

    private $loginModel;

    public function __construct()
    {
        $this->loginModel = new Query();
    }

    public function login($email, $pwd)
    {
        $data = $this->loginModel->custom("SELECT * FROM user WHERE EMAIL=?", "select")->execute([$email]);
        if ($data && password_verify($pwd, $data[0]['PASSWORD'])) {
            return "match";
        } else {
            return "mismatch";
        }
    }

    public function insertUser($name, $firstname, $gender, $email, $password, $function, $cin, $photo)
    {
        $data = $this->loginModel->custom("SELECT * FROM user WHERE EMAIL=?", "select")->execute([$email]);
        if ($data) {
            return "alreadyRegistered";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $this->loginModel->custom(
                "INSERT INTO user (NAME,FIRSTNAME,GENDER,EMAIL,PASSWORD,FUNCTION,CIN,PHOTO) VALUES (?,?,?,?,?,?,?,?)",
                "insert"
            )->execute([$name, $firstname, $gender, $email, $hashedPassword, $function, $cin, $photo]);
            return "inserted";
        }
    }
}
