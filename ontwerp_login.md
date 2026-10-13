# Login en logout

### User Story – Inloggen
**Als gebruiker** wil ik kunnen inloggen met mijn gebruikersnaam en wachtwoord, zodat ik toegang krijg tot de Speelkast.

**Acceptatiecriteria:**
- Inloggen met gebruikersnaam en wachtwoord.
- Bij correcte gegevens doorsturen naar `speelkast.php`.
- Bij onjuiste gegevens een foutmelding tonen.
- Alleen ingelogde gebruikers kunnen de Speelkast bekijken.
- Gebruiker kan uitloggen.

### View
**`views/login_view.php`**
- Formulier met gebruikersnaam en wachtwoord.
- Versturen via POST.
- Eventuele foutmelding tonen.

**`views/speelkast_view.php`**
- Uitlogknop via POST.

### Controller
**`controllers/AuthController.php`**

Verwerkt het inloggen en uitloggen.

**Methodes:**
- `login()` – controleert POST, haalt gebruiker op via `UserManager`, vergelijkt wachtwoord en slaat `user_id` op in `$_SESSION`. Bij succes redirect naar `speelkast.php`, anders foutmelding tonen via de View.
- `logout()` – controleert of via POST is uitgelogd, vernietigt de session en verwijst terug naar `index.php`.

### Model
**`models/UserManager.php`**
- Ontvangt PDO via de constructor (dependency injection).
- Zoekt gebruiker op via een prepared statement.

**Methodes:**
- `__construct($pdo)`
- `getUser($username)` – haalt gebruiker op uit MySQL.

### Databaseverbinding
**`database/DBConnect.php`**
- Maakt verbinding met MySQL via PDO.
- Gebruikt try/catch voor verbindingsfouten.
- `getInstance()` – geeft de PDO-verbinding terug.

### Startpagina's
- `index.php` – start session, maakt benodigde objecten en roept `login()` aan.
- `speelkast.php` – controleert de session, verwerkt eventueel uitloggen en toont de Speelkast.

### Database
**MySQL – tabel `user`**

| Kolom | Type |
|---|---|
| id | INT, PRIMARY KEY, AUTO_INCREMENT |
| username | VARCHAR |
| password | VARCHAR |

**Nog aanpassen:**
- Wachtwoorden hashen en controleren met `password_verify()`.
- Databasegegevens naar een configuratiebestand verplaatsen.