<?php
include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

// Crear una instancia del objeto de Firebase
$rdb = new firebaseRDB($databaseURL);

// Verificar si se enviaron los datos del formulario para actualizar el curso
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id = $_POST['id'];
    $nombre_curso = $_POST['nombre_curso'];
    $descripcion = $_POST['descripcion'];
    $profesor = $_POST['profesor'];

    // Recuperar todos los cursos desde Firebase
    $retrieve = $rdb->retrieve("/cursos");
    $cursos = json_decode($retrieve, true);

    if (!$cursos) {
        echo "No se pudieron recuperar los cursos de Firebase.";
        exit();
    }

    $firebase_id = null;

    // Buscar el curso con el 'id' específico
    foreach ($cursos as $firebase_id_actual => $curso) {
        if (isset($curso['id']) && $curso['id'] === $id) {
            // Encontramos el curso con el 'id' específico
            $firebase_id = $firebase_id_actual;
            break;
        }
    }

    if (!$firebase_id) {
        echo "No se encontró ningún curso con el ID '$id' en Firebase.";
        exit();
    }

    // Datos del curso actualizado
    $curso_actualizado = [
        'nombre_curso' => $nombre_curso,
        'descripcion' => $descripcion,
        'profesor' => $profesor
        // Puedes agregar más campos aquí según sea necesario
    ];

    // Actualizar el curso en la base de datos de Firebase
    $update = $rdb->update("/cursos",$firebase_id, $curso_actualizado);

    if ($update) {
        // Redireccionar si la actualización fue exitosa
        header("Location: ../../html/Admin/Cursos.php");
        exit();
    } else {
        // Manejar el caso de error en la actualización
        echo "Error al actualizar el curso en Firebase.";
        exit();
    }
} else {
    // Si no se enviaron los datos del formulario, redirigir a alguna página de error o volver atrás
    header("Location: ../../html/Admin/Cursos.php");
    exit();
}
?>
