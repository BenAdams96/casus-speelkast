<?php

require_once __DIR__ . "/BoardGame.php";
require_once __DIR__ . "/Expansion.php";
require_once __DIR__ . "/enums/ItemType.php";

class ItemManager
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createItem(array $data): KastItem
    {
        if ($data["type"] === ItemType::BORDSPEL->value) {
            return new BoardGame(
                $data["titel"],
                $data["username"],
                $data["image_path"],
                $data["min_spelers"],
                $data["max_spelers"]
            );
        }

        if ($data["type"] === ItemType::UITBREIDING->value) {
            return new Expansion(
                $data["titel"],
                $data["username"],
                $data["image_path"],
                $data["min_spelers"],
                $data["max_spelers"],
                $data["basisspel"]
            );
        }

        throw new InvalidArgumentException("Ongeldig speltype.");
    }

    public function getAllItems()
    {
        $stmt = $this->pdo->query(
            "SELECT items.*, user.username
         FROM items
         JOIN user ON items.user_id = user.id"
        );

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $items = [];

        //iedere database-rij omzetten naar een object
        foreach ($rows as $row) {
            $items[] = $this->createItem($row);
        }

        return $items;
    }

    public function addItem(KastItem $item, int $userId)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO items
        (user_id, type, titel, image_path, min_spelers, max_spelers, basisspel)
        VALUES
        (:user_id, :type, :titel, :image_path, :min_spelers, :max_spelers, :basisspel)"
        );

        //gegevens uit het object ophalen
        return $stmt->execute([
            "user_id" => $userId,
            "type" => $item->getType()->value,
            "titel" => $item->getTitel(),
            "image_path" => $item->getImagePath(),
            "min_spelers" => $item->getMinSpelers(),
            "max_spelers" => $item->getMaxSpelers(),
            "basisspel" => $item instanceof Expansion ? $item->getBasisspel() : null
        ]);
    }

}
