<?php
include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

// Crear una instancia del objeto de Firebase
$rdb = new firebaseRDB($databaseURL);

// Recuperar los datos del formulario
$original_correo = $_POST['original_correo'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$correo = $_POST['correo'];
$tipo_usuario = $_POST['tipo_usuario'];

// Verificar si el correo ha cambiado
if ($original_correo !== $correo) {
    // Eliminar el usuario original
    $rdb->delete("/usuarios/$original_correo");
}

// Obtener el ID único del usuario por su correo electrónico
$retrieve = $rdb->retrieve("/usuarios", "correo", "EQUAL", $original_correo);
$userData = json_decode($retrieve, true);

if (!empty($userData)) {
    foreach ($userData as $userId => $user) {
        // Actualizar los datos del usuario
        $userData[$userId]['nombre'] = $nombre;
        $userData[$userId]['apellido'] = $apellido;
        $userData[$userId]['correo'] = $correo;
        $userData[$userId]['tipo_usuario'] = $tipo_usuario;
        // Actualizar el usuario en Firebase
        $rdb->update("/usuarios", $userId, $userData[$userId]);
    }
}

// Redireccionar a la página de listado de usuarios
header("Location: ../../html/Admin/RegistroAlumno.php");
exit();
?>
