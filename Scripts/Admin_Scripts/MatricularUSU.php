<?php 
$correo = $_GET['correo'];
include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Styles/Modificar.css">
    <title>Editar Usuario</title>
</head>
<body>
    <div class="contenedor">
        <h1>Matricula - Seleccione curso</h1>
        <form action="MatricularUsuario.php" method="post">
            <?php include '../Admin_Scripts/listarCursos.php'; ?>
        <input type="submit" value="Guardar">
        </form>
    </div>
</body>
</html>