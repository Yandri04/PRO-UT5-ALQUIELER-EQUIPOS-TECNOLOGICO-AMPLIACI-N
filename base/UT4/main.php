<?php
require_once 'tienda.php';

$t1 = new tienda("MERCADONA");
/*
verificamos los listar
echo $t1->listarClientes();
echo "\n";
echo $t1->listarEquipos();
*/

/*
incluir equipos
$t1->incluirPortatil("portatilP1", 32, 12.40, 1024);
$t1->incluirCamara("campP1", 33, 15.23, "epson", 5000, 10000);
$t1->incluirProyector("proyectorP1", 34, 74.25, [1, 2, 3, 4], "QHD");
echo $t1->listarEquipos();
*/

/* incluir cliente
$t1->incluirClientes("LAMACARENA",1);
echo $t1->listarClientes();
*/

/* 
Alquilar y devolver equipos
$t1->alquilarClienteEquipo(1,1);
echo $t1->listarEquipos();
$t1->devolverClienteEquipo(1,1);
echo "\n";
echo $t1->listarEquipos();
*/

echo "ASEGURABLES";
echo "\n";
print_r($t1->equiposAsegurables());
echo "\n";
echo "ORDENADOS POR PRECIO";
echo "\n";
print_r($t1->ordenarEquiposPorPrecio());
echo "\n";
echo "ORDENADOS POR NOMBRES EQUIPOS";
echo "\n";
print_r($t1->ordenarEquiposPorNombre());
echo "\n";
echo "ORDENADOS POR Clientes Por Alquileres";
echo "\n";
print_r($t1->ordenarClientesPorAlquileres());
