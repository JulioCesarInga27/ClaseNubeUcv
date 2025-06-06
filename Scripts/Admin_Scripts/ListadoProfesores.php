<?php 
include '../../Scripts/Config.php';
include '../../Scripts/firebaseRDB.php';

// Crear una instancia del objeto de Firebase
$rdb = new firebaseRDB($databaseURL);

// Recuperar todos los usuarios tipo profesor
$retrieve = $rdb->retrieve("/usuarios", "tipo_usuario", "EQUAL", "profesor");
$profesores = json_decode($retrieve, true);
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
        <?php foreach ($profesores as $key => $profesor) { ?>
            <tr>
                <td><?php echo $profesor['correo']; ?></td>
                <td><?php echo $profesor['apellido']; ?></td>
                <td><?php echo $profesor['nombre']; ?></td>
                <td><?php echo $profesor['tipo_usuario']; ?></td>
                <td>
                    <!-- Aquí van los enlaces para modificar o eliminar al profesor -->
                    <a href="#" class="copy-icon" data-correo="<?php echo $profesor['correo']; ?>"><i class="fa-duotone fa-copy" style="--fa-primary-color: #25db00; --fa-secondary-color: #25db00;"></i>Copiar</a>

            <script>
            // Agregar un listener de clic a todos los iconos de copiar
             var copyIcons = document.querySelectorAll('.copy-icon');
              copyIcons.forEach(icon => {
                icon.addEventListener('click', function(event) {
              // Prevenir el comportamiento predeterminado del enlace
            event.preventDefault();
            // Obtener el correo del atributo de datos
            var correo = this.getAttribute('data-correo');
            // Copiar el correo al portapapeles
            navigator.clipboard.writeText(correo).then(function() {
                // Mensaje de éxito si se copió correctamente
                alert('Correo copiado al portapapeles: ' + correo);
            }, function() {
                // Mensaje de error si ocurrió un problema al copiar
                alert('Error al copiar el correo');
            });
        });
    });
</script>

                    
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
