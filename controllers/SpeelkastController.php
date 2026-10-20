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
        $items = $this->itemManager->getAllItems();
        require "views/toevoegen_view.php";
    }


    // Server side checking whether the form is filled in correctly.
    private function getFormulierData(): array
    {
        //formuliergegevens ophalen
        $type = $_POST["type"] ?? "";
        $titel = trim($_POST["titel"] ?? "");
        $basisspel = trim($_POST["basisspel"] ?? "");

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

        //controleren of ingevulde spelersaantallen positieve gehele getallen zijn
        if ($minSpelers !== "" && !preg_match("/^[1-9][0-9]*$/", $minSpelers)) {
            throw new Exception("Ongeldig minimaal aantal spelers.");
        }

        if ($maxSpelers !== "" && !preg_match("/^[1-9][0-9]*$/", $maxSpelers)) {
            throw new Exception("Ongeldig maximaal aantal spelers.");
        }

        //lege spelersaantallen omzetten naar null
        if ($minSpelers === "") {
            $minSpelers = null;
        } else {
            $minSpelers = (int) $minSpelers;
        }

        if ($maxSpelers === "") {
            $maxSpelers = null;
        } else {
            $maxSpelers = (int) $maxSpelers;
        }

        //maximaal mag niet kleiner zijn dan minimaal
        if (
            $minSpelers !== null && $maxSpelers !== null &&
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

    public function exportXml()
    {
        //spellen uit database ophalen
        $items = $this->itemManager->getAllItems();

        //spellen omzetten naar XML
        $exporter = new XmlExporter();
        $xml = $exporter->createXml($items);

        //XML netjes opmaken
        $dom = new DOMDocument();
        $dom->formatOutput = true;
        $dom->loadXML($xml);
        $xml = $dom->saveXML();

        //XML downloaden als op download is geklikt
        if (isset($_GET["download"])) {
            header("Content-Type: application/xml; charset=UTF-8");
            header('Content-Disposition: attachment; filename="speelkast.xml"');

            echo $xml;
            exit;
        }

        //anders de exportpagina tonen
        require "views/export_view.php";
    }



    public function showAvondlijst()
    {
        //spellen ophalen en XML maken
        $items = $this->itemManager->getAllItems();

        $exporter = new XmlExporter();
        $xml = $exporter->createXml($items);

        //tijdelijk testen met ongeldig speltype
        // $xml = str_replace("<type>bordspel</type>", "<type>ongeldig</type>", $xml);

        //XML-string laden in DOMDocument
        $dom = new DOMDocument();
        $dom->loadXML($xml);

        //XML controleren met XSD
        libxml_use_internal_errors(true);
        $isValid = $dom->schemaValidate(__DIR__ . "/../speelkast.xsd");
        libxml_clear_errors();
        libxml_use_internal_errors(false);

        //XSLT-bestand laden
        $xslt = new DOMDocument();
        $xslt->load(__DIR__ . "/../avondlijst.xslt");

        //XML omzetten naar HTML met XSLT
        $processor = new XSLTProcessor();
        $processor->importStylesheet($xslt);

        //validatieresultaat doorgeven aan XSLT
        $validatie = $isValid ? "XML is geldig volgens XSD." : "XML is niet geldig volgens XSD.";
        $processor->setParameter("", "validatie", $validatie);

        //HTML tonen in browser
        header("Content-Type: text/html; charset=UTF-8");
        echo $processor->transformToXML($dom);
    }

}