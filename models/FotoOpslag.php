<?php

require_once __DIR__ . "/../interfaces/InterfaceOpslag.php";

class FotoOpslag implements InterfaceOpslag
{
    public function saveFile(array $file): string
    {
        //bestand controleren
        $extension = $this->validateFile($file);

        //unieke bestandsnaam maken
        $fileName = uniqid() . "." . $extension;
        $imagePath = "upload/" . $fileName;

        //bestemming bepalen
        $destination = __DIR__ . "/../" . $imagePath;

        //bestand van tijdelijke locatie naar uploadmap verplaatsen
        if (!move_uploaded_file($file["tmp_name"], $destination)) {
            throw new Exception("Foto kon niet worden opgeslagen.");
        }

        return $imagePath;
    }

    private function validateFile(array $file): string
    {
        //controleren of upload goed is gegaan
        if (($file["error"] ?? null) !== UPLOAD_ERR_OK) {
            throw new Exception("Foto uploaden mislukt.");
        }

        //maximale bestandsgrootte controleren (2 MB)
        if ($file["size"] > 2 * 1024 * 1024) {
            throw new Exception("Foto is te groot.");
        }

        //MIME-type controleren
        $mimeType = mime_content_type($file["tmp_name"]);

        if ($mimeType === "image/jpeg") {
            return "jpg";
        } elseif ($mimeType === "image/png") {
            return "png";
        } elseif ($mimeType === "image/gif") {
            return "gif";
        }

        throw new Exception("Ongeldig afbeeldingstype.");
    }
}