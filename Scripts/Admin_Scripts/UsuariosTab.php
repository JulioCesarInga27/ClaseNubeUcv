<?php 
include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

// Crear una instancia del objeto de Firebase
$rdb = new firebaseRDB($databaseURL);

// Recuperar todos los usuarios
$retrieve = $rdb->retrieve("/usuarios");
$usuarios = json_decode($retrieve, true);
?>

<table class="table table-dark">
    <thead>
        <tr>
            <th scope="col">ID / Correo</th>
            <th scope="col">Apellidos</th>
            <th scope="col">Nombres</th>
            <th scope="col">Tipo</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $key => $usuario) { ?>
            <tr>
                <td><?php echo $usuario['correo']; ?></td>
                <td><?php echo $usuario['apellido']; ?></td>
                <td><?php echo $usuario['nombre']; ?></td>
                <td><?php echo $usuario['tipo_usuario']; ?></td>
                <td>

                
                    <a href="../../Scripts/Admin_Scripts/MatricularUSU.php?correo=<?php echo $usuario['correo']; ?>"><i class="fa-solid fa-book" style="color: #63E6BE;"></i></a>

                    
                    <a href="../../Scripts/Admin_Scripts/ModificarUSU.php?correo=<?php echo $usuario['correo']; ?>"><i class="fa-sharp fa-solid fa-pen-to-square"></i></a>
                    
                    <a href="../../Scripts/Admin_Scripts/EliminarUSU.php?correo=<?php echo $usuario['correo']; ?>"><i class="fa-solid fa-trash"></i></a>
                    

                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>