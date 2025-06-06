<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../Scripts/Config.php';
include '../Scripts/firebaseRDB.php';

$rdb = new firebaseRDB($databaseURL);

// Obtener el ID del curso de la sesión si está presente en la URL
if (isset($_GET['curso_id'])) {
    $_SESSION['cursoid'] = $_GET['curso_id'];
} else {
    die("Error: No se proporcionó el ID del curso.");
}

$curso_id = $_SESSION['cursoid'];

// Obtener datos del curso desde Firebase
$retrieveCursos = $rdb->retrieve("/cursos", "id", "EQUAL", $curso_id);
$cursosData = json_decode($retrieveCursos, true);

$nombreCurso = 'Curso no encontrado';
$descripcionCurso = 'Descripción no encontrada';

if (is_array($cursosData) && !empty($cursosData)) {
    foreach ($cursosData as $curso) {
        $nombreCurso = htmlspecialchars($curso['nombre_curso']);
        $descripcionCurso = htmlspecialchars($curso['descripcion']);
    }
}

// Obtener sesiones del curso
$retrieveSesiones = $rdb->retrieve("/sesiones/$curso_id");
$sesionesData = json_decode($retrieveSesiones, true);

if (!is_array($sesionesData)) {
    $sesionesData = [];
}

// Función para generar las pestañas
function generarPestanas($sesiones) {
    if ($sesiones && is_array($sesiones) && !empty($sesiones)) {
        foreach ($sesiones as $index => $sesion) {
            $activeClass = $index == 0 ? 'active' : '';
            $nombreSesion = isset($sesion['nombre']) ? htmlspecialchars($sesion['nombre']) : 'Bienvenido';
            $idSesion = isset($sesion['id']) ? htmlspecialchars($sesion['id']) : '';
            echo "<li class='$activeClass' data-tab='tab-$index' data-sesion-id='$idSesion'>$nombreSesion";
            
            echo "</li>";
        }
    } else {
        echo "<li class='active'>Bienvenido</li>";
    }
}

// Función para generar el contenido de las pestañas
function generarContenido($sesiones) {
    if ($sesiones && is_array($sesiones) && !empty($sesiones)) {
        foreach ($sesiones as $index => $sesion) {
            $activeClass = $index == 0 ? 'active' : '';
            echo "<div id='tab-$index' class='tab-pane $activeClass'>";
            echo "<h3>" . (isset($sesion['nombre']) ? htmlspecialchars($sesion['nombre']) : 'Aquí está el contenido del curso, navega entre las sesiones para el contenido') . "</h3>";

            echo "<h2>Video: </h2>";
            
// Verificar si hay video y sesión id definidos
        if (!empty($sesion['video']) && isset($sesion['id'])) {
            $video_url = htmlspecialchars($sesion['video']);
            $sesionid = htmlspecialchars($sesion['id']);

    // Verificar si existe video_estado y está disponible
    if (!empty($sesion['video']) && isset($sesion['id'])) {
        $video_url = htmlspecialchars($sesion['video']);
        $sesionid = htmlspecialchars($sesion['id']);
    
        // Verificar si existe video_estado y está configurado como "disponible"
        if (isset($sesion['video_estado']) && $sesion['video_estado'] === "Disponible") {
            // Construir la ruta completa al video
            $video_path = "../uploads/videos/" . $video_url; // Asegúrate de que esta ruta sea correcta
            // Mostrar el reproductor de video solo si hay una URL válida
            if (!empty($video_url)) {
                echo "<div class='video-container'><video src='" . $video_path . "' controls style='width: 100%; height: auto;'></video></div>";
            }
        }
    }
}


            echo "<h2>Análisis del Video por la IA: </h2>";

            if (isset($sesion['ia']) && isset($sesion['video_estado']) && $sesion['video_estado'] === "Disponible") {
                $nombreMaterial = $sesion['ia']; // Nombre del archivo PDF
                $urlMaterial = "../uploads/Documentos/" . htmlspecialchars($nombreMaterial); // URL del PDF
                $extensionMaterial = pathinfo($nombreMaterial, PATHINFO_EXTENSION);
            
                // Determinar el icono según la extensión del archivo
                if (in_array($extensionMaterial, ['pdf'])) {
                    $icono = "../Icon/pdf.jpg";
                } else {
                    $icono = "../Icon/defecto.jpg"; // Icono genérico por defecto
                }
            
                // Mostrar la tarjeta del PDF con los estilos
                echo "<div class='material-card'>";
                echo "<a href='$urlMaterial' download>";
                echo "<img src='$icono' alt='$extensionMaterial'>";
                echo "<div class='material-name'>$nombreMaterial</div>";
                echo "</a>";
                echo "</div>";
            } else {
                echo "<p class='no-materials'>No se encontró el archivo txt generado por la IA para esta sesión.</p>";
            }
            



            echo "<h2>Materiales: </h2>";

            // Mostrar los materiales
            if (isset($sesion['material']) && is_array($sesion['material']) && !empty($sesion['material'])) {
                 echo "<div class='materiales-container'>";
            foreach ($sesion['material'] as $material) {
            $nombreMaterial = basename($material); // Obtener el nombre del archivo
            $urlMaterial = "../uploads/materiales/" . htmlspecialchars($material); // URL del material
            $extensionMaterial = pathinfo($nombreMaterial, PATHINFO_EXTENSION);
            $icono = "icono-generico.png"; // Icono genérico, puedes ajustar según el tipo de archivo

            // Determinar el icono según la extensión del archivo
            if (in_array($extensionMaterial, ['pdf'])) {
            $icono = "../Icon/pdf.jpg";
            } elseif (in_array($extensionMaterial, ['ppt', 'pptx'])) {
            $icono = "../Icon/ppt.jpg";
            } elseif (in_array($extensionMaterial, ['jpg', 'jpeg', 'png'])) {
            $icono = "../Icon/png.jpg";
            } elseif (in_array($extensionMaterial, ['doc', 'docx'])) {
            $icono = "../Icon/word.jpg";
            } elseif (in_array($extensionMaterial, ['xls', 'xlsx'])) {
            $icono = "../Icon/excel.jpg";
            } else {
            $icono = "../Icon/defecto.jpg"; // Icono genérico por defecto
            }

            // Mostrar la tarjeta del material con los estilos
            echo "<div class='material-card'>";
            echo "<a href='$urlMaterial' download>";
            echo "<img src='$icono' alt='$extensionMaterial'>";
            echo "<div class='material-name'>$nombreMaterial</div>";
            echo "</a>";
            echo "</div>";
            }
            echo "</div>";
            } else {
            echo "<p class='no-materials'>No hay materiales disponibles para esta sesión.</p>";
            }



            echo "</div>";
        }
    } else {
        echo "<div class='tab-pane active'>Aquí podrás ver el contenido de tus sesiones.</div>";
    }
}

ob_start(); // Iniciar el almacenamiento en búfer de salida
?>

<ul class="tab-header">
    <?php generarPestanas($sesionesData); ?>
</ul>
<div class="tab-content">
    <?php generarContenido($sesionesData); ?>
</div>

<?php
$contenido_tabs = ob_get_clean(); // Obtener el contenido del búfer y limpiarlo

echo "
    <h1>Detalles del Curso</h1>
    <div class='curso'>
        <h2 id='nombreCurso'>" . $nombreCurso . "</h2>
        <p id='descripcionCurso'>" . $descripcionCurso . "</p>
        <div class='unidades'>
            <h3>Unidades</h3>
            <div class='tabs'>
                <!-- Mostrar pestañas y contenido dinámico -->
                $contenido_tabs
            </div>
        </div>
    </div>
";
?>
