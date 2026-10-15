<?php

session_start();

require "database/DBConnect.php";
require "models/ItemManager.php";
require "models/FotoOpslag.php";
require "controllers/SpeelkastController.php";

//controleren of gebruiker is ingelogd
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$pdo = DBConnect::getInstance();
$itemManager = new ItemManager($pdo);
$opslag = new FotoOpslag();
$speelkast = new SpeelkastController($itemManager, $opslag);

$speelkast->toevoegen();