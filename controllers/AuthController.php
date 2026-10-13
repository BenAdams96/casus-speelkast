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
            //$user is: array(3) { ["id"]=> int(1) ["username"]=> string(3) "ben" ["password"]=> string(4) "test" }
            
            //wachtwoord controleren
            //TODO: add hash
            if ($user && $password === $user["password"]) {
                $_SESSION["user_id"] = $user["id"]; // use id since people can have same name (not implemented yet)

                header("Location: speelkast.php");
                
                exit;
            } //else toevoegen

            $error = "Ongeldige gebruikersnaam of wachtwoord.";
        }

        //loginformulier tonen als er geen login is
         require "views/login_view.php";
    }

    //uitloggen
    public function logout()
    {
        if (isset($_POST["logout"])) {
            session_destroy();
            header("Location: index.php");
            exit;
        }
    }

}
