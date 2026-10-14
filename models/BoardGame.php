<?php
require_once __DIR__ . "/KastItem.php";

class BoardGame extends KastItem
{
    //Veel inheritance:
    //Construct en andere functies + variabelen niet nodig omdat deze al gedefinieerd zijn in KastItem
    //alleen functie omschrijving moet geimplementeerd worden.

    public function getType(): ItemType
    {
        return ItemType::BORDSPEL;
    }

    public function omschrijving()
    {
        return "$this->titel - $this->minSpelers tot $this->maxSpelers spelers";
    } //nog verder uitbreiden maar voor nu is dit genoeg om mee te testen
}