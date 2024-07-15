<?php
// Iniciar sesión para obtener el nombre del usuario si es necesario
session_start();

// Conexión a la base de datos (incluye tu archivo de conexión aquí)
include 'conexion.php';

// Variables para almacenar las respuestas correctas y la calificación
$respuestas_correctas = array(
    'pregunta1' => 'B',
    'pregunta2' => 'C',
    'pregunta3' => 'A',
    'pregunta4' => 'A',
    'pregunta5' => 'D',
    'pregunta6' => 'A',
    'pregunta7' => 'C',
    'pregunta8' => 'B'
);
$calificacion = 0;
$mensaje = '';
$nombre_usuario = isset($_SESSION['nombre_usuario']) ? $_SESSION['nombre_usuario'] : ''; // Obtener el nombre del usuario desde la sesión

// Obtener las respuestas del último formulario almacenado en la base de datos
$sql_formulario = "SELECT id, pregunta1, pregunta2, pregunta3, pregunta4, pregunta5, pregunta6, pregunta7, pregunta8 FROM formulario2 ORDER BY id DESC LIMIT 1";
$resultado_formulario = $conn->query($sql_formulario);

if ($resultado_formulario->num_rows > 0) {
    $row_formulario = $resultado_formulario->fetch_assoc();
    $id_formulario = $row_formulario['id'];

    // Comparar respuestas con las correctas y calcular la calificación
    foreach ($respuestas_correctas as $pregunta => $respuesta_correcta) {
        if ($row_formulario[$pregunta] === $respuesta_correcta) {
            $calificacion++;
        }
    }

    // Insertar la calificación en la tabla calificaciones2
    $sql_calificacion = "INSERT INTO calificaciones2 (id_formulario2, calificacion) VALUES ('$id_formulario', '$calificacion')";
    if ($conn->query($sql_calificacion) === TRUE) {
        $mensaje = "Calificación registrada correctamente. Tu calificación es $calificacion.";
    } else {
        $mensaje = "Error al registrar la calificación: " . $conn->error;
    }
} else {
    $mensaje = "No se encontraron respuestas del formulario.";
}

// Mensaje de felicitación si la calificación es mayor o igual a 7
if ($calificacion >= 7) {
    $mensaje_felicitacion = "¡Felicidades, $nombre_usuario! Has obtenido una calificación de $calificacion. ¡Eres un experto en plantas y jardines!";
    $puede_descargar_certificado = true;
} else {
    $mensaje_felicitacion = "Tu calificación es de $calificacion. Sigue aprendiendo sobre plantas y jardines para mejorar tu conocimiento.";
    $puede_descargar_certificado = false;
}

// Función para generar un certificado (en este caso, simplemente se muestra un enlace)
function generarCertificado() {
    // Aquí podrías generar un archivo PDF o cualquier otro formato de certificado
    return '<a href="assets/images/certificado.pdf">Descargar Certificado</a>';
}

// Cerrar la conexión a la base de datos al finalizar
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificación</title>
    <link rel="stylesheet" href="css/calificacion.css">
</head>

<body>
    <div class="container">
        <h2>Resultado de la Calificación</h2>
        <p><?php echo $mensaje; ?></p>
        <p><?php echo $mensaje_felicitacion; ?></p>
        <?php if ($puede_descargar_certificado): ?>
            <div class="certificado"><?php echo generarCertificado(); ?></div>
        <?php endif; ?>
        <a href="inicio.php" class="btn btn-primary">Volver al Inicio</a>
    </div>
</body>

</html>
