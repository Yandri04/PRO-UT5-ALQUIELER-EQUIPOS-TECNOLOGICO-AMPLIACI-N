<?php
require_once __DIR__ . '/interfaces/alquilable.php';
require_once __DIR__ . '/interfaces/asegurable.php';
require_once __DIR__ . '/interfaces/resumible.php';
require_once __DIR__ . '/ClasesGenerales/equipo.php';
require_once __DIR__ . '/equipos/camara.php';
require_once __DIR__ . '/equipos/portatil.php';
require_once __DIR__ . '/equipos/proyector.php';
require_once __DIR__ . '/ClasesGenerales/cliente.php';

class tienda
{
    public string $nombre;
    public array $equipos;
    public array $clientes;

    public function __construct(string $nombre)
    {
        $this->nombre = $nombre;
        $this->equipos = [
            1 => new camara("Canon EOS R6", 1, 45.50, "Canon", 100, 102400),
            2 => new portatil("HP Pavilion", 2, 25.00, 8),
            3 => new proyector("Epson EB-X06", 3, 20.00, ["HDMI", "USB"], "XGA"),
            4 => new camara("Sony Alpha A7 III", 4, 50.00, "Sony", 50, 204800),
            5 => new portatil("Lenovo ThinkPad", 5, 35.00, 16)
        ];
        $this->clientes = [
            new clientes("Yandri", 1, 3),
            new clientes("Carlos", 2, 2),
            new clientes("María", 3, 4),
            new clientes("Andrés", 4, 1),
            new clientes("Lucía", 5, 3)
        ];
    }

    private function incluirEquipo(equipo $e): void
    {
        array_push($this->equipos, $e);
    }

    public function incluirPortatil(string $nombre, int $numero, float $precio, int $ramGb): void
    {
        if (empty(trim($nombre))) {
            throw new InvalidArgumentException("El nombre del portátil no puede estar vacío.");
        }
        if ($numero <= 0) {
            throw new InvalidArgumentException("El número de identificación debe ser mayor que cero.");
        }
        if ($precio <= 0) {
            throw new InvalidArgumentException("El precio debe ser un número positivo mayor que cero.");
        }
        if ($ramGb < 0) {
            throw new InvalidArgumentException("La memoria RAM debe ser de al menos 1 GB.");
        }
        foreach ($this->equipos as $indice => $equipo) {
            if ($equipo instanceof portatil) {
                if ($equipo->getNumero() === $numero) {
                    throw new Exception("Ya existe una portatil registrada con el número: $numero.");
                }
            }
        }
        $this->incluirEquipo(new portatil($nombre, $numero, $precio, $ramGb));
    }
    public function incluirCamara(string $nombre, int $numero, float $precio, string $marca, int $minIso, int $maxIso): void
    {
        if (empty(trim($nombre))) {
            throw new InvalidArgumentException("El nombre de la cámara no puede estar vacío.");
        }

        if ($numero <= 0) {
            throw new InvalidArgumentException("El número de identificación debe ser mayor que cero.");
        }

        if ($precio <= 0) {
            throw new InvalidArgumentException("El precio debe ser un número positivo mayor que cero.");
        }

        if (empty(trim($marca))) {
            throw new InvalidArgumentException("La marca de la cámara no puede estar vacía.");
        }

        if ($minIso < 0) {
            throw new InvalidArgumentException("El valor mínimo de ISO debe ser al menos 50.");
        }

        if ($maxIso <= $minIso) {
            throw new InvalidArgumentException("El valor máximo de ISO debe ser mayor que el ISO mínimo.");
        }

        foreach ($this->equipos as $indice => $equipo) {
            if ($equipo instanceof camara) {
                if ($equipo->getNumero() === $numero) {
                    throw new Exception("Ya existe una cámara registrada con el número: $numero.");
                }
            }
        }

        $this->incluirEquipo(new camara($nombre, $numero, $precio, $marca, $minIso, $maxIso));
    }
    public function incluirProyector(string $nombre, int $numero, float $precio, array $conexiones, string $resolucion): void
    {
        if (empty(trim($nombre))) {
            throw new InvalidArgumentException("El nombre del proyector no puede estar vacío.");
        }

        if ($numero <= 0) {
            throw new InvalidArgumentException("El número de identificación debe ser mayor que cero.");
        }

        if ($precio <= 0) {
            throw new InvalidArgumentException("El precio debe ser un número positivo mayor que cero.");
        }
        if (empty($conexiones)) {
            throw new InvalidArgumentException("Las conexiones no deben ser vacias.");
        }
        if (empty(trim($resolucion))) {
            throw new InvalidArgumentException("La resolución del proyectos no puede estar vacia.");
        }

        foreach ($this->equipos as $indice => $equipo) {
            if ($equipo instanceof proyector) {
                if ($equipo->getNumero() === $numero) {
                    throw new Exception("Ya existe un proyector registrada con el número: $numero.");
                }
            }
        }
        $this->incluirEquipo(new proyector($nombre, $numero, $precio, $conexiones, $resolucion));
    }

