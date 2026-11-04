<?php
require_once 'equipo.php';
require_once __DIR__ . '/interfaces/resumible.php';
class clientes implements resumible
{
    public string $nombre;
    public int $id;
    private array $equiposAlquilados;
    private int $maxAlquilerConcurrente;


    // se crea el constructor pasandole un array vacio de equipos
    public function __construct(string $nombre, int $id, int $maxAlquilerConcurrente = 3)
    {
        $this->nombre = $nombre;
        $this->id = $id;
        $this->equiposAlquilados = [];
        $this->maxAlquilerConcurrente = $maxAlquilerConcurrente;
    }
    // retorna los numeros de equipos
    public function numEquiposAlquilados()
    {
        return count($this->equiposAlquilados);
    }

    public function tieneAlquilado(equipo $equipo): bool
    {
        return in_array($equipo, $this->equiposAlquilados, true);
    }
    // pendiente
    public function alquilar(equipo $equipo): void
    {
        // Nosotros ya tenems alquilado este producto
        if ($this->tieneAlquilado($equipo)) {
            throw new Exception("ERROR YA ALQUILAMOS ESTE EQUIPOS");
        }
        // ya llegamos al cupo maximo de alquileres
        if (($this->numEquiposAlquilados() >= $this->maxAlquilerConcurrente)) {
            throw new Exception("ERROR YA LLEGASTE A TU CANTIDAD MAXIMA DE PRODUCTOS ALQUILADOS");
        }
        // otro cliente tiene alquilado ese equipo

        if ($equipo->estaAlquilado()) {
            throw new Exception("ERROR EL EQUIPO YA ESTA ALQUILADO POR OTRA PERSONA");
        }

        $equipo->marcarAlquilado();
        array_push($this->equiposAlquilados, $equipo);
    }

    // metodo para setear el objeto que compramos como devuelvo y quitarlo de nuetro array de prestamos
    public function devolver(int $numEquipo): void
    {
        foreach ($this->equiposAlquilados  as $indice => $equipo) {
            if ($equipo->getnumero() === $numEquipo) {
                $equipo->marcarDevuelto();
                unset($this->equiposAlquilados[$indice]);
                break;
            }
        }
    }
    #[Override]
    public function muestraResumen(): string
    {
        return "[" . static::class . "]" . " - {$this->nombre}" . " - ID: {$this->id}" . " - Nº ALQUILERES: {$this->maxAlquilerConcurrente}";
    }
    public function listaAlquileres(): string
    {
        $txt = "";
        foreach ($this->equiposAlquilados as $indice => $equipo) {
            $txt .= $equipo->muestraResumen() . "\n";
        }
        return $txt;
    }
}
