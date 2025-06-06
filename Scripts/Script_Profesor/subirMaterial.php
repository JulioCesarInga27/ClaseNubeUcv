<?php
include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Variable para determinar si se subieron los archivos exitosamente
$subido_exitosamente = false;

// Verificar si se recibieron los datos necesarios del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sesion_id'])) {
    $sesion_id = $_POST['sesion_id'];
    $curso_id = $_SESSION['cursoid']; 
    $materiales = $_FILES['material'];

    // Verificar si se seleccionaron archivos
    if (!empty($materiales['name'][0])) {
        $target_dir = "../../uploads/materiales/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $uploaded_files = []; // Array para almacenar los nombres de los archivos subidos

        // Iterar sobre cada archivo subido
        for ($i = 0; $i < count($materiales['name']); $i++) {
            $target_file = $target_dir . basename($materiales["name"][$i]);

            // Mover el archivo subido a la carpeta de destino
            if (move_uploaded_file($materiales["tmp_name"][$i], $target_file)) {
                $uploaded_files[] = basename($materiales["name"][$i]); // Agregar nombre del archivo a la lista de subidos
            } else {
                echo "Error al subir uno o más archivos.";
                exit; // Terminar el script si falla la carga de algún archivo
            }
        }

        // Guardar los enlaces de los materiales en Firebase
        $firebase = new firebaseRDB($databaseURL);
        $data = [
            "material" => $uploaded_files // Guardar un array con los nombres de los archivos subidos
        ];

        try {
            $update_path = "sesiones/$curso_id"; // Ruta específica para los materiales
            $update = $firebase->update($update_path, $sesion_id, $data); // Actualizar en Firebase

            if ($update) {
                $subido_exitosamente = true;
            } else {
                echo "Error al actualizar los enlaces de los materiales en la base de datos.";
            }
        } catch (Exception $e) {
            echo "Error al actualizar los enlaces de los materiales en la base de datos: " . $e->getMessage();
        }
    } else {
        echo "No se seleccionó ningún archivo.";
    }
} else {
    echo "Error: Datos incompletos para subir los materiales.";
}

// Redireccionar a la página anterior con alerta dependiendo del resultado
if ($subido_exitosamente) {
    echo "<script>alert('Materiales subidos y enlaces guardados correctamente.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
} else {
    echo "<script>alert('Error al subir los materiales.'); window.location.href='{$_SERVER['HTTP_REFERER']}';</script>";
}
?>
