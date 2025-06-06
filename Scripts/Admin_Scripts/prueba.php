<?php
// Comprueba si la sesión no está iniciada antes de iniciarla
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

$rdb = new firebaseRDB($databaseURL);

// Obtener todos los cursos
$retrieveCursos = $rdb->retrieve("/cursos");
$cursosData = json_decode($retrieveCursos, true);

if (!empty($cursosData)) {
    foreach ($cursosData as $curso) {
        $imagen = isset($curso['imagen']) ? $curso['imagen'] : null;
        $cursoId = $curso['id']; // Obtener el ID del curso
        $_SESSION['cursoid'] = $cursoId; // Guardar el ID del curso en sesión
        if ($imagen) {
            echo '
            <div class="col-md-4">
                <div class="card mb-4" style="width: 18rem;">
                    <img class="card-img-top" src="' . $imagen . '" alt="Imagen del curso">
                    <div class="card-body">
                        <h5 class="card-title">' . $curso['nombre_curso'] . '</h5>
                        <p class="card-text">' . $curso['descripcion'] . '</p>
                        <a href="GestionRecursos.php?curso_id=' . $cursoId . '" class="btn btn-primary">Ir a Curso</a>
                    </div>
                </div>
            </div>';
        } else {
            // Generar un color aleatorio
            $randomColor = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
            echo '
            <div class="col-md-4">
                <div class="card mb-4" style="width: 18rem;">
                    <div class="card-img-top" style="background-color:' . $randomColor . '; height: 180px;"></div>
                    <div class="card-body">
                        <h5 class="card-title">' . $curso['nombre_curso'] . '</h5>
                        <p class="card-text">' . $curso['descripcion'] . '</p>
                        <a href="GestionRecursos.php?curso_id=' . $cursoId . '" class="btn btn-primary">Ir a Curso</a>
                    </div>
                </div>
            </div>';
        }
    }
} else {
    echo "Aún no tienes cursos asignados.";
}
?>
