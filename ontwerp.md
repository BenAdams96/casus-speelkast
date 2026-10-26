# Beknopt ontwerp – Speelkast

Dit overzicht beschrijft de huidige implementatie, inclusief de aanvullende opdracht voor MVC en `.env`. Uitgebreide uitleg staat in de [README](README.md).

## Paginaflow

`index.php` (login) → `speelkast.php` (alle items) → `toevoegen.php`, `export.php` of `avondlijst.php`. Deze vervolgpagina's linken terug naar de kast. Uitloggen gebeurt via POST op `speelkast.php` en stuurt terug naar `index.php`. Nieuwe accounts worden aangemaakt via `registreren.php`.

Beschermde startscripts controleren `$_SESSION['user_id']`. De eigenaar van een nieuw item komt uit de session. Alle ingelogde gebruikers zien de gezamenlijke collectie.

## Classes, interfaces en constructors

| Onderdeel | Ontvangt of doet |
|---|---|
| `DBConnect` | Private constructor leest `.env`; `getInstance()` levert PDO. |
| `UserManager` en `ItemManager` | Krijgen PDO via de constructor; verzorgen databasebewerkingen. |
| `AuthController` | Krijgt `UserManager`; verwerkt login, registratie en logout. |
| `SpeelkastController` | Krijgt `ItemManager` en `InterfaceOpslag`; verwerkt items, export en avondlijst. |
| `KastItem` | Abstract; krijgt titel, username, imagePath, minSpelers en maxSpelers. Implementeert `InterfaceFoto`. |
| `BoardGame` | Erft de constructor van `KastItem`; eigen `omschrijving()` en `getType()`. |
| `Expansion` | Krijgt dezelfde gegevens plus basisspel; eigen `omschrijving()` en `getType()`. |
| `InterfaceFoto` | Contract: `checkFileName()` en `showImage()`. |
| `InterfaceOpslag` / `FotoOpslag` | Contract en implementatie van `saveFile()`; bewaart foto's in `upload/`. |
| `ItemType` | Enum: `bordspel` of `uitbreiding`. |
| `XmlExporter` | Geen constructorargumenten; krijgt itemobjecten via `createXml()`. |

## Database en opslag

- `user`: `id` (PK), `username` (uniek), `password` (hash).
- `items`: `id` (PK), `user_id` (FK), `type`, `titel`, `image_path`, `min_spelers`, `max_spelers`, `basisspel`.
- Relatie: `user.id` → `items.user_id` (één gebruiker, nul of meerdere items).
- `basisspel` is vrije tekst. Foto's staan in `upload/`; de database bewaart alleen het relatieve pad.

## Verwerking en MVC

Startscripts verbinden de objecten. Controllers verwerken verzoeken, models verzorgen gegevens en opslag, en views tonen de pagina's. Toevoegen: formulier → PHP-validatie → `FotoOpslag` → `ItemManager` → database. Avondlijst: itemobjecten → `XmlExporter` → XSD-controle → XSLT → HTML. De databaseconfiguratie staat apart in `.env`.
