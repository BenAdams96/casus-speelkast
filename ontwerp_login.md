# Login pagina

### User Story – Inloggen

**Als gebruiker** wil ik kunnen inloggen met mijn gebruikersnaam en wachtwoord, zodat ik toegang krijg tot de Speelkast.

**Acceptatiecriteria:**
- De gebruiker kan een gebruikersnaam en wachtwoord invoeren.
- De gebruiker kan op een knop klikken om in te loggen.
- Bij correcte inloggegevens wordt de gebruiker doorgestuurd naar de Speelkast.
- Bij onjuiste inloggegevens verschijnt een foutmelding.
- Bij lege invoervelden verschijnt een foutmelding.

**Technische eisen:**
- Gebruikersgegevens worden opgeslagen in MySQL.
- Wachtwoorden worden opgeslagen als hash.
- PDO met prepared statements voor het ophalen van gebruikersgegevens.
- `password_verify()` voor wachtwoordcontrole.
- `$_SESSION` om de gebruiker ingelogd te houden.
- Databasegegevens via configuratie (`.env` of configuratiebestand). (later)

### View
**login.php**
- invoerveld voor gebruikersnaam (text)
- invoerveld voor wachtwoord (password)
- knop om in te loggen
- eventuele foutmelding tonen
- formulier verstuurt gegevens via POST

### Controller
**AuthController.php**
Verwerkt het loginverzoek:
- ontvangt POST met gebruikersnaam en wachtwoord
- controleert of beide velden zijn ingevuld
- vraagt gebruikersgegevens op bij het Model
- controleert het ingevoerde wachtwoord met `password_verify()`
- bij succesvolle login: slaat gebruikersgegevens (id en gebruikersnaam) op in `$_SESSION`
- verwijst gebruiker door naar de Speelkast-pagina
- bij mislukte login: toont foutmelding via de View

**Methodes (voorlopig):**
- `login()`
- `logout()`

### Model

**DBConnect.php**
- maakt verbinding met MySQL via PDO
- gebruikt databasegegevens uit configuratie
- handelt eventuele verbindingsfouten af met try/catch
- geeft de PDO-verbinding door aan andere classes

**Methodes:**
- `getConnection()` – geeft de PDO-verbinding terug

**User.php**
- ontvangt de PDO-verbinding via de constructor (dependency injection)
- zoekt gebruiker op in MySQL met prepared statement
- geeft de gevonden gebruikersgegevens terug (of geen resultaat)

**Methodes:**
- `__construct($db_handle)` – ontvangt de databaseverbinding
- `findByUsername($username)` – zoekt gebruiker op basis van gebruikersnaam

### Database

**MySQL – tabel users**

| Kolom | Type | Beschrijving |
|---|---|---|
| id | INT, PRIMARY KEY, AUTO_INCREMENT | uniek gebruikers-ID |
| username | VARCHAR(100), UNIQUE | gebruikersnaam |
| password_hash | VARCHAR(255) | gehasht wachtwoord |

Database en tabel aanmaken via phpMyAdmin.