<?php
require_once __DIR__ . "/../interfaces/InterfaceFoto.php";
require_once __DIR__ . "/enums/ItemType.php"; //nieuwe enum gemaakt voor de itemtype, want waarom niet

abstract class KastItem implements InterfaceFoto
{
    protected string $titel;
    protected string $username; //zelfde als eigenaar
    protected string $imagePath;
    protected ?int $minSpelers; // quesstion mark omdat deze NULL kunnen zijn uitbreiding
    protected ?int $maxSpelers; // dan overnemen van het basisspel

    public function __construct($titel, $username, $imagePath, $minSpelers, $maxSpelers)
    {
        $this->titel = $titel;
        $this->username = $username;
        $this->imagePath = $imagePath;
        $this->minSpelers = $minSpelers;
        $this->maxSpelers = $maxSpelers;
    }

    public function getTitel()
    {
        return $this->titel;
    }

    public function getUsername() //beschouw username en eigenaar als hetzelfde
    {
        return $this->username;
    }

    public function getImagePath()
    {
        return $this->imagePath;
    }

    public function getMinSpelers()
    {
        return $this->minSpelers;
    }

    public function getMaxSpelers()
    {
        return $this->maxSpelers;
    }

    abstract public function getType(): ItemType;

    //methodes van InterfaceFoto
    public function checkFileName(): bool
    {
        $extension = strtolower(pathinfo($this->imagePath, PATHINFO_EXTENSION));
        return in_array($extension, ["jpg", "png", "gif"]);
    }

    public function showImage(): string
    {
        if (!$this->checkFileName()) {
            return "Ongeldig afbeeldingstype.";
        }

        return '<img src="' . htmlspecialchars($this->imagePath) . '" width="120">';
    }

    //iedere subclass maakt een eigen omschrijving
    abstract public function omschrijving();
}
