<?php

require_once __DIR__ . "/KastItem.php";

class BoardGame extends KastItem
{
    private array $uitbreidingen = [];

    public function getType(): ItemType
    {
        return ItemType::BORDSPEL;
    }

    public function addUitbreiding(string $titel): void
    {
        $this->uitbreidingen[] = $titel;
    }

    public function omschrijving(): string
    {
        $aantal = count($this->uitbreidingen);

        if ($aantal === 0) {
            return "Dit is een basisspel en heeft geen uitbreiding in de kast staan.";
        }

        $soort = $aantal === 1 ? "uitbreiding" : "uitbreidingen";
        $namen = implode(", ", $this->uitbreidingen);

        return "Dit is een basisspel en heeft $aantal $soort: $namen.";
    }
}
