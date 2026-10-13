<?php

session_start();

require "database/DBConnect.php";
require "models/UserManager.php";
require "controllers/AuthController.php";

$pdo = DBConnect::getInstance(); //DB connectie
$user = new UserManager($pdo); //Model (PDO DI injection)
$auth = new AuthController($user); //Controller

$auth->login();

// $route = $_GET["route"] ?? "login";

// switch ($route) {
//     case "login":
//         $auth->login();
//         break;

//     case "logout":
//         $auth->logout();
//         break;

//     case "speelkast":
//         if (!isset($_SESSION["user_id"])) {
//             header("Location: index.php");
//             exit;
//         }

//         require "views/speelkast.php";
//         break;
// }