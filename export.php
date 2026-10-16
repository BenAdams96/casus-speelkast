<?php
ob_start();
session_start();

//controleren of gebruiker is ingelogd
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

//benodigde classes laden
require_once "database/DBConnect.php";
require_once "models/ItemManager.php";
require_once "models/FotoOpslag.php";
require_once "models/XmlExporter.php";
require_once "controllers/SpeelkastController.php";

//objecten aanmaken
$pdo = DBConnect::getInstance();

$itemManager = new ItemManager($pdo);
$opslag = new FotoOpslag();
$speelkast = new SpeelkastController($itemManager, $opslag);

//XML exporteren
$speelkast->exportXml();