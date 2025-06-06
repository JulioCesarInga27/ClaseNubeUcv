<?php 
include 'Config.php';
include 'firebaseRDB.php';

$EMAIL = $_POST['email'];
$PASS = $_POST['contra'];

try {
    $rdb = new firebaseRDB($databaseURL);
    
    $retrieve = $rdb->retrieve("/usuarios", "correo", "EQUAL", $EMAIL);
    $data = json_decode($retrieve, true);

    if (!empty($data)) {
        $user = reset($data); // Obtener el primer (y único) usuario en el resultado

        if (is_array($user) && isset($user['contraseña']) && $user['contraseña'] === $PASS) {
            session_start();
            $_SESSION['user_id'] = $user['correo']; // Usar correo como user_id
            $_SESSION['authenticated'] = true;

            if ($user['tipo_usuario'] === 'admin') {
                header("Location: ../html/Admin/index_admin.php");
            } elseif ($user['tipo_usuario'] === 'profesor') {
                header("Location: ../html/Profesor/prueba.php");
            } else {
                header("Location: ../html/index.php");
            }
            exit();
        } else {
            echo "<script>alert('Usuario o contraseña incorrectos');</script>";
            echo "<script>window.location.href = '../html/login.html';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Usuario no encontrado');</script>";
        echo "<script>window.location.href = '../html/login.html';</script>";
        exit();
    }
} catch (Exception $e) {
    echo "Error en la consulta: " . $e->getMessage();
}
?>
