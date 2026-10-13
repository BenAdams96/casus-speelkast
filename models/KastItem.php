
<?php
require_once __DIR__ . "../interfaces/InterfaceFoto.php";

abstract class KastItem implements InterfaceFoto
{
    protected string $titel;
    protected string $eigenaar;

    public function __construct($titel, $eigenaar)
    {
        $this->titel = $titel;
        $this->eigenaar = $eigenaar;
    }

    public function getTitel()
    {
        return $this->titel;
    }

    public function getEigenaar()
    {
        return $this->eigenaar;
    }

    //TODO: verder uitbreiden, voor nu is dit genoeg om te testen
}
