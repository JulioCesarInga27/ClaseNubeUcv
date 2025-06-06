<?php
include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

// Comprobar si la clase firebaseRDB ya está definida
if (!class_exists('firebaseRDB')) {
    include '../../Scripts/firebaseRDB.php';
}

// Crear una instancia del objeto de Firebase
$rdb = new firebaseRDB($databaseURL);

// Obtener los datos del formulario
$correo = $_POST['correo'];
$cursosSeleccionados = $_POST['cursos'];

// Verificar que se hayan seleccionado cursos
if (empty($cursosSeleccionados)) {
    echo "No se seleccionaron cursos.";
    exit();
}

// Matricular al usuario en los cursos seleccionados
foreach ($cursosSeleccionados as $cursoKey) {
    // Obtener el ID real del curso desde la base de datos
    $retrieveCurso = $rdb->retrieve("/cursos/" . $cursoKey);
    $cursoData = json_decode($retrieveCurso, true);

    // Verificar si se encontró el curso
    if (empty($cursoData)) {
        echo "Curso no encontrado.";
        continue; // Pasar al siguiente curso si no se encontró
    }

    // Obtener el ID real del curso
    $cursoIdReal = $cursoData['id']; // Asumiendo que el ID real está en $cursoData['id']

    // Crear una entrada para el alumno
    $data = [
        'correo' => $correo,
        'id' => $cursoIdReal
    ];

    // Guardar la entrada en la base de datos
    $rdb->insert("/alumnos", $data);
}

echo "Usuario matriculado en los cursos seleccionados.";
?>
