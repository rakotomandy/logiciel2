<?php

class Database
{

    private $conn;

    public function __construct($servername, $username, $password,$db)
    {
        $this->conn = new PDO("mysql:host=$servername", $username, $password);
        $sql="CREATE DATABASE IF NOT EXISTS $db";
        $this->conn->exec($sql);
        $this->conn->exec("USE $db");
        echo "db successfully created";
    }

    public function createTable($tableName,$columns,$chars){
        if(is_array($columns) && is_array($chars)){
            $create=" CREATE TABLE $tableName";
            $table="";
            $tab=array_combine($columns,$chars);
            foreach($tab as $key=>$value){
                $table.=" $key $value,";
            }
            $table=(trim($table,","));
            $create.="($table)";
            $this->conn->exec($create);
            echo "table created successfully";
            echo $create;
        }
    }

    public function alterTable($table,$column,$sub){
        $alter="ALTER TABLE $table $column $sub";
        $this->conn->exec($alter);
        echo "table altered successfully";
    }

    public function addDropColumn($table,$action,$column,$colDef="",$position="",$existing=""){
        $add="ALTER TABLE $table $action COLUMN $column $colDef $position $existing";
        $this->conn->exec($add);
        strtolower($action);
        echo "column {$action}ed successfully";
    }

    public function custom($table){
        $req="DROP TABLE $table";
        $this->conn->exec($req);
        echo "table deleted successfully";
    }
}

$db=new Database("localhost","root","","logiciel");
/* $db->createTable("user_skype",["ID","PSEUDO","EMAIL","PASSWORD","PHOTO"],["INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ","VARCHAR(50) NOT NULL","VARCHAR(50) NOT NULL","VARCHAR(50) NOT NULL","VARCHAR(50) NOT NULL"]); */
$db->createTable("user",["ID","NAME","FIRSTNAME","GENDER","FUNCTION","CIN","PHOTO"],["INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY","VARCHAR(250) NOT NULL","VARCHAR(250) NOT NULL","VARCHAR(100) NOT NULL","VARCHAR(100) NOT NULL","INT(100) UNSIGNED NOT NULL","VARCHAR(100) NOT NULL"]);
// $db->custom("message");
// $db->addDropColumn("user","ADD","PASSWORD","VARCHAR(250) NOT NULL", "AFTER","EMAIL");
