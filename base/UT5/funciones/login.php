<?php
require_once '../tienda.php';
session_start();


$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $uRecibido = $_POST['usuario'] ?? "";

    if (!empty($uRecibido)) {
        $tienda = $_SESSION["tienda"];
        $clienteEncontrado = $tienda->buscarClientePorNombre($uRecibido);
        if ($clienteEncontrado !== null) {
            $_SESSION['usuario_id'] = $clienteEncontrado->getId();
            $_SESSION['usuario_nombre'] = $clienteEncontrado->getNombre();

            header("Location: ../index.php");
            exit();
        } else {
            $error = "El cliente no existe";
        }
    } else {
        $error = "Introduce un nombre";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <fieldset>
        <legend>Login</legend>

        <form action="" method="POST">
            <label for="usuario">Ingrese su usuario</label>
            <input type="text" id="usuario" name="usuario">
            <button type="submit">Login</button>
        </form>

        <?php
        if (!empty($error)) {
            echo $error;
        }
        ?>
    </fieldset>
</body>

</html>