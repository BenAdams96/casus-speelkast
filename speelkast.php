<?php

session_start();

require "database/DBConnect.php";
require "models/UserManager.php";
require "controllers/AuthController.php";

//controleren of gebruiker is ingelogd
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$pdo = DBConnect::getInstance();
$user = new UserManager($pdo);
$auth = new AuthController($user);

//logout controleren
$auth->logout();

//view tonen
//for now like this, later using MySQL with PDO fetch
$items = simplexml_load_file("database/speelkast.xml"); 
require "views/speelkast_view.php";