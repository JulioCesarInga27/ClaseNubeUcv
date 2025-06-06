<?php
// Comprueba si la sesión no está iniciada antes de iniciarla
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../Scripts/Config.php';
include '../Scripts/firebaseRDB.php';

$rdb = new firebaseRDB($databaseURL);

// Obtener el correo del usuario de la sesión
$userId = $_SESSION['user_id']; // Aquí está el correo

// Obtener los alumnos cuyo correo coincida con el correo de la sesión
$retrieveAlumnos = $rdb->retrieve("/alumnos", "correo", "EQUAL", $userId);
$alumnosData = json_decode($retrieveAlumnos, true);

$cursos = [];
if (!empty($alumnosData)) {
    foreach ($alumnosData as $alumno) {
        $alumnoId = $alumno['id'];
        
        // Obtener los cursos que correspondan al ID del alumno
        $retrieveCursos = $rdb->retrieve("/cursos", "id", "EQUAL", $alumnoId);
        $cursosData = json_decode($retrieveCursos, true);
        
        if (!empty($cursosData)) {
            $cursos = array_merge($cursos, $cursosData);
        }
    }
}

if (!empty($cursos)) {
    foreach ($cursos as $curso) {
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
                        <a href="../html/CursoOp.php?curso_id=' . $cursoId . '" class="btn btn-primary">Ir a Curso</a>
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
                        <a href="../html/CursoOp.php?curso_id=' . $cursoId . '" class="btn btn-primary">Ir a Curso</a>
                    </div>
                </div>
            </div>';
        }
    }
} else {
    echo "Aun No tienes Cursos Asignados.";
}
?>
