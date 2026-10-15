<?php

session_start();

require "database/DBConnect.php";
require "models/ItemManager.php";
require "controllers/SpeelkastController.php";

//controleren of gebruiker is ingelogd
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$pdo = DBConnect::getInstance();
$itemManager = new ItemManager($pdo);
$speelkast = new SpeelkastController($itemManager);

//formulier tonen
require "views/toevoegen_view.php";