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
            if ($user && password_verify($password, $user["password"])) {
                session_regenerate_id(true);

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];

                header("Location: speelkast.php");
                exit;
            }

            $error = "Ongeldige gebruikersnaam of wachtwoord.";
        }

        //loginformulier tonen
        require "views/login_view.php";
    }

    public function register(): void
    {
        $error = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = trim($_POST["username"] ?? "");
            $password = $_POST["password"] ?? "";

            if ($username !== "" && $password !== "") {
                $this->usermanager->registerUser($username, $password);

                header("Location: index.php");
                exit;
            }

            $error = "Vul alle velden in.";
        }

        require __DIR__ . "/../views/registreren_view.php";
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
