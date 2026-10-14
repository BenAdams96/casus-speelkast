<?php

session_start();

//controleren of gebruiker is ingelogd
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

//formulier tonen
require "views/toevoegen_view.php";