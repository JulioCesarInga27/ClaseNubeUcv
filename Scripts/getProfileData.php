<?php
include 'Config.php';
include 'firebaseRDB.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$email = $_SESSION['user_id'];

try {
    $rdb = new firebaseRDB($databaseURL);
    $retrieve = $rdb->retrieve("/usuarios", "correo", "EQUAL", $email);
    $data = json_decode($retrieve, true);
    
    if (!empty($data)) {
        $user = reset($data);

        echo '<div class="perfil-container">';
        echo '<img src="../uploads/' . ($user['profile_picture'] ?? 'default.png') . '" alt="Foto de Perfil" class="foto-perfil">';
        echo '<form action="../Scripts/updateProfile.php" method="POST" enctype="multipart/form-data">';
        echo '<label for="nombre">Nombre:</label>';
        echo '<input type="text" id="nombre" name="nombre" value="' . $user['nombre'] . '">';
        echo '<label for="apellido">Apellido:</label>';
        echo '<input type="text" id="apellido" name="apellido" value="' . $user['apellido'] . '">';
        echo '<label for="correo">Correo Electrónico:</label>';
        echo '<input type="email" id="correo" name="correo" value="' . $user['correo'] . '" readonly>';
        echo '<label for="telefono">Teléfono:</label>';
        echo '<input type="text" id="telefono" name="telefono" value="' . ($user['telefono'] ?? '') . '">';
        echo '<label for="descripcion">Descripción:</label>';
        echo '<textarea id="descripcion" name="descripcion">' . ($user['descripcion'] ?? '') . '</textarea>';
        echo '<label for="profile_picture">Cambiar Foto de Perfil:</label>';
        echo '<input type="file" id="profile_picture" name="profile_picture">';
        echo '<button type="submit">Actualizar Perfil</button>';
        echo '</form>';
        echo '<form action="../Scripts/addFriend.php" method="POST">';
        echo '<label for="friend_email">Agregar Amigo por Correo:</label>';
        echo '<input type="email" id="friend_email" name="friend_email" placeholder="Correo del Amigo">';
        echo '<button type="submit">Agregar Amigo</button>';
        echo '</form>';
        echo '</div>';
    } else {
        echo '<p>No se encontraron datos del usuario.</p>';
    }
} catch (Exception $e) {
    echo "Error al obtener los datos: " . $e->getMessage();
}
?>
