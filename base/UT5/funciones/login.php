<?php

require_once '../../UT4/tienda.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uRecibido = $_POST['usuario'] ?? "";
    if (!empty($uRecibido)) {
        $tienda = $_SESSION["tienda"];
        $clienteEncontrado = $tienda->buscarClientePorNombre($uRecibido);
        if ($clienteEncontrado !== null) {
            $_SESSION['usuario_id'] = $clienteEncontrado->getId(); 
            $_SESSION['usuario_nombre'] = $clienteEncontrado->getNombre();
            setcookie("usuario_logiado", $uRecibido, [
                "expires" => time() + (86400 * 30),
                "path" => "/",
                "secure" => false,
                "httponly" => true
            ]);
            header("Location: ../../UT4/main.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

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
            <button>Login</button>
        </form>
    </fieldset>
</body>

</html>