<?php 
include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

$rdb = new firebaseRDB($databaseURL);
$retrieve = $rdb->retrieve("/cursos");
$cursos = json_decode($retrieve, true);
?>

<table class="table table-dark">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Nombre del Curso</th>
            <th scope="col">Descripción</th>
            <th scope="col">Profesor</th>
            <th scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cursos as $key => $curso) { ?>
            <tr>
                <td><?php echo isset($curso['id']) ? $curso['id'] : 'ID not set'; ?></td>
                <td><?php echo isset($curso['nombre_curso']) ? $curso['nombre_curso'] : 'Nombre no disponible'; ?></td>
                <td><?php echo isset($curso['descripcion']) ? $curso['descripcion'] : 'Descripción no disponible'; ?></td>
                <td><?php echo isset($curso['profesor']) ? $curso['profesor'] : 'Profesor no disponible'; ?></td>
                <td>
                    <a href="../../Scripts/Admin_Scripts/ModificarCUR.php?id=<?php echo isset($curso['id']) ? $curso['id'] : '#'; ?>">
                        <i class="fa-sharp fa-solid fa-pen-to-square"></i>
                    </a>
                    <a href="../../Scripts/Admin_Scripts/EliminarCUR.php?id=<?php echo isset($curso['id']) ? $curso['id'] : '#'; ?>">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
