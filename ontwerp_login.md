# Inloggen, registreren en uitloggen

Als gebruiker wil ik kunnen inloggen, zodat ik de gezamenlijke Speelkast kan bekijken en items kan toevoegen.

## Samenwerking

1. `index.php` start de session en vraagt PDO op via `DBConnect::getInstance()`.
2. Het startscript geeft PDO aan `UserManager` en geeft die manager aan `AuthController`.
3. `AuthController::login()` toont bij GET het formulier uit `views/login_view.php`.
4. Bij POST haalt `UserManager::getUser()` de gebruiker op met een prepared statement. De controller controleert het wachtwoord met `password_verify()`.
5. Bij succes wordt het session-ID vernieuwd. `user_id` en `username` worden in de session opgeslagen en de browser gaat naar `speelkast.php`.
6. Beschermde startscripts sturen een bezoeker zonder `user_id` terug naar `index.php`.

## Registreren

`registreren.php` roept `AuthController::register()` aan. De controller controleert of gebruikersnaam en wachtwoord zijn ingevuld. `UserManager::registerUser()` maakt een hash met `password_hash()` en slaat de gebruiker op met een prepared statement. Daarna gaat de browser terug naar de loginpagina.

## Uitloggen

Het overzicht verstuurt een POST met `logout`. `AuthController::logout()` maakt `$_SESSION` leeg, vernietigt de session en stuurt terug naar `index.php`.

## Huidige aandachtspunten

- De logincontroller maakt bij een mislukte login een foutmelding; `login_view.php` toont deze onder de inlogknop.
- De databaseconfiguratie wordt uit `.env` gelezen en wachtwoorden worden gehasht.
- Voor een nieuwe installatie worden accounts via registratie aangemaakt; er is geen seed met vaste inloggegevens.

Zie de [README](README.md) voor installatie, de databasetabellen en alle diagrammen.
