<?php
// Incluir configuración de Firebase y cualquier inicialización necesaria
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../Scripts/Config.php'; // Archivo de configuración común
include '../../Scripts/firebaseRDB.php'; // Clase para manejar Firebase Realtime Database

// Inicializar conexión a Firebase
$rdb = new firebaseRDB($databaseURL);

// Verificar que se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sesionid'])) {
    $sesionid = $_POST['sesionid'];
    $cursoid = $_SESSION['cursoid'];
    $video_name = $_POST['video_name'];
    // Obtener el ID del curso desde la sesión
    $data = [
        "video" => $video_name,
        "video_estado" => "Disponible",
    ];


    // Actualizar en Firebase el estado a "Oculto"
    $resultado = $rdb->update("/sesiones/$cursoid",$sesionid, $data);

    if ($resultado) {
        $_SESSION['message'] = 'Video ocultado correctamente.';
    } else {
        $_SESSION['message'] = 'Error: No se pudo ocultar el video.';
    }

    // Redireccionar a donde sea necesario después de ocultar el video
    header("Location: http://localhost/ClaseNubeUCV/html/Admin/GestionRecursos.php?curso_id=$cursoid");
    exit;
}

$_SESSION['message'] = 'No se proporcionó el ID de la sesión para ocultar el video.';
header("Location: http://localhost/ClaseNubeUCV/html/Admin/GestionRecursos.php?curso_id=$cursoid");
exit;
?>

