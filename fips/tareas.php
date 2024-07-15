<?php
include 'conexion.php';

$mensaje = '';
$error = '';

// Función para obtener todas las tareas de la base de datos
function obtenerTareas($conn) {
    $sql = "SELECT * FROM tareas";
    $result = $conn->query($sql);
    return $result;
}

// Verificar si se ha enviado el formulario para agregar una tarea
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_tarea'])) {
    $titulo = validar($_POST['titulo']);
    $descripcion = validar($_POST['descripcion']);
    $fecha_limite = $_POST['fecha_limite'];

    $sql = "INSERT INTO tareas (titulo, descripcion, fecha_limite) VALUES ('$titulo', '$descripcion', '$fecha_limite')";

    if ($conn->query($sql) === TRUE) {
        // Tarea agregada con éxito
        $mensaje = "Tarea agregada correctamente.";
    } else {
        // Error al agregar tarea
        $error = "Error al agregar la tarea: " . $conn->error;
    }
}

// Verificar si se ha enviado el formulario para editar una tarea
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar_tarea'])) {
    $id_tarea = $_POST['id_tarea'];
    $titulo = validar($_POST['titulo']);
    $descripcion = validar($_POST['descripcion']);
    $fecha_limite = $_POST['fecha_limite'];

    $sql = "UPDATE tareas SET titulo='$titulo', descripcion='$descripcion', fecha_limite='$fecha_limite' WHERE id=$id_tarea";

    if ($conn->query($sql) === TRUE) {
        // Tarea editada con éxito
        $mensaje = "Tarea editada correctamente.";
    } else {
        // Error al editar tarea
        $error = "Error al editar la tarea: " . $conn->error;
    }
}

// Verificar si se ha enviado el formulario para eliminar una tarea
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_tarea'])) {
    $id_tarea = $_POST['id_tarea'];

    $sql = "DELETE FROM tareas WHERE id=$id_tarea";

    if ($conn->query($sql) === TRUE) {
        // Tarea eliminada con éxito
        $mensaje = "Tarea eliminada correctamente.";
    } else {
        // Error al eliminar tarea
        $error = "Error al eliminar la tarea: " . $conn->error;
    }
}

// Función para limpiar y validar datos del formulario
function validar($datos) {
    global $conn; // Acceder a la conexión global
    $datos = trim($datos);
    $datos = stripslashes($datos);
    $datos = htmlspecialchars($datos);
    return $conn->real_escape_string($datos); // Escapar los datos para evitar inyección SQL
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tareas</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/tareas.css"> <!-- Aquí vincula tu archivo CSS personalizado -->
     <!-- Aquí vincula tu archivo JavaScript -->
</head>

<body>
    <div class="container">
        <h2>Gestión de Tareas</h2>

        <!-- Formulario para agregar tarea -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="form-group" id="form-agregar">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="4" required></textarea>

            <label for="fecha_limite">Fecha Límite:</label>
            <input type="date" id="fecha_limite" name="fecha_limite" required>

            <button type="submit" class="btn btn-primary" name="agregar_tarea">Agregar Tarea</button>
        </form>

        <!-- Mostrar mensajes de éxito o error al agregar, editar o eliminar tarea -->
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-success"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Obtener y mostrar todas las tareas existentes -->
        <?php
        $result = obtenerTareas($conn);

        if ($result->num_rows > 0) {
            echo '<h3>Listado de Tareas</h3>';
            echo '<table class="table">';
            echo '<thead><tr><th>Título</th><th>Descripción</th><th>Fecha Límite</th></tr></thead>';
            echo '<tbody>';
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td><a href="formulario.php?id=' . (isset($row['id']) ? htmlspecialchars($row['id']) : '') . '">' . (isset($row['titulo']) ? htmlspecialchars($row['titulo']) : '') . '</a></td>';
                echo '<td>' . (isset($row['descripcion']) ? htmlspecialchars($row['descripcion']) : '') . '</td>';
                echo '<td>' . (isset($row['fecha_limite']) ? htmlspecialchars($row['fecha_limite']) : '') . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '<p>No hay tareas registradas.</p>';
        }

        $conn->close();
        ?>

        <a href="inicio.php" class="btn btn-primary">Ir a Inicio</a>
        <script src="js/tareas.js"></script>
    </div>
</body>

</html>
