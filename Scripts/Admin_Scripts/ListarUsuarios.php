<?php 
include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

// Crear una instancia del objeto de Firebase
$rdb = new firebaseRDB($databaseURL);

// Recuperar todos los usuarios
$retrieve = $rdb->retrieve("/usuarios");
$usuarios = json_decode($retrieve, true);

// Si se envió un formulario para actualizar el usuario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $tipo_usuario = $_POST['tipo_usuario'];

    // Actualizar los datos del usuario en la base de datos
    $rdb->update("/usuarios/$correo", [
        'nombre' => $nombre,
        'apellido' => $apellido,
        'correo' => $correo,
        'tipo_usuario' => $tipo_usuario
    ]);
}

?>

<table class="table table-dark">
    <thead>
        <tr>
            <th scope="col">ID / Correo</th>
            <th scope="col">Apellidos</th>
            <th scope="col">Nombres</th>
            <th scope="col">Tipo</th>
            <th scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $key => $usuario) { ?>
            <tr>
                <form method="post">
                    <td><input type="hidden" name="id" value="<?php echo $key; ?>"><?php echo $usuario['correo']; ?></td>
                    <td><input type="text" name="apellido" value="<?php echo $usuario['apellido']; ?>"></td>
                    <td><input type="text" name="nombre" value="<?php echo $usuario['nombre']; ?>"></td>
                    <td>
                        <select name="tipo_usuario">
                            <option value="admin" <?php echo ($usuario['tipo_usuario'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="profesor" <?php echo ($usuario['tipo_usuario'] === 'profesor') ? 'selected' : ''; ?>>Profesor</option>
                            <option value="alumno" <?php echo ($usuario['tipo_usuario'] === 'alumno') ? 'selected' : ''; ?>>Alumno</option>
                        </select>
                    </td>
                    <td>
                        <button type="submit">Guardar Cambios</button>
                    </td>
                </form>
            </tr>
        <?php } ?>
    </tbody>
</table>