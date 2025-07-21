<?php

class Query
{

    private static $hostname, $dbname, $user, $password;
    private $query, $reqType, $db;

    public static function connect($hostname, $dbname, $user, $password)
    {
        self::$hostname = $hostname;
        self::$dbname = $dbname;
        self::$user = $user;
        self::$password = $password;
    }

    public  function __construct()
    {
        $this->db = new PDO("mysql:host=" . self::$hostname . ";dbname=" . self::$dbname, self::$user, self::$password);
    }

    public function custom($req, $type)
    {
        $this->reqType = $type;
        $this->query = $req;
        return $this;
    }

    public function execute($value = [])
    {
        if (is_array($value) && !empty($value)) {
            if ($this->reqType != "select") {
                $req = $this->db->prepare($this->query);
                $req->execute($value);
            } else {
                $req = $this->db->prepare($this->query);
                $req->execute($value);
                return $req->fetchAll(PDO::FETCH_OBJ);
            }
        } else {
            if ($this->reqType == "select") {
                $req = $this->db->prepare($this->query);
                $req->execute();
                return $req->fetchAll(PDO::FETCH_OBJ);
            }
        }
    }

    public function getQuery()
    {
        return $this->query;
    }
}
Query::connect("localhost", "logiciel", "root", "");
// $db = new Query();
// $db->requete("insert", "user")->columns(["PSEUDO", "EMAIL", "PASSWORD", "PHOTO"])->execute(["ERIC", "eric@gmail.com", "1234", "sfqssfqqq112sfedd"]);
// $db->custom("SELECT * FROM user ","select")->where(["EMAIL","PASSWORD"],"=", "AND","WHERE");
// $db->requete("update","user")->where(["EMAIL","PASSWORD"],"=","SET",",")->where(["ID"],"=","WHERE", "AND");
// $db->requete("delete", "user")->updateSelectDelete(["PSEUDO"], "=","WHERE")->execute(["JEAN"]);
// $data = $db->requete("select", "user")->execute();
// var_dump($data);
// echo $db->getQuery();
