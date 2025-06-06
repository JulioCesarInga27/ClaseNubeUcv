<?php
// Incluir el archivo de configuración y la clase para interactuar con Firebase
include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

// Verificar si se enviaron los datos del formulario para crear el curso
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre_curso = $_POST['nombrecruso'];
    $descripcion = $_POST['descripcion'];
    $profesor = $_POST['docente'];

    // Crear una instancia del objeto de Firebase
    $rdb = new firebaseRDB($databaseURL);

    // Generar un nuevo ID único para el curso
    $nuevo_curso_id = uniqid();
    $idstatic = uniqid();

    // Datos del nuevo curso
    $nuevo_curso = [
        'id' => $nuevo_curso_id,
        'nombre_curso' => $nombre_curso,
        'descripcion' => $descripcion,
        'profesor' => $profesor,
        'idstatic' => $idstatic
    
    ];

    // Guardar el nuevo curso en la base de datos de Firebase
    $insert = $rdb->insert("/cursos", $nuevo_curso);

    // Redireccionar a alguna página después de crear el curso
    header("Location: ../../html/Admin/RegistroCursos.php");
    exit();
} else {
    // Si no se enviaron los datos del formulario, redirigir a alguna página de error o volver atrás
    header("Location: ../../html/Admin/RegistroCursos.php");
    exit();
}
?>
