<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

$rdb = new firebaseRDB($databaseURL);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sesion_id']) && isset($_POST['material_index'])) {
    $sesion_id = $_POST['sesion_id'];
    $curso_id = $_SESSION['cursoid'];
    $material_index = $_POST['material_index'];

    // Eliminar el material de la base de datos
    $resultado = $rdb->delete("/sesiones/$curso_id/$sesion_id/material/", $material_index);

    if ($resultado) {
        $_SESSION['message'] = 'Material eliminado correctamente.';

        // Redirigir a la página anterior
        header("Location: http://localhost/ClaseNubeUCV/html/Profesor/CursoOp.php?curso_id=$curso_id");
        exit;
    } else {
        $_SESSION['message'] = 'Error: No se pudo eliminar el material.';
    }
}

// Redirigir a la página anterior si no se proporcionaron datos adecuados
header("Location: http://localhost/ClaseNubeUCV/html/Profesor/CursoOp.php?curso_id=$curso_id");
exit;
?>
