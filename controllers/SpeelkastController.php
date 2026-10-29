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

    public function showItems(): void
    {
        //controleren of er een spel verwijderd moet worden
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["verwijderen"])) {

            $itemId = (int) ($_POST["item_id"] ?? 0);
            $userId = $_SESSION["user_id"];

            //spel verwijderen als het van de ingelogde gebruiker is
            if ($itemId > 0) {
                $this->itemManager->deleteItem($itemId, $userId);
            }

            header("Location: speelkast.php");
            exit;
        }

        //alle spellen tonen
        $items = $this->itemManager->getAllItems();

        require "views/speelkast_view.php";
    }

    public function toevoegen(): void
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

        $items = $this->itemManager->getAllItems();
        require "views/toevoegen_view.php";
    }

    private function getFormulierData(): array
    {
        $type = $_POST["type"] ?? "";
        $titel = trim($_POST["titel"] ?? "");
        $basisspel = trim($_POST["basisspel"] ?? "");

        //zelf ingevuld basisspel gebruiken
        if ($basisspel === "Basisspel niet aanwezig") {
            $basisspel = trim($_POST["nieuw_basisspel"] ?? "");
        }

        $minSpelers = $_POST["min_spelers"] ?? "";
        $maxSpelers = $_POST["max_spelers"] ?? "";

        //type controleren
        if (
            $type !== ItemType::BORDSPEL->value &&
            $type !== ItemType::UITBREIDING->value
        ) {
            throw new Exception("Ongeldig speltype.");
        }

        //titel controleren
        if ($titel === "") {
            throw new Exception("Vul een titel in.");
        }

        //basisspel verplicht bij uitbreiding
        if ($type === ItemType::UITBREIDING->value && $basisspel === "") {
            throw new Exception("Vul een basisspel in.");
        }

        //spelersaantallen verplicht bij bordspel
        if ($type === ItemType::BORDSPEL->value) {
            if ($minSpelers === "" || $maxSpelers === "") {
                throw new Exception("Vul beide spelersaantallen in.");
            }
        }

        //spelersaantallen valideren
        if ($minSpelers !== "" && !preg_match("/^[1-9][0-9]*$/", $minSpelers)) {
            throw new Exception("Ongeldig minimaal aantal spelers.");
        }

        if ($maxSpelers !== "" && !preg_match("/^[1-9][0-9]*$/", $maxSpelers)) {
            throw new Exception("Ongeldig maximaal aantal spelers.");
        }

        //lege waarden omzetten naar null
        $minSpelers = $minSpelers === "" ? null : (int) $minSpelers;
        $maxSpelers = $maxSpelers === "" ? null : (int) $maxSpelers;

        if (
            $minSpelers !== null &&
            $maxSpelers !== null &&
            $maxSpelers < $minSpelers
        ) {
            throw new Exception("Max spelers mag niet lager zijn dan min spelers.");
        }

        return [
            "type" => $type,
            "titel" => $titel,
            "username" => $_SESSION["username"],
            "basisspel" => $basisspel,
            "min_spelers" => $minSpelers,
            "max_spelers" => $maxSpelers
        ];
    }


    public function exportXml(): void
    {
        //spellen ophalen en XML maken
        $items = $this->itemManager->getAllItems();

        $exporter = new XmlExporter();
        $xml = $exporter->createXml($items);

        //XML netjes opmaken
        $dom = new DOMDocument();
        $dom->formatOutput = true;
        $dom->loadXML($xml);
        $xml = $dom->saveXML();

        //volledige Speelkast downloaden als ZIP

        if (isset($_GET["zip"])) {

            $zipBestand = tempnam(sys_get_temp_dir(), "speelkast_");

            if ($zipBestand === false) {
                die("Kon geen tijdelijk bestand maken.");
            }

            $zip = new ZipArchive();

            if ($zip->open($zipBestand, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                die("Kon ZIP-bestand niet openen.");
            }

            //XML toevoegen
            $zip->addFromString("speelkast.xml", $xml);

            //foto's toevoegen
            foreach ($items as $item) {
                $bestandsnaam = basename($item->getImagePath());
                $fotoPad = __DIR__ . "/../upload/" . $bestandsnaam;

                if (is_file($fotoPad)) {
                    $zip->addFile($fotoPad, "upload/" . $bestandsnaam);
                }
            }

            //controleren of ZIP succesvol is gemaakt
            if (!$zip->close()) {
                die("ZIP-bestand kon niet worden opgeslagen.");
            }

            clearstatcache(true, $zipBestand);

            if (!is_file($zipBestand) || filesize($zipBestand) === 0) {
                die("ZIP-bestand is leeg of bestaat niet.");
            }

            //eventuele eerdere uitvoer verwijderen
            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            //ZIP downloaden
            header("Content-Type: application/zip");
            header('Content-Disposition: attachment; filename="speelkast.zip"');
            header("Content-Length: " . filesize($zipBestand));

            readfile($zipBestand);
            unlink($zipBestand);
            exit;
        }


        //alleen XML downloaden
        if (isset($_GET["download"])) {
            header("Content-Type: application/xml; charset=UTF-8");
            header('Content-Disposition: attachment; filename="speelkast.xml"');

            echo $xml;
            exit;
        }

        //exportpagina tonen
        require "views/export_view.php";
    }


    public function showAvondlijst(): void
    {
        $items = $this->itemManager->getAllItems();

        $exporter = new XmlExporter();
        $xml = $exporter->createXml($items);

        $dom = new DOMDocument();
        $dom->loadXML($xml);

        //XML valideren
        libxml_use_internal_errors(true);
        $isValid = $dom->schemaValidate(__DIR__ . "/../speelkast.xsd");
        libxml_clear_errors();
        libxml_use_internal_errors(false);

        //XSLT laden
        $xslt = new DOMDocument();
        $xslt->load(__DIR__ . "/../avondlijst.xslt");

        $processor = new XSLTProcessor();
        $processor->importStylesheet($xslt);

        $validatie = $isValid
            ? "XML is geldig volgens XSD."
            : "XML is niet geldig volgens XSD.";

        $processor->setParameter("", "validatie", $validatie);

        header("Content-Type: text/html; charset=UTF-8");
        echo $processor->transformToXML($dom);
    }
}
