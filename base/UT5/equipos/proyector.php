<?php
require_once __DIR__ . '/../equipo.php';
class proyector extends equipo
{
    public array $conexiones;
    private string $resolucion;

    #[Override]
    public function __construct(string $nombre, int $numero, float $precio, array $conexiones, string $resolucion)
    {
        parent::__construct($nombre, $numero, $precio);
        $this->conexiones = $conexiones;
        $this->resolucion = $resolucion;
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
        foreach ($this->conexiones as $key => $value) {
            $txt .= "CONEXIONES {$value} \n";
        }
        $txt .= "Resolución:" . $this->resolucion;
        return $txt;
    }
}
