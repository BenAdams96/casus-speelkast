<?php

session_start();

require "database/DBConnect.php";
require "models/UserManager.php";
require "controllers/AuthController.php";

$pdo = DBConnect::getInstance();
$usermanager = new UserManager($pdo);
$auth = new AuthController($usermanager);

$auth->register();