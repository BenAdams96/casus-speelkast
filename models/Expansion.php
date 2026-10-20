<?php
require_once __DIR__ . "/KastItem.php";

class Expansion extends KastItem
{
    protected string $basisspel;

    public function __construct($titel, $username, $imagePath, $minSpelers, $maxSpelers, $basisspel)
    {
        parent::__construct($titel, $username, $imagePath, $minSpelers, $maxSpelers);
        $this->basisspel = $basisspel; //omdat alleen basisspel extra is vergeleken met KastItem
    }

    public function getType(): ItemType
    {
        return ItemType::UITBREIDING;
    }

    public function getBasisspel(): string
    {
        return $this->basisspel;
    }

    public function omschrijving(): string
    {
        if ($this->basisspel === "Basisspel niet aanwezig") {
            return "$this->titel is een uitbreiding ($this->minSpelers tot $this->maxSpelers spelers)";
        }

        return "$this->titel - uitbreiding voor $this->basisspel ($this->minSpelers tot $this->maxSpelers spelers)";
    }
}