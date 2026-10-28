
<?php

class XmlExporter
{
    public function createXml(array $items): string
    {
        //hoofdelement maken
        $xml = new SimpleXMLElement("<speelkast></speelkast>");

        //ieder spel toevoegen aan XML
        foreach ($items as $item) {
            $spel = $xml->addChild("item");

            $spel->addChild("type", $item->getType()->value);
            $spel->addChild("titel", htmlspecialchars($item->getTitel(), ENT_XML1, "UTF-8"));
            $spel->addChild("eigenaar", htmlspecialchars($item->getUsername(), ENT_XML1, "UTF-8"));
            $spel->addChild("foto", htmlspecialchars($item->getImagePath(), ENT_XML1, "UTF-8"));

            //spelersaantallen, indien ingevuld
            if ($item->getMinSpelers() !== null) {
                $spel->addChild("min_spelers", (string) $item->getMinSpelers());
            }

            if ($item->getMaxSpelers() !== null) {
                $spel->addChild("max_spelers", (string) $item->getMaxSpelers());
            }

            //extra gegevens voor uitbreidingen
            if ($item instanceof Expansion) {
                $spel->addChild("basisspel", htmlspecialchars($item->getBasisspel(), ENT_XML1, "UTF-8"));

                //controleren of basisspel aanwezig is
                $bestaat = false;

                foreach ($items as $anderItem) {
                    if (
                        $anderItem instanceof BoardGame &&
                        strcasecmp(trim($anderItem->getTitel()), trim($item->getBasisspel())) === 0
                    ) {
                        $bestaat = true;
                        break;
                    }
                }

                $spel->addChild("basisspel_bestaat", $bestaat ? "ja" : "nee");
            }

            //omschrijving altijd als laatste
            $spel->addChild("omschrijving", htmlspecialchars($item->omschrijving(), ENT_XML1, "UTF-8"));
        }

        return $xml->asXML();
    }
}
