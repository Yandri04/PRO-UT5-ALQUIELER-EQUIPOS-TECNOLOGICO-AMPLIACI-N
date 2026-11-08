
<?php
session_start();

unset($_SESSION['usuario_id']);
unset($_SESSION['usuario_nombre']);

$params = session_get_cookie_params();

session_destroy();

if (ini_get("session.use_cookies")) {
    setcookie(session_name(), "", [
        "expires" => time() - 42000,
        "path" => $params["path"],
        "domain" => $params["domain"],
        "secure" => $params["secure"],
        "httponly" => $params["httponly"],
        "samesite" => "Lax"
    ]);
}

header("Location: ../index.php");
exit();

?>