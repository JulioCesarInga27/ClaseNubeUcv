<?php
$correo = $_GET['correo'];

include '../../Scripts/Config.php';



// Comprobar si la clase firebaseRDB ya está definida
if (!class_exists('firebaseRDB')) {
    include '../Scripts/firebaseRDB.php';
}

// Crear una instancia del objeto de Firebase
$rdb = new firebaseRDB($databaseURL);

// Recuperar el usuario por su correo
$retrieve = $rdb->retrieve("/usuarios", "correo", "EQUAL", $correo);
$usuarios = json_decode($retrieve, true);

// Verificar si se encontró el usuario
if (empty($usuarios)) {
    echo "Usuario no encontrado.";
    exit();
}

// Extraer el primer usuario encontrado (debería ser único por correo)
$usuario = array_shift($usuarios);

// Recuperar todos los cursos
$retrieveCursos = $rdb->retrieve("/cursos");
$cursos = json_decode($retrieveCursos, true);

// Mostrar el formulario de selección de cursos
echo '<input type="hidden" name="correo" value="' . htmlspecialchars($correo) . '">';
if (!empty($cursos)) {
    echo '<label for="cursos">Seleccione los cursos:</label>';
    echo '<select name="cursos[]" id="cursos" multiple>';
    foreach ($cursos as $cursoId => $curso) {
        echo '<option value="' . htmlspecialchars($cursoId) . '">' . htmlspecialchars($curso['nombre_curso']) . '</option>';
    }
    echo '</select>';
} else {
    echo '<p>No hay cursos disponibles.</p>';
}
?>
