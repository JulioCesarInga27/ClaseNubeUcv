<?php 
$id = $_GET['id'];
include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

// Crear una instancia del objeto de Firebase
$rdb = new firebaseRDB($databaseURL);

// Recuperar el curso por su ID
$retrieve = $rdb->retrieve("/cursos", "id", "EQUAL", $id);

$curso = json_decode($retrieve, true);

// Verificar si se encontró el curso
if (empty($curso)) {
    echo "Curso no encontrado.";
    exit();
}
$curso = array_shift($curso);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Styles/Modificar.css">
    <title>Editar Curso</title>
</head>
<body>
    <div class="contenedor">
        <h1>Editar Curso</h1>
        <form action="ActualizarCurso.php" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="text" name="nombre_curso" placeholder="Nombre del Curso" value="<?php echo $curso['nombre_curso']; ?>">
            <input type="text" name="descripcion" placeholder="Descripción" value="<?php echo $curso['descripcion']; ?>">
            <input type="text" name="profesor" placeholder="Profesor" value="<?php echo $curso['profesor']; ?>">
            <input type="submit" value="Guardar">
        </form>
    </div>
</body>
</html>

