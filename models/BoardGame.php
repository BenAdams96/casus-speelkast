<?php

require_once __DIR__ . "/KastItem.php";

class BoardGame extends KastItem
{
    //Constructor en andere functies worden overgenomen van KastItem

    public function getType(): ItemType
    {
        return ItemType::BORDSPEL;
    }

    public function omschrijving(): string
    {
        return "Dit is een basisspel.";
    }
}
