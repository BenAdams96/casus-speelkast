<?php

session_start();

require "models/DBConnect.php";
require "models/User.php";
require "controllers/AuthController.php";

$pdo = DBConnect::getInstance(); //DB connectie
$user = new UserManager($pdo); //Model (PDO DI injection)
$auth = new AuthController($user); //Controller

$auth->login(); //nettere manier zodat login functie aanroepen hier niet nodig is.