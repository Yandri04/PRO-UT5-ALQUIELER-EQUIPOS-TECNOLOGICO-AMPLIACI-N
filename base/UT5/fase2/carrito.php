<?php
require_once '../tienda.php';
session_start();
$tienda = $_SESSION['tienda'];
$usuarioActual = $_SESSION['usuario_id'];

$carrito_equipos = [];
$arrayEquiposConfirmadosCli = [];
if (isset($_COOKIE['carrito_equipos'])) {
    $carrito_equipos = json_decode($_COOKIE['carrito_equipos'], true);
}

if (isset($_POST['nEquipo']) && !empty($_POST['nEquipo']) && !isset($_POST['eliminar'])) {
    $nEquipoRecibido = $_POST['nEquipo'];

    if (!in_array($nEquipoRecibido, $carrito_equipos)) {
        $carrito_equipos[] = $nEquipoRecibido;
        setcookie('carrito_equipos', json_encode($carrito_equipos), time() + 86400, "/");
    }
}

if (isset($_POST['eliminar']) && isset($_POST['nEquipo']) && !empty($_POST['nEquipo'])) {
    $nEquipoAEliminar = $_POST['nEquipo'];

    $posicion = array_search($nEquipoAEliminar, $carrito_equipos);

    if ($posicion !== false) {
        unset($carrito_equipos[$posicion]);

        $carrito_equipos = array_values($carrito_equipos);

        setcookie('carrito_equipos', json_encode($carrito_equipos), time() + 86400, "/");
    }
}

if (isset($_POST['vaciar'])) {
    setcookie('carrito_equipos', json_encode($carrito_equipos), time() - 86400, "/");
}
if (isset($_POST['confirmar'])) {

    $hubo_error = false;

    foreach ($carrito_equipos as $key => $value) {
        try {
            $tienda->alquilarClienteEquipo($usuarioActual, $value);
            array_push($arrayEquiposConfirmadosCli, $value);
        } catch (Exception $e) {
            echo "El equipo " . $value . " ya está alquilado.<br>";
            $hubo_error = true;
            break;
        }
        header("Location: ../index.php");
    }
    if (!$hubo_error) {
        setcookie('carrito_equipos', '', time() - 86400, "/");
        $carrito_equipos = [];
    }
}
$_SESSION['eAlquilados'] = $arrayEquiposConfirmadosCli;
$tienda = $_SESSION['tienda'];
$txt = "";


foreach ($carrito_equipos as $value) {
    $equipoActual = $tienda->buscarEquipoPorNumero($value);
    if ($equipoActual) {
        $txt .= $equipoActual->muestraResumen();
    } else {
        echo "Equipo ID ({$value}) no encontrado en la tienda.\n";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <fieldset>
        <legend>Carrito</legend>
        <form action="" method="POST">
            <label for="nEquipo">Añadir equipo en base ID</label>
            <input type="text" name="nEquipo">
            <button type="submit" name="eliminar">Eliminar</button>
            <br>
            <button type="submit" name="vaciar">VaciarCarrito</button>
            <br>
            <button type="submit" name="confirmar">confirmar</button>
            <fieldset>
                <legend>Equipos en el carrito</legend>
                <?php echo $txt  ?>
            </fieldset>
        </form>
    </fieldset>

</body>

</html>