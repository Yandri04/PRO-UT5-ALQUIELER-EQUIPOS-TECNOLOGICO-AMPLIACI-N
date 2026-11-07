<?php
require_once __DIR__ . '/../interfaces/asegurable.php';
require_once __DIR__ . '/../equipo.php';

class portatil extends equipo implements Asegurable
{
    private int $ramGb;

    #[Override]
    public function __construct(string $nombre, int $numero, float $precio, int $ramGb)
    {
        parent::__construct($nombre, $numero, $precio);
        $this->ramGb = $ramGb;
    }


    #[Override]
    public function calcularSeguro(int $dias): float
    {
        return round($this->getPrecio()  + ($this->getPrecio() * 0.10) * $dias, 2);
    }

    #[Override]
    public function tipo()
    {
        return $this->nombre;
    }

    #[Override]
    public function muestraResumen(): string
    {
        $txt = parent::muestraResumen();
        $txt .= "la RAM para los portátiles:" . $this->ramGb;
        return $txt;
    }
}
