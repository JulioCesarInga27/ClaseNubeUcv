<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

$rdb = new firebaseRDB($databaseURL);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sesion_id']) && isset($_POST['video_url'])) {
    $sesion_id = $_POST['sesion_id'];
    $curso_id = $_SESSION['cursoid'];
    $video_url = $_POST['video_url'];

    // Eliminar la referencia al video en la base de datos
    $rdb->delete("/sesiones/$curso_id/$sesion_id/video",$video_url);

    

// Redirigir a la página anterior
echo "$sesion_id <br>
    $curso_id<br>
    $video_url<br>";

exit;
}
?>
