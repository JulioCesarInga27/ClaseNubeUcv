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
        <h1>Editar Usuario</h1>
        <form action="ActualizarUsuario.php" method="post">
            <input type="hidden" name="original_correo" value="<?php echo $usuario['correo']; ?>">
            <input type="text" name="nombre" placeholder="Nombre" value="<?php echo $usuario['nombre']; ?>">
            <input type="text" name="apellido" placeholder="Apellidos" value="<?php echo $usuario['apellido']; ?>">
            <input type="email" name="correo" placeholder="Correo" value="<?php echo $usuario['correo']; ?>">
            <select name="tipo_usuario" required>
                <option value="" disabled>Tipo de perfil</option>
                <option value="alumno" <?php if ($usuario['tipo_usuario'] == 'alumno') echo 'selected'; ?>>Alumno</option>
                <option value="profesor" <?php if ($usuario['tipo_usuario'] == 'profesor') echo 'selected'; ?>>Profesor</option>
            </select>
            <input type="submit" value="Guardar">
        </form>
    </div>
</body>
</html>
