# casus-speelkast

### Aanpak:
Functionaliteiten: wat moet de gebruiker allemaal kunnen doen?
Gegevens: welke informatie moet worden bijgehouden?
Technische eisen: welke technieken moet ik toepassen.
ontwerp: welke pagina's en hoe navigeert de gebruiker.
Technisch ontwerp: ontwerp database, classes, interfaces, methodes en MVC-structuur.
Implementatie: de applicatie stap voor stap bouwen en testen.

Vervolg: nadat de eerste opzet is uitgewerkt, een concreet stappenplan maken voor de implementatie. Hierin bepalen met welk onderdeel ik begin en in welke volgorde ik de functionaliteiten ga bouwen.

extra: Opmaak. (Wil eerst focussen zodat de backend+werking goed werkt.)


## Functionaliteiten
- gebruiker moet in kunnen loggen.
- gebruiker moet kunnen uitloggen.
- gebruiker moet bordspellen kunnen bekijken.
- gebruiker moet bordspellen kunnen toevoegen.
- bij bekijken/toevoeegen van bordspel: foto, titel, type, extra info, eigenaar.
- gebruiker moet de speelkast gegevens kunnen exporteren.
- gebruiker moet een avondlijst kunnen bekijken.

## Gegevens
- gebruiker inloggegevens: gebruikersnaam, wachtwoord (hash toepasssen)
- bordspel: foto, titel, type, extra info (aantal spelers, min leeftijd, beschrijving), eigenaar
- uitbreiding: titel, eigenaar, foto, bij welk basisspel deze hoort (misschien even kijken of dit dan niet bij bordspel zelf hoort)

## Technische eisen
- MySQL met PDO en prepared statements
- session voor login en logout
- OOP: abstracte class, subclass, interfaces
- MVC toepassen (meer uitgebreid checken hoe)
- doe dubbele verificatie (JS en Server side), voor log in maar misschien ook voor spel toevoegen
- XML, XSD, XSLT voor avondlijst
- .env voor makkelijke configuratie

## Ontwerp

## Technisch ontwerp

## Implementatie



