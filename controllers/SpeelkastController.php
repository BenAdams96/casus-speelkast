<?php

class SpeelkastController
{
    private ItemManager $itemManager;
    private InterfaceOpslag $opslag;

    public function __construct(ItemManager $itemManager, InterfaceOpslag $opslag)
    {
        $this->itemManager = $itemManager;
        $this->opslag = $opslag;
    }

    public function showItems()
    {
        $items = $this->itemManager->getAllItems();

        require "views/speelkast_view.php";
    }

    public function toevoegen()
    {
        $error = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            try {
                //formuliergegevens ophalen en controleren
                $data = $this->getFormulierData();

                //foto opslaan
                $data["image_path"] = $this->opslag->saveFile($_FILES["foto"]);

                //object maken en opslaan in database
                $item = $this->itemManager->createItem($data);
                $this->itemManager->addItem($item, $_SESSION["user_id"]);

                header("Location: speelkast.php");
                exit;

            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        require "views/toevoegen_view.php";
    }

    private function getFormulierData(): array
    {
        var_dump($_POST);
        exit;
        //formuliergegevens ophalen
        $type = $_POST["type"];
        $titel = trim($_POST["titel"]);
        $basisspel = trim($_POST["basisspel"] ?? "");

        $minSpelers = $_POST["min_spelers"];
        $maxSpelers = $_POST["max_spelers"];

        //basisvalidatie
        if ($titel === "") {
            throw new Exception("Vul een titel in.");
        }

        if ($type === ItemType::UITBREIDING->value && $basisspel === "") {
            throw new Exception("Vul een basisspel in.");
        }

        //lege spelersaantallen omzetten naar null
        if ($minSpelers === "") {
            $minSpelers = null;
        }

        if ($maxSpelers === "") {
            $maxSpelers = null;
        }

        return [ //username hier niet nodig. gebruiken we 
            "type" => $type,
            "titel" => $titel,
            "basisspel" => $basisspel,
            "min_spelers" => $minSpelers,
            "max_spelers" => $maxSpelers
        ];
    }
}