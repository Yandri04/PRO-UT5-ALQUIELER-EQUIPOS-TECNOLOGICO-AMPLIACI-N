<?php
require_once __DIR__ . '/interfaces/alquilable.php';
require_once __DIR__ . '/interfaces/resumible.php';

abstract class equipo implements Alquilable , resumible
{
    const IVA = 0.21;
    public string $nombre;
    protected int $numero;
    private float $precio;
    private bool $alquilado;

    public function __construct(string $nombre, int $numero, float $precio)
    {
        $this->nombre = $nombre;
        $this->numero = $numero;
        $this->precio = $precio;
        $this->alquilado = false;
    }

    abstract function tipo();


    public function getNumero(): int
    {
        return $this->numero;
    }


    public function getPrecio(): float
    {
        return $this->precio;
    }

    public function getPrecioConIva(): float
    {
        return round($this->precio + ($this->precio * self::IVA), 2);
    }

    public function estaAlquilado(): bool
    {
        return $this->alquilado;
    }

    public function marcarAlquilado(): void
    {
        $this->alquilado = true;
    }

    public function marcarDevuelto(): void
    {
        $this->alquilado = false;
    }

    public function muestraResumen(): string
    {
        return "[" . static::class . "] {$this->nombre} ({$this->numero}) - {$this->precio} EUR/dia (" . $this->getPrecioConIva() . " con IVA) - " . ($this->alquilado ? "ALQUILADO" : "DISPONIBLE");
    }

    public function __toString(): string
    {
        return "[" . static::class . "] {$this->nombre} (n. {$this->numero})";
    }
}
