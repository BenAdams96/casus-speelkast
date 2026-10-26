# De Speelkast

Een PHP-app om bordspellen en uitbreidingen te bekijken en toe te voegen, met foto's, XML-export en een avondlijst.

## Starten

1. Zet het project in de webmap van XAMPP en start Apache en MySQL.
2. Kopieer [.env.example](.env.example) naar `.env` en vul je databasegegevens in.
3. Zorg dat PHP mag schrijven in `upload/`.
4. Open [Speelkast](http://localhost/Educom/casus-speelkast/), registreer een account en log in.

Maak voor de demonstratie twee testaccounts aan. Er worden geen vaste accounts meegeleverd. Wachtwoorden worden gehasht; `.env` staat buiten Git.

## Gebruik

Na inloggen zie je alle spellen en hun eigenaar. Via de knoppen kun je een item toevoegen, XML downloaden, de avondlijst openen en uitloggen. Zonder login zijn deze pagina's niet toegankelijk.

Foto's staan in `upload/`: JPG, PNG of GIF, maximaal 2 MB. Elke upload krijgt een unieke bestandsnaam.

## Projectstructuur

- `models/`: gegevens, databasebewerkingen, foto-opslag en XML-export.
- `views/`: formulieren en overzichten.
- `controllers/`: verzoeken verwerken en models en views verbinden.
- `interfaces/`: afspraken voor foto's en opslag.
- `database/`: databaseverbinding.
- `ontwerp/`: diagrammen en toelichting.

De PHP-bestanden in de hoofdmap starten de pagina's. `DBConnect` leest de databasegegevens uit `.env`.

`BoardGame` en `Expansion` erven de gedeelde gegevens van `KastItem`. Zo hoeven bijvoorbeeld de titel, eigenaar en foto niet in beide classes apart te worden uitgewerkt.

## Databasestructuur – Speelkast

| Tabel | Kolommen |
|---|---|
| `user` | `id`, `username` (uniek), `password` (hash) |
| `items` | `id`, `user_id`, `type`, `titel`, `image_path`, `min_spelers`, `max_spelers`, `basisspel` |

Eén gebruiker kan meerdere items bezitten: `items.user_id` verwijst naar `user.id`. De gebruikersnaam wordt getoond als eigenaar. Zo hoeft de naam van de eigenaar niet nog eens bij ieder item te worden opgeslagen.

Bij bordspellen zijn de spelersaantallen verplicht. Bij uitbreidingen is `basisspel` een naam als tekst; spelersaantallen mogen leeg zijn. De database bewaart alleen het fotopad, niet de afbeelding.

Door het basisspel als tekst op te slaan, kan een uitbreiding ook worden toegevoegd als het basisspel nog niet in de kast staat. De app toont dan een waarschuwing.

**Dependency injection:** de managers krijgen PDO via hun constructor. `AuthController` krijgt `UserManager`; `SpeelkastController` krijgt `ItemManager` en `InterfaceOpslag`.

## Validatie en avondlijst

JavaScript controleert titel, basisspel, spelersaantallen en foto vóór verzenden. PHP controleert opnieuw, onder andere met `preg_match()`. Dat is nodig omdat JavaScript kan worden uitgeschakeld. Bij verkeerde inloggegevens verschijnt een foutmelding. `showImage()` controleert de bestandsextensie via `checkFileName()` voordat de foto wordt getoond.

De knop **Avondlijst** opent `avondlijst.php`: items → XML → controle met `speelkast.xsd` → HTML via `avondlijst.xslt`. De pagina toont of de XML geldig is. PHP heeft hiervoor de extensies `SimpleXML`, `dom` en `xsl` nodig; database en uploads gebruiken `pdo_mysql` en `fileinfo`.

## Diagrammen

De diagrammen zijn met hulp van AI gemaakt en gecontroleerd aan de hand van de code.

### Database

![Database](ontwerp/database/relational_database.png)

### Classes en interfaces

![Classes en interfaces](ontwerp/speelkast/Speelkast_overzicht.png)

### Programmaflow

![Programmaflow](ontwerp/speelkast/Speelkast_programmaflow.png)

[Bewerken in draw.io](ontwerp/speelkast.drawio)

