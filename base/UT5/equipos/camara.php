<?php
require_once __DIR__ . '/../interfaces/asegurable.php';
require_once __DIR__ . '/../equipo.php';
class camara extends equipo implements Asegurable
{
    public string $marca;
    private int $minIso;
    private int $maxIso;

    #[Override]
    public function __construct(string $nombre, int $numero, float $precio, string $marca, int $minIso, int $maxIso)
    {
        parent::__construct($nombre, $numero, $precio);
        $this->marca = $marca;
        $this->minIso = $minIso;
        $this->maxIso = $maxIso;
    }

    public function muestraRangoIso(): string
    {
        return "ISO {$this->minIso} - {$this->maxIso}";
    }

    #[Override]
    public function calcularSeguro(int $dias): float
    {
        return round($this->getPrecio()  + ($this->getPrecio() * 0.15) * $dias, 2);
    }

    #[Override]
    public function tipo():string
    {
        return $this->nombre;
    }
    #[Override]
    public function muestraResumen(): string
    {
        $txt = parent::muestraResumen();
        $txt .= "Marca:" . $this->marca;
        $txt .= "Rango ISO:" . $this->marca;
        return $txt;
    }
}