    public function incluirClientes(string $nombre, int $maxAlquileresConcurrentes = 3): void
    {
        if (trim($nombre) === "") {
            throw new Exception("ERROR: El nombre del cliente no puede estar vacío");
        }

        if ($maxAlquileresConcurrentes <= 0) {
            throw new Exception("ERROR: El máximo de alquileres debe ser mayor que 0");
        }


        $id = count($this->clientes) + 1;
        foreach ($this->clientes as $indice => $cliente) {

            if ($cliente->id === $id) {
                throw new Exception("Ya existe un cliente registrado con el número: $id.");
            }
        }
        $clienteAgregado = new clientes($nombre, $id, $maxAlquileresConcurrentes);
        $this->clientes[$id] = $clienteAgregado;
    }

    private function listar(string $cabecera, array $elementos): string
    {
        $txt = "{$cabecera} \n";
        foreach ($elementos as $indice => $elemento) {
            $txt .= $elemento->muestraResumen() . "\n";
        }
        return $txt;
    }
    public function listarEquipos(): string
    {
        $cEquipos = count($this->equipos);
        return $this->listar("Equipos de {$this->nombre} ({$cEquipos})", $this->equipos);
    }
    public function listarClientes(): string
    {
        $cClientes = count($this->clientes);
        return $this->listar("Clientes de {$this->nombre} ({$cClientes})", $this->clientes);
    }

    public function alquilarClienteEquipo(int $idCliente, int $numEquipo): void
    {

        // obtengo el equipo a comparar
        // cmpruebo si el equipo ya esta aqluilado
        $equipoComparar = $this->equipos[$numEquipo];
        if ($equipoComparar->estaAlquilado()) {
            throw new Exception("EL EQUIPO YA ESTA ALQULADO");
        }
        // obtengo el cliente 
        $cliBuscado = null;
        foreach ($this->clientes as $indice => $cliente) {
            if ($cliente->id === $idCliente) {
                $cliBuscado = $cliente;
                break;
            }
        }
        // verificamos que obtenemos el cliente si es null, no sacamos erro sino se alquila
        if ($cliBuscado === null) {
            throw new Exception("El cliente no existe");
        }
        $cliBuscado->alquilar($equipoComparar);
    }

    public function devolverClienteEquipo(int $idCliente, int $numEquipo): void
    {
        $cliBuscado = null;
        foreach ($this->clientes as $indice => $cliente) {
            if ($cliente->id === $idCliente) {
                $cliBuscado = $cliente;
                break;
            }
        }
        if ($cliBuscado === null) {
            throw new Exception("El cliente no existe");
        }
        $cliBuscado->devolver($numEquipo);
    }

    public function getEquipo(int $numEquipo): equipo
    {
        $equipoComparar = $this->equipos[$numEquipo];
        if ($equipoComparar === null) {
            throw new Exception("EQUIPO NO EXISTE");
        } else {
            return $equipoComparar;
        }
    }

    public function equiposAsegurables(): array
    {
        $arrayDeAsegurables = [];
        foreach ($this->equipos as $key => $value) {
            if ($value instanceof Alquilable) {
                array_push($arrayDeAsegurables, $value);
            }
        }
        return $arrayDeAsegurables;
    }

    public function ordenarEquiposPorPrecio(): array
    {
        $arrayOrdenadorPorPrecio = $this->equipos;
        usort($arrayOrdenadorPorPrecio, fn($a, $b) => $a->getPrecio() <=> $b->getPrecio());
        return $arrayOrdenadorPorPrecio;
    }

    public function ordenarEquiposPorNombre(): array
    {
        $arrayOrdenadorPorNombre = $this->equipos;
        usort($arrayOrdenadorPorNombre, fn($a, $b) => strcasecmp($a->tipo(), $b->tipo()));
        return $arrayOrdenadorPorNombre;
    }

    public function ordenarClientesPorAlquileres(): array
    {
        $arrayOrdenadorPorAlquileresClientes = $this->clientes;
        usort($arrayOrdenadorPorAlquileresClientes, fn($a, $b) => $a->numEquiposAlquilados() <=> $b->numEquiposAlquilados());
        return $arrayOrdenadorPorAlquileresClientes;
    }
    public function buscarClientePorNombre(string $nombre)
    {
        foreach ($this->clientes as $cli) {
            if ($cli->nombre === $nombre) {
                return $cli;
            } else {
                return null;
            }
        }
    }
}
