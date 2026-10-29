
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

    public function getAllItems(): array
    {
        $stmt = $this->pdo->query(
            "SELECT items.*, user.username
             FROM items
             JOIN user ON items.user_id = user.id"
        );

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $items = [];
        $bordspellen = [];

        foreach ($rows as $row) {
            $item = $this->createItem($row);

            //database-ID als sleutel gebruiken
            $items[(int) $row["id"]] = $item;

            //bordspellen indexeren met een hashmap
            if ($item instanceof BoardGame) {
                $naam = strtolower(trim($item->getTitel()));
                $bordspellen[$naam] = $item;
            }
        }

        //uitbreidingen aan basisspellen koppelen
        foreach ($items as $item) {
            if ($item instanceof Expansion) {
                $naam = strtolower(trim($item->getBasisspel()));

                if (isset($bordspellen[$naam])) {
                    $bordspellen[$naam]->addUitbreiding($item->getTitel());
                }
            }
        }

        return $items;
    }

    public function addItem(KastItem $item, int $userId): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO items
             (user_id, type, titel, image_path, min_spelers, max_spelers, basisspel)
             VALUES
             (:user_id, :type, :titel, :image_path, :min_spelers, :max_spelers, :basisspel)"
        );

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

    public function deleteItem(int $itemId, int $userId): bool
    {
        //alleen verwijderen als de ingelogde gebruiker eigenaar is
        $stmt = $this->pdo->prepare(
            "DELETE FROM items
             WHERE id = :id AND user_id = :user_id"
        );

        $stmt->execute([
            "id" => $itemId,
            "user_id" => $userId
        ]);

        return $stmt->rowCount() === 1;
    }
}
