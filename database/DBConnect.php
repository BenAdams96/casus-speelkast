<?php

class DBConnect {
    static $db; //static betekent dat variabele bij de class zelf hoort, niet het object.
    private $dbh; //hier word het PDO object in opgeslagen
    private function __construct() {
        try { //try to get PDO connection
            $this->dbh = new PDO(
                "mysql:host=localhost;dbname=speelkast",
                "root",
                ""
            );

        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }
    public static function getInstance() { //makes it kind of a singleton
        if (!isset(DBConnect::$db)) { //if not existing, create it.
            DBConnect::$db = new DBConnect(); //:: om iets direct via de class aan te spreken, ipv via een instantie
        }

        return DBConnect::$db->dbh;
    }
}

?>