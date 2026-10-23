
<?php

require_once __DIR__ . "/KastItem.php";

class Expansion extends KastItem
{
    protected string $basisspel;

    public function __construct($titel, $username, $imagePath, $minSpelers, $maxSpelers, $basisspel)
    {
        parent::__construct($titel, $username, $imagePath, $minSpelers, $maxSpelers);
        $this->basisspel = $basisspel;
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
        return "Dit spel is een uitbreiding van $this->basisspel.";
    }
}
