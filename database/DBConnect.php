<?php

class DBConnect
{
    private static ?DBConnect $db = null;
    private PDO $dbh;

    private function __construct()
    {
        //databasegegevens uit .env lezen
        $env = parse_ini_file(__DIR__ . "/../.env");

        if ($env === false) {
            die(".env niet gevonden");
        }

        try {
            $this->dbh = new PDO(
                "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']}",
                $env["DB_USER"],
                $env["DB_PASSWORD"]
            );

        } catch (PDOException $error) {
            die("Databaseverbinding mislukt: " . $error->getMessage());
        }
    }

    public static function getInstance(): PDO
    {
        if (self::$db === null) {
            self::$db = new DBConnect();
        }

        return self::$db->dbh;
    }
}