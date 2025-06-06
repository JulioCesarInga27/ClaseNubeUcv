<?php
include 'Config.php';
include 'firebaseRDB.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$correo = $_SESSION['correotemp'];

$nueva_contrasena = $_POST['nueva_contrasena'];
$confirmacion = $_POST['confirmar_contrasena'];

if ($_SESSION['token_validado'] === $_SESSION['token_generado']) {
    if ($nueva_contrasena == $confirmacion) {
        try {
            $rdb = new firebaseRDB($databaseURL);

            // Obtener el usuario por correo
            $retrieve = $rdb->retrieve("/usuarios", "correo", "EQUAL", $correo);
            $userData = json_decode($retrieve, true);

            if (!empty($userData)) {
                foreach ($userData as $key => $value) {
                    if ($value['correo'] === $correo) {
                        $userId = $key;
                        // Actualizar la contraseña del usuario
                        $userData[$userId]['contraseña'] = $nueva_contrasena;
                        break;
                    }
                }

                // Actualizar el usuario con la nueva contraseña
                $update = $rdb->update("/usuarios", $userId, $userData[$userId]);

                if ($update) {
                    echo "<script>alert('Contraseña Cambiada');</script>";                  
                    echo "<script>window.location.href = '../html/login.html';</script>";
                } else {
                    echo "<script>alert('Fracazo');</script>";                   
                    echo "<script>window.location.href = '../html/login.html';</script>";
                }
            } else {
                echo "Usuario no encontrado";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Contraseñas distintas.";
    }
} else {
    echo "Token inválido";
}
?>