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

    public function getAllItems()
    {
        $stmt = $this->pdo->query(
            "SELECT items.*, user.username
             FROM items
             JOIN user ON items.user_id = user.id"
        );

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $items = [];
        $basisspellen = [];

        //eerst de basisspellen verzamelen
        foreach ($rows as $row) {
            if ($row["type"] === ItemType::BORDSPEL->value) {
                $basisspellen[$row["titel"]] = $row;
            }
        }

        //iedere database-rij omzetten naar een object
        foreach ($rows as $row) {

            if ($row["type"] === ItemType::BORDSPEL->value) {
                $item = new BoardGame(
                    $row["titel"],
                    $row["username"],
                    $row["image_path"],
                    $row["min_spelers"],
                    $row["max_spelers"]
                );

            } elseif ($row["type"] === ItemType::UITBREIDING->value) {

                //bijbehorend basisspel zoeken
                $basis = $basisspellen[$row["basisspel"]] ?? null;

                //eigen spelersaantallen gebruiken, anders die van basisspel
                $minSpelers = $row["min_spelers"] ?? ($basis["min_spelers"] ?? null);
                $maxSpelers = $row["max_spelers"] ?? ($basis["max_spelers"] ?? null);
                //TODO: checken hoe dit netter kan!

                $item = new Expansion(
                    $row["titel"],
                    $row["username"],
                    $row["image_path"],
                    $minSpelers,
                    $maxSpelers,
                    $row["basisspel"]
                );

            } else {
                continue;
            }

            $items[] = $item;
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
