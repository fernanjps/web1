<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Verificar si la tabla 'usuarios' existe en la base de datos
$table_check_query = "SHOW TABLES LIKE 'usuarios'";
$table_check_result = $conn->query($table_check_query);

if ($table_check_result->num_rows == 0) {
    die("La tabla 'usuarios' no existe en la base de datos.");
}

// Verificar si la tabla 'perfiles' existe en la base de datos
$table_check_query = "SHOW TABLES LIKE 'perfiles'";
$table_check_result = $conn->query($table_check_query);

if ($table_check_result->num_rows == 0) {
    die("La tabla 'perfiles' no existe en la base de datos.");
}

// Fetch user data
$sql = "
SELECT 
    usuarios.id, 
    usuarios.nombre, 
    usuarios.apellido, 
    usuarios.telefono, 
    usuarios.correo, 
    usuarios.contraseña, 
    usuarios.created_at, 
    perfiles.direccion, 
    perfiles.fecha_nacimiento, 
    perfiles.profile_photo 
FROM 
    usuarios 
LEFT JOIN 
    perfiles 
ON 
    usuarios.id = perfiles.usuario_id 
WHERE 
    usuarios.id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Usuario no encontrado.");
}

// Guarda la ruta de la foto de perfil en la sesión
$_SESSION['profile_photo'] = $user['profile_photo'];

// Update user data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_nombre = $_POST['nombre'];
    $new_apellido = $_POST['apellido'];
    $new_telefono = $_POST['telefono'];
    $new_correo = $_POST['correo'];
    $new_direccion = $_POST['direccion'];
    $new_fecha_nacimiento = $_POST['fecha_nacimiento'];
    
    // Handle profile photo upload
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_photo"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        // Check file type
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
                $profile_photo = $target_file;
                $_SESSION['profile_photo'] = $profile_photo; // Actualiza la sesión con la nueva foto
            } else {
                $error = "Error al subir la foto de perfil.";
                $profile_photo = $user['profile_photo'];
            }
        } else {
            $error = "Solo se permiten archivos JPG, JPEG, PNG y GIF.";
            $profile_photo = $user['profile_photo'];
        }
    } else {
        $profile_photo = $user['profile_photo'];
    }

    // Check if profile exists
    $profile_check_sql = "SELECT id FROM perfiles WHERE usuario_id = ?";
    $profile_check_stmt = $conn->prepare($profile_check_sql);
    $profile_check_stmt->bind_param("i", $user_id);
    $profile_check_stmt->execute();
    $profile_check_result = $profile_check_stmt->get_result();
    $profile_exists = $profile_check_result->num_rows > 0;

    if ($profile_exists) {
        $update_sql = "UPDATE perfiles SET direccion = ?, fecha_nacimiento = ?, profile_photo = ? WHERE usuario_id = ?";
    } else {
        $update_sql = "INSERT INTO perfiles (direccion, fecha_nacimiento, profile_photo, usuario_id) VALUES (?, ?, ?, ?)";
    }

    $update_stmt = $conn->prepare($update_sql);
    if (!$update_stmt) {
        die("Error preparing update statement: " . $conn->error);
    }
    $update_stmt->bind_param("sssi", $new_direccion, $new_fecha_nacimiento, $profile_photo, $user_id);

    if ($update_stmt->execute()) {
        $user_update_sql = "UPDATE usuarios SET nombre = ?, apellido = ?, telefono = ?, correo = ? WHERE id = ?";
        $user_update_stmt = $conn->prepare($user_update_sql);
        if (!$user_update_stmt) {
            die("Error preparing user update statement: " . $conn->error);
        }
        $user_update_stmt->bind_param("ssssi", $new_nombre, $new_apellido, $new_telefono, $new_correo, $user_id);

        if ($user_update_stmt->execute()) {
            $_SESSION['username'] = $new_nombre; // Update session variable
            header("Location: profile.php");
            exit();
        } else {
            $error = "Error updating user profile: " . $conn->error;
        }
    } else {
        $error = "Error updating profile: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="css/perfil.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="inicio.php">Inicio</a></li>
                <li><a href="profile.php">Perfil</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Perfil de Usuario</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post" action="profile.php" enctype="multipart/form-data">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($user['apellido']); ?>" required>
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($user['telefono']); ?>" required>
            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($user['correo']); ?>" required>
            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($user['direccion']); ?>">
            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($user['fecha_nacimiento']); ?>">
            <label for="profile_photo">Foto de Perfil:</label>
            <input type="file" id="profile_photo" name="profile_photo">
            <?php if ($_SESSION['profile_photo']): ?>
                <img src="<?php echo htmlspecialchars($_SESSION['profile_photo']); ?>" alt="Foto de Perfil" style="width:100px;height:100px;">
            <?php endif; ?>
            <button type="submit">Actualizar Perfil</button>
        </form>
        <h2>Datos del usuario</h2>
        <p><strong>ID:</strong> <?php echo htmlspecialchars($user['id']); ?></p>
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($user['nombre']); ?></p>
        <p><strong>Apellido:</strong> <?php echo htmlspecialchars($user['apellido']); ?></p>
        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($user['telefono']); ?></p>
        <p><strong>Correo:</strong> <?php echo htmlspecialchars($user['correo']); ?></p>
        <p><strong>Contraseña:</strong> <?php echo htmlspecialchars($user['contraseña']); ?></p>
        <p><strong>Fecha de Creación:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
    </main>
</body>
</html>