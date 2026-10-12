<?php

class AuthController {

    private UserManager $user;

    public function __construct(UserManager $user) {
        $this->user = $user;
    }

    public function login() {

        $error = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST" &&
            isset($_POST["username"], $_POST["password"])) {
                
                $username = $_POST["username"];
                $password = $_POST["password"];

                //gebruiker ophalen uit model
                $user = $this->user->getUser($username);

                //wachtwoord controleren
                //TODO: add hash
                if ($user && $password === $user["password"]) {

                    $_SESSION["user_id"] = $user["id"];

                    header("Location: views/speelkast.php");
                    exit;
                }

                $error = "Ongeldige gebruikersnaam of wachtwoord.";
            } //else toevoegen als verkeerde log in is

        require "views/login.php"; //require verschilt van header, doet geen Request sturen.
        exit; //na require wordt anders de rest van de code nog doorgevoerd. (is hier laatste stap dus niet noodzakelijk, wel netjes)
    }
}