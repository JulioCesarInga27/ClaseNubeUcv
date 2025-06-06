<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../Scripts/Config.php'; // Incluye la configuración necesaria
include '../../Scripts/firebaseRDB.php'; // Incluye el archivo para interactuar con Firebase

// Verificar si se recibieron los datos necesarios del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sesion_id'])) {
    $sesion_id = $_POST['sesion_id'];
    $curso_id = $_SESSION['cursoid'];
    $duration = $_POST['duration'];
    $width = $_POST['width'];
    $height = $_POST['height'];
    $frameRate = $_POST['frameRate'];
    $audioChannels = $_POST['audioChannels'];
    $audioSampleRate = $_POST['audioSampleRate'];
    $source = $_POST['source'];

    if (isset($_FILES['video'])) {
        $video = $_FILES['video'];
        $target_dir = "../../uploads/videos/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $videoFileName = 'grabacion_' . uniqid() . '.mp4';
        $target_file = $target_dir . $videoFileName;

        if (move_uploaded_file($video['tmp_name'], $target_file)) {
            // Obtener metadatos del video usando FFmpeg
            $ffmpegOutput = shell_exec("ffmpeg -i \"$target_file\" 2>&1");

            // Extraer duración del video
            preg_match('/Duration: (\d+:\d+:\d+\.\d+)/', $ffmpegOutput, $durationMatch);
            $duration = isset($durationMatch[1]) ? $durationMatch[1] : 'N/A';

            // Extraer tasa de fotogramas del video
            preg_match('/(\d+) fps/', $ffmpegOutput, $frameRateMatch);
            $frameRate = isset($frameRateMatch[1]) ? $frameRateMatch[1] : 'N/A';

            // Ruta temporal para el archivo con metadatos incrustados
            $temp_file = $target_dir . 'temp_' . $videoFileName;

            // Generar comando para incrustar metadatos usando FFmpeg
            $ffmpeg_command = "ffmpeg -i \"$target_file\" -c copy ";

            // Añadir duración al comando si está disponible
            if ($duration !== 'N/A') {
                $ffmpeg_command .= "-metadata duration=\"$duration\" ";
            }

            // Añadir tasa de fotogramas al comando si está disponible
            if ($frameRate !== 'N/A') {
                $ffmpeg_command .= "-r $frameRate ";
            }

            // Añadir el archivo de salida temporal
            $ffmpeg_command .= "\"$temp_file\"";

            // Ejecutar el comando FFmpeg para incrustar metadatos
            shell_exec($ffmpeg_command);

            // Mover el archivo temporal al destino final y sobrescribir el original
            if (rename($temp_file, $target_file)) {
                // Actualizar Firebase con el nombre del archivo
                $firebase = new firebaseRDB($databaseURL);
                $data = [
                    "video" => $videoFileName
                ];

                try {
                    $update_path = "sesiones/$curso_id";
                    $update = $firebase->update($update_path, $sesion_id, $data);

                    if ($update) {
                        $output_txt = "../../uploads/Documentos/{$videoFileName}.txt";
                        $input_txt = "../../uploads/videos/{$videoFileName}";
                        $python_path = "C:\\Users\\mange\\AppData\\Local\\Programs\\Python\\Python312\\python.exe";
                        $command = "{$python_path} IA.py {$input_txt} {$output_txt} 2>&1";
                        exec($command, $output, $return_code);

                        echo "<pre>";
                        echo "Salida de IA.py:\n";
                        echo implode("\n", $output) . "\n";
                        echo "Código de retorno: $return_code\n";
                        echo "</pre>";

                        if ($return_code === 0) {
                            $data_txt = ["ia" => basename($output_txt)];
                            $update_txt = $firebase->update($update_path, $sesion_id, $data_txt);

                            if ($update_txt) {
                                echo '<script>alert("Video subido y análisis generado correctamente."); window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
                            } else {
                                echo '<script>alert("Error al guardar el nombre del archivo de texto en Firebase."); window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
                            }
                        } else {
                            echo '<script>alert("Error al generar el análisis: Código de retorno ' . $return_code . '"); window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
                        }
                    } else {
                        echo '<script>alert("Error al actualizar el enlace del video en la base de datos."); window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
                    }
                } catch (Exception $e) {
                    echo '<script>alert("Error al actualizar el enlace del video en la base de datos: ' . $e->getMessage() . '"); window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
                }
            } else {
                echo '<script>alert("Error al incrustar metadatos en el video."); window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
            }
        } else {
            echo '<script>alert("Error al mover el archivo de video al servidor."); window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
        }
    } else {
        echo '<script>alert("No se recibió el archivo de video."); window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
    }
} else {
    echo '<script>alert("No se recibieron datos POST válidos."); window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
}
?>
