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
            // ENT_XML1 zorgt ervoor dat speciale tekens correct in XML komen
            $spel->addChild("titel", htmlspecialchars($item->getTitel(), ENT_XML1, "UTF-8"));
            $spel->addChild("eigenaar", htmlspecialchars($item->getUsername(), ENT_XML1, "UTF-8"));
            $spel->addChild("foto", htmlspecialchars($item->getImagePath(), ENT_XML1, "UTF-8"));

            //extra gegevens voor bordspellen
            if ($item instanceof BoardGame) {
                $spel->addChild("min_spelers", $item->getMinSpelers());
                $spel->addChild("max_spelers", $item->getMaxSpelers());
            }

            //extra gegevens voor uitbreidingen
            if ($item instanceof Expansion) {
                $spel->addChild("basisspel", htmlspecialchars($item->getBasisspel(), ENT_XML1, "UTF-8"));

                $has_basegame = false;

                foreach ($items as $otherItem) {
                    if ($otherItem instanceof BoardGame &&
                        //check of twee strings gelijk zijn, eerst trim, en dan namen vergelijken, zo checken of er basegame is
                        strcasecmp(trim($otherItem->getTitel()), trim($item->getBasisspel())) === 0)
                        {
                        $has_basegame = true;
                        break;
                    }
                }
            }

            $spel->addChild("omschrijving", htmlspecialchars($item->omschrijving(), ENT_XML1, "UTF-8"));
        }

        return $xml->asXML();
    }
}