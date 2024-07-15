<?php
// Conexión a la base de datos (incluye tu archivo de conexión aquí)
include 'conexion.php';

// Variables para almacenar las respuestas correctas y la calificación
$respuestas_correctas = array(
    'pregunta1' => 'B',
    'pregunta2' => 'C',
    'pregunta3' => 'A',
    'pregunta4' => 'A',
    'pregunta5' => 'D'
);
$calificacion = 0;
$mensaje = '';
$nombre_usuario = ''; // Aquí deberías obtener el nombre del usuario, por ejemplo, desde una sesión

// Obtener las respuestas del último formulario almacenado en la base de datos
$sql_formulario = "SELECT id, pregunta1, pregunta2, pregunta3, pregunta4, pregunta5 FROM formulario ORDER BY id DESC LIMIT 1";
$resultado_formulario = $conn->query($sql_formulario);

if ($resultado_formulario->num_rows > 0) {
    $row_formulario = $resultado_formulario->fetch_assoc();
    $id_formulario = $row_formulario['id'];

    // Comparar respuestas con las correctas y calcular la calificación
    foreach ($respuestas_correctas as $pregunta => $respuesta_correcta) {
        if ($row_formulario[$pregunta] === $respuesta_correcta) {
            $calificacion += 2; // Suma 2 puntos por cada respuesta correcta
        }
    }

    // Actualizar la calificación en la tabla 'calificaciones'
    $sql_actualizar = "INSERT INTO calificaciones (id_formulario, calificacion) VALUES ($id_formulario, $calificacion) ON DUPLICATE KEY UPDATE calificacion = VALUES(calificacion)";
    
    if ($conn->query($sql_actualizar) === TRUE) {
        $mensaje = "Calificación actualizada correctamente en la base de datos.";
    } else {
        $mensaje = "Error al actualizar la calificación: " . $conn->error;
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
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificación del Formulario</title>
    <link rel="stylesheet" href="css/calificacion.css"> <!-- Ajusta la ruta según donde tengas tus estilos CSS -->
</head>

<body>
    <div class="container">
        <h2>Calificación del Formulario de Plantas y Jardines</h2>
        <div class="mensaje"><?php echo $mensaje_felicitacion; ?></div>
        <div class="calificacion">Tu calificación es: <?php echo $calificacion; ?></div>
        <?php if ($puede_descargar_certificado): ?>
            <div class="certificado"><?php echo generarCertificado(); ?></div>
        <?php endif; ?>
        <a href="inicio.php" class="btn btn-primary">Volver a Inicio</a>
    </div>
</body>

</html>

<?php
// Cerrar la conexión a la base de datos al finalizar
$conn->close();
?>
