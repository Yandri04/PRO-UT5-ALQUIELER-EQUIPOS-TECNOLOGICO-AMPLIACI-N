<?php
require_once '../tienda.php';
session_start();

if (isset($_SESSION['tienda']) && isset($_SESSION['usuario_id']) && isset($_POST['nEquipoDevolver'])) {
    
    $t1 = $_SESSION['tienda'];
    $usuarioActual = $_SESSION['usuario_id'];
    $equipoDevolver = $_POST['nEquipoDevolver'];

    try {
        $t1->devolverClienteEquipo($usuarioActual, $equipoDevolver);

        $_SESSION['tienda'] = $t1;

        if (isset($_SESSION['eAlquilados'])) {
            $posicion = array_search($equipoDevolver, $_SESSION['eAlquilados']);
            if ($posicion !== false) {
                unset($_SESSION['eAlquilados'][$posicion]);
                $_SESSION['eAlquilados'] = array_values($_SESSION['eAlquilados']);
            }
        }

    } catch (Exception $e) {
        echo "Error al devolver el equipo: " . $e->getMessage();
        exit;
    }
}

header("Location: ../index.php");
exit;
?>
