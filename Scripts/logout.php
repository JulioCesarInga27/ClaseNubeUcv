<?php




// Inicia la sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// bloquea la pagina

// Redirige a la página de inicio de sesión
header("Location: ../html/login.html");
session_destroy();

exit();
?>