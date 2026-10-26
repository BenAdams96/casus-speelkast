# Databasestructuur – Speelkast

De database `speelkast` bevat `user` en `items`. Eén gebruiker kan meerdere items bezitten; elk item heeft één eigenaar via `items.user_id` → `user.id`.

![Relatie tussen user en items](relational_database.png)

Een kort overzicht staat in de [README: databasestructuur](../../README.md#databasestructuur--speelkast).

- De eigenaar wordt opgehaald als `user.username`; er is geen aparte kolom `eigenaar`.
- Wachtwoorden worden bij registratie gehasht.
- `basisspel` is tekst, geen foreign key naar een ander item.
- Spelersaantallen mogen bij uitbreidingen `NULL` zijn en worden niet automatisch overgenomen.
- Foto's staan in `upload/`; `items.image_path` bewaart het relatieve pad.

[Bewerkbaar diagram](../speelkast.drawio)
