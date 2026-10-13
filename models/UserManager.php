<?php

class UserManager {
    private PDO $pdo;
    
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    public function getUser($username) {

        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE username = :username");
        $stmt->execute(["username" => $username]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}