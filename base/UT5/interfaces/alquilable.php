<?php
interface Alquilable
{
    public function estaAlquilado(): bool;
    public function marcarAlquilado(): void;
    public function marcarDevuelto(): void;
}
