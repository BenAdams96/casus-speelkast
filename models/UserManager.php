<?php

class UserManager
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function getUser($username): array|false //geeft array terug als gebruiker bestaat, anders false. 
    {

        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE username = :username");
        $stmt->execute(["username" => $username]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registerUser($username, $password): bool //execute geeft een bool terug (true bij succes en false bij falen)
    {
        //wachtwoord hashen voordat het wordt opgeslagen
        $hash = password_hash($password, PASSWORD_DEFAULT); //password default verplicht in nieuwere php versie's.

        $stmt = $this->pdo->prepare(
            "INSERT INTO user (username, password)
         VALUES (:username, :password)"
        );

        return $stmt->execute([
            "username" => $username,
            "password" => $hash
        ]);
    }
}