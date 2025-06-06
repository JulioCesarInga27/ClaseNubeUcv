<?php
include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

// Crear una instancia del objeto de Firebase
$rdb = new firebaseRDB($databaseURL);

// Obtener el correo del usuario a eliminar desde la sesión
session_start();
if(isset($_SESSION['correo2'])) {
    $correo = $_SESSION['correo2'];
    
    // Crear una instancia del objeto de Firebase
    $rdb = new firebaseRDB($databaseURL);
    
    // Obtener los datos del usuario a eliminar
    $retrieve = $rdb->retrieve("/usuarios", "correo", "EQUAL", $correo);
    $userData = json_decode($retrieve, true);
    
    // Verificar si se encontraron datos del usuario
    if (!empty($userData)) {
        // Iterar sobre los datos del usuario y obtener su ID único
        foreach ($userData as $userId => $user) {
            // Eliminar el usuario de Firebase
            $rdb->delete("/usuarios", $userId); // Corregir este línea para proporcionar el identificador único del usuario
        }
    }

    // Redireccionar a la página de listado de usuarios
    header("Location: ../../html/Admin/RegistroAlumno.php");
    exit();
} else {
    // Si no se encontró el correo en la sesión, redirigir a la página de listado de usuarios
    header("Location: ../../html/Admin/ListarUsuarios.php");
    exit();
}
?>
