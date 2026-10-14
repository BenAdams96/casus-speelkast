<?php

class AuthController
{
    private UserManager $usermanager;

    public function __construct(UserManager $usermanager)
    {
        $this->usermanager = $usermanager;
    }

    /**
     * Verwerkt de login en toont het loginformulier.
     */
    public function login()
    {
        $error = "";

        if (
            $_SERVER["REQUEST_METHOD"] === "POST" &&
            isset($_POST["username"], $_POST["password"])
        ) {

            $username = $_POST["username"];
            $password = $_POST["password"];

            //gebruiker ophalen uit model
            $user = $this->usermanager->getUser($username);

            //wachtwoord controleren
            //TODO: add hash
            if ($user && $password === $user["password"]) {
                $_SESSION["user_id"] = $user["id"];

                header("Location: speelkast.php");
                exit;
            }

            $error = "Ongeldige gebruikersnaam of wachtwoord.";
        }

        //loginformulier tonen
        require "views/login_view.php";
    }

    //uitloggen
    public function logout()
    {
        //controleren of gebruiker wil uitloggen
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["logout"])) {
            $_SESSION = [];
            session_destroy();

            header("Location: index.php");
            exit;
        }
    }

}
