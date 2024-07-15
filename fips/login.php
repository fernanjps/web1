<?php
session_start();
include 'conexion.php';

$login_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $correo = validar($_POST['correo']);
    $contraseña = validar($_POST['contraseña']);

    // Verificar si el usuario existe en la base de datos
    $sql = "SELECT * FROM usuarios WHERE correo='$correo'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verificar la contraseña
        if (password_verify($contraseña, $row['contraseña'])) {
            $_SESSION['username'] = $row['nombre']; // Usar 'nombre' o 'username' si está disponible
            $_SESSION['user_id'] = $row['id']; // Almacenar el ID del usuario en la sesión

            // Registrar el inicio de sesión en Uhistorial
            $log_sql = "INSERT INTO Uhistorial (user_id) VALUES (?)";
            $log_stmt = $conn->prepare($log_sql);
            $log_stmt->bind_param("i", $row['id']);
            $log_stmt->execute();

            // Login exitoso, redirigir a la página de inicio
            header("Location: inicio.php");
            exit();
        } else {
            $login_error = "Contraseña incorrecta.";
        }
    } else {
        $login_error = "No existe una cuenta con este correo electrónico. Regístrate primero.";
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
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <main>
        <div class="contenedor__todo">
            <!-- Formulario de Login -->
            <div class="contenedor__login">
                <form action="login.php" method="POST" class="formulario__login">
                    <h2>Iniciar Sesión</h2>
                    <?php if (isset($_POST['login'])): ?>
                        <?php if (!empty($login_error)): ?>
                            <p style="color: red;"><?php echo $login_error; ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                    <input type="text" name="correo" placeholder="Correo Electrónico" required>
                    <input type="password" name="contraseña" placeholder="Contraseña" required>
                    <button type="submit" name="login">Entrar</button>
                    <a href="register.php" class="btn__registrarse">Registrarse</a>
                </form>
            </div>
        </div>
    </main>

    <script src="assets/js/script.js"></script>
</body>
</html>
