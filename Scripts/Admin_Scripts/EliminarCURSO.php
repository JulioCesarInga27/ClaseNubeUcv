<?php
include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

// Crear una instancia del objeto de Firebase
$rdb = new firebaseRDB($databaseURL);

session_start();

if(isset($_SESSION['id2'])) {
    $id = $_SESSION['id2'];
    
    $rdb = new firebaseRDB($databaseURL);
    
    $retrieve = $rdb->retrieve("/cursos", "id", "EQUAL", $id);
    $cursoData = json_decode($retrieve, true);
    
    if (!empty($cursoData)) {
        foreach ($cursoData as $cursoId => $curso) {
            $rdb->delete("/cursos", $cursoId); 
        }
    }

    // Eliminar la variable de sesión después de la operación
    unset($_SESSION['id2']);

    header("Location: ../../html/Admin/Cursos.php");
    exit();
} else {
    header("Location: ../../html/Admin/Cursos.php");
    exit();
}
?>

