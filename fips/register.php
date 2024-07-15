<?php
include 'conexion.php';

$register_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $nombre = validar($_POST['nombre']);
    $apellido = validar($_POST['apellido']);
    $telefono = validar($_POST['telefono']);
    $correo = validar($_POST['correo']);
    $contraseña = password_hash(validar($_POST['contraseña']), PASSWORD_DEFAULT);

    // Verificar si el correo ya está registrado
    $sql_verificar = "SELECT id FROM usuarios WHERE correo='$correo'";
    $result_verificar = $conn->query($sql_verificar);
    if ($result_verificar->num_rows > 0) {
        $register_error = "Este correo electrónico ya está registrado. Intente con otro.";
    } else {
        // Insertar usuario en la base de datos
        $sql = "INSERT INTO usuarios (nombre, apellido, telefono, correo, contraseña) VALUES ('$nombre', '$apellido', '$telefono', '$correo', '$contraseña')";
        if ($conn->query($sql) === TRUE) {
            // Registro exitoso, redirigir al login
            header("Location: login.php");
            exit();
        } else {
            $register_error = "Error al registrar usuario: " . $conn->error;
        }
    }
}

$conn->close();

function validar($datos) {
    global $conn;
    $datos = trim($datos);
    $datos = stripslashes($datos);
    $datos = htmlspecialchars($datos);
    return $conn->real_escape_string($datos);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/register.css">
</head>
<body>
    <main>
        <div class="contenedor__todo">
            <!-- Formulario de Registro -->
            <div class="contenedor__register">
                <form action="register.php" method="POST" class="formulario__register" id="registerForm">
                    <h2>Registrarse</h2>
                    <?php if (!empty($register_error)): ?>
                        <p style="color: red;"><?php echo $register_error; ?></p>
                    <?php endif; ?>
                    <input type="text" name="nombre" placeholder="Nombre" required>
                    <input type="text" name="apellido" placeholder="Apellido" required>
                    <input type="text" name="telefono" placeholder="Número de Teléfono" required>
                    <input type="email" name="correo" placeholder="Correo Electrónico" required>
                    <input type="password" name="contraseña" placeholder="Contraseña" required>
                    <button type="submit" name="register">Registrarse</button>
                </form>
            </div>
        </div>
    </main>
    <script src="assets/js/register.js"></script>
</body>
</html>
