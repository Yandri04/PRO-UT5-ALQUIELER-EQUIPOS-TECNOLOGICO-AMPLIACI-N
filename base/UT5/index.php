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
        <form action="fase1/login.php" method="POST">
            <button type="submit">Login</button>
        </form>
        <form action="fase1/logout.php" method="POST">
            <button type="submit">Logout</button>
        </form>
        <form action="<?php echo $ruta_carrito; ?>" method="POST">

            <button type="submit">Carrito</button>
        </form>
    </header>


</body>

</html>