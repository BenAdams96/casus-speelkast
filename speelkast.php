<?php

session_start();

require "database/DBConnect.php";
require "models/UserManager.php";
require "models/ItemManager.php";
require "controllers/AuthController.php";
require "controllers/SpeelkastController.php";


//controleren of gebruiker is ingelogd
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$pdo = DBConnect::getInstance();

$userManager = new UserManager($pdo);
$auth = new AuthController($userManager);

$itemManager = new ItemManager($pdo);
$speelkast = new SpeelkastController($itemManager);

//! Q: nu word logout iedere keer bij iedere request, misschien niet heel netjes, maar om hier de check
//! al te doen lijkt me ook niet het meest netjes?
//logout controleren
$auth->logout();

//spellen tonen
$speelkast->showItems();