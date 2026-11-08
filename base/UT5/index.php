<?php
require_once 'tienda.php';
session_start();

if (!isset($_SESSION["tienda"])) {
    $t1 = new tienda("MERCADONA");

    $t1->incluirPortatil("portatilP1", 32, 12.40, 1024);
    $t1->incluirCamara("campP1", 33, 15.23, "epson", 5000, 10000);
    $t1->incluirProyector("proyectorP1", 34, 74.25, [1, 2, 3, 4], "QHD");

    $t1->incluirClientes("LAMACARENA", 1);

    $t1->alquilarClienteEquipo(1, 1);
    $t1->devolverClienteEquipo(1, 1);

    $_SESSION["tienda"] = $t1;
} else {
    $t1 = $_SESSION["tienda"];
}

$ruta_carrito = empty($_SESSION['usuario_id']) ? "fase1/login.php" : "fase2/carrito.php";
$equiposConfirmados = $_SESSION['eAlquilados'] ?? [];
$txtEquiposConfirmados = [];
foreach ($equiposConfirmados as $key => $value) {
    $e = $t1->buscarEquipoPorNumero($value);
    if ($e) {
        array_push($txtEquiposConfirmados, $e->muestraResumen());
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
</head>

<body>
    <header>
        <h1>Tienda</h1>
        <?php if (!isset($_SESSION['usuario_nombre'])): ?>
            <form action="fase1/login.php" method="POST">
                <button type="submit">Login</button>
            </form>
        <?php else: ?>
            <p><?php echo $_SESSION['usuario_nombre'] ?></p>
        <?php endif; ?>
        <form action="fase1/logout.php" method="POST">
            <button type="submit">Logout</button>
        </form>
        <form action="<?php echo $ruta_carrito; ?>" method="POST">
            <button type="submit">Carrito</button>
        </form>
    </header>
    <fieldset>
        <legend>
            <h1>Productos</h1>
        </legend>
        <?php
        $equiposTienda = $t1->equipos;


        echo "<table>";
        echo "<tr>";
        echo "<th>ESTADO</th>";
        echo "<th>ID</th>";
        echo "<th>DESCRIPCION</th>";



        foreach ($equiposTienda as $key => $value) {
            echo "<tr>";

            echo "<td>" . ($value->estaAlquilado() ? "Alquilado" : "No alquilado") . "</td>";
            echo "<td>" . $value->getNumero() . "</td>";

            echo "<td>" . $value->muestraResumen() . "</td>";

            if (isset($_SESSION['usuario_nombre'])) {
                $estaConfirmado = in_array($value->getNumero(), $equiposConfirmados);
        ?>

                <td>
                    <?php if ($estaConfirmado): ?>
                        <form action="fase3/devolverEquipo.php" method="POST" style="display: inline-block; margin-bottom: 15px;">
                            <button type="submit" name="nEquipoDevolver" value="<?php echo $value->getNumero(); ?>">
                                Devolver equipo
                            </button>
                        </form>
                    <?php else: ?>
                        <form action="fase2/carrito.php" method="POST" style="display: inline-block; margin-bottom: 15px;">
                            <button type="submit" name="nEquipo" value="<?php echo $value->getNumero(); ?>">
                                Añadir al carrito
                            </button>
                        </form>
                    <?php endif; ?>
                </td>
        <?php
            }

            echo "</tr>";
        }

        echo "</table>";
        ?>
    </fieldset>
    <fieldset>
        <legend>Equipos Alquilados</legend>
        <?php foreach ($txtEquiposConfirmados as $key => $value) { ?>
            <p> <?php echo $value ?></p>
        <?php } ?>
    </fieldset>
</body>

</html>