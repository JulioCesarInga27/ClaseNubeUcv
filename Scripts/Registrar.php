<?php
include 'Config.php';
include 'firebaseRDB.php';

session_start();
$correotem = $_SESSION['correo_temporal'];

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$tipo_usuario = $_POST['tipo_usuario'];
$contraseña = $_POST['pass2'];

try {
    $rdb = new firebaseRDB($databaseURL);
    
    // Verificar si el correo ya existe en la base de datos
    $retrieve = $rdb->retrieve("/usuarios", "correo", "EQUAL", $correotem);
    $data = json_decode($retrieve, true);

    if (!empty($data)) {
        echo "<script>alert('El correo ya está registrado');</script>";
        echo "<script>window.location.href = '../html/signup.html';</script>";
        
        exit();
    }

    // Crear nuevo usuario
    $newUser = [
        'correo' => $correotem,
        'nombre' => $nombre,
        'apellido' => $apellido,
        'tipo_usuario' => $tipo_usuario,
        'contraseña' => $contraseña // Nota: Asegúrate de manejar las contraseñas de manera segura
    ];

    $insert = $rdb->insert("/usuarios", $newUser);

    if ($insert) {
        echo "<script>alert('Cuenta creada con éxito');</script>";
        echo "<script>window.location.href = '../html/login.html';</script>";
        
    } else {
        echo "<script>alert('Error al crear la cuenta');</script>";
        echo "<script>window.location.href = '../html/signup.html';</script>";
        
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>