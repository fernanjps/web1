<?php
// Conexión a la base de datos
include 'conexion.php';

// Inicializar variables para manejar los valores del formulario y el mensaje de estado
$pregunta1 = $pregunta2 = $pregunta3 = $pregunta4 = $pregunta5 = $pregunta6 = $pregunta7 = $pregunta8 = '';
$mensaje = '';

// Preguntas y opciones
$preguntas = [
    'pregunta1' => [
        'label' => '1. ¿Cuál de las siguientes NO es una característica común de las plantas suculentas?',
        'options' => [
            'A' => 'A) Hojas gruesas y carnosas que almacenan agua.',
            'B' => 'B) Raíces profundas que buscan agua en el subsuelo.',
            'C' => 'C) Necesidad de riego escaso y tolerancia a la sequía.'
        ]
    ],
    'pregunta2' => [
        'label' => '2. ¿Qué tipo de suelo es ideal para la mayoría de las plantas de jardín?',
        'options' => [
            'A' => 'A) Suelo arcilloso, que retiene mucha agua y nutrientes.',
            'B' => 'B) Suelo arenoso, que drena rápidamente y es bueno para las plantas con raíces profundas.',
            'C' => 'C) Suelo limoso, que tiene una buena mezcla de arcilla y arena, proporcionando un equilibrio entre retención de agua y drenaje.'
        ]
    ],
    'pregunta3' => [
        'label' => '3. ¿Cuál de estas plagas comunes de jardín se puede controlar eficazmente con jabón insecticida diluido?',
        'options' => [
            'A' => 'A) Pulgones',
            'B' => 'B) Gusanos de alambre',
            'C' => 'C) Topos'
        ]
    ],
    'pregunta4' => [
        'label' => '4. ¿En qué época del año es más recomendable podar la mayoría de los árboles y arbustos?',
        'options' => [
            'A' => 'A) Invierno, cuando las plantas están inactivas.',
            'B' => 'B) Primavera, antes de que broten las nuevas hojas.',
            'C' => 'C) Verano, durante la temporada de crecimiento activo.'
        ]
    ],
    'pregunta5' => [
        'label' => '5. ¿Cuál de estos métodos de jardinería orgánica NO ayuda a mejorar la salud del suelo?',
        'options' => [
            'A' => 'A) Adición de compost rico en nutrientes.',
            'B' => 'B) Siembra de cultivos de cobertura para agregar materia orgánica.',
            'C' => 'C) Rotación de cultivos para prevenir el agotamiento de nutrientes.',
            'D' => 'D) Uso de fertilizantes químicos de liberación lenta.'
        ]
    ],
    'pregunta6' => [
        'label' => '6. ¿Qué tipo de planta es más adecuada para crecer en condiciones de poca luz?',
        'options' => [
            'A' => 'A) Planta de serpiente (Sansevieria)',
            'B' => 'B) Cactus',
            'C' => 'C) Planta de aloe vera'
        ]
    ],
    'pregunta7' => [
        'label' => '7. ¿Cuál es el pH ideal del suelo para la mayoría de las plantas de jardín?',
        'options' => [
            'A' => 'A) Entre 4.5 y 5.5',
            'B' => 'B) Entre 5.5 y 6.5',
            'C' => 'C) Entre 6.5 y 7.5'
        ]
    ],
    'pregunta8' => [
        'label' => '8. ¿Cuál de los siguientes métodos de riego es más eficiente en términos de conservación de agua?',
        'options' => [
            'A' => 'A) Riego por aspersión',
            'B' => 'B) Riego por goteo',
            'C' => 'C) Riego por inundación'
        ]
    ]
];

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pregunta1 = validar($_POST['pregunta1']);
    $pregunta2 = validar($_POST['pregunta2']);
    $pregunta3 = validar($_POST['pregunta3']);
    $pregunta4 = validar($_POST['pregunta4']);
    $pregunta5 = validar($_POST['pregunta5']);
    $pregunta6 = validar($_POST['pregunta6']);
    $pregunta7 = validar($_POST['pregunta7']);
    $pregunta8 = validar($_POST['pregunta8']);

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO formulario2 (pregunta1, pregunta2, pregunta3, pregunta4, pregunta5, pregunta6, pregunta7, pregunta8)
            VALUES ('$pregunta1', '$pregunta2', '$pregunta3', '$pregunta4', '$pregunta5', '$pregunta6', '$pregunta7', '$pregunta8')";

    if ($conn->query($sql) === TRUE) {
        $mensaje = "Formulario enviado correctamente.";
        header("Location: calificar2.php");
        exit();
    } else {
        $mensaje = "Error al enviar el formulario: " . $conn->error;
    }
}

// Función para limpiar y validar datos del formulario
function validar($datos) {
    global $conn; 
    $datos = trim($datos);
    $datos = stripslashes($datos);
    $datos = htmlspecialchars($datos);
    return $conn->real_escape_string($datos); 
}

// Ordenar las preguntas aleatoriamente
$claves_preguntas = array_keys($preguntas);
shuffle($claves_preguntas);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Calificación</title>
    <link rel="stylesheet" href="css/formulario.css">
</head>

<body>
    <div class="container">
        <h2>Formulario de Calificación sobre Plantas y Jardines</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="formulario">
            <?php foreach ($claves_preguntas as $clave): ?>
                <div class="form-group">
                    <label for="<?php echo $clave; ?>"><?php echo $preguntas[$clave]['label']; ?></label>
                    <select name="<?php echo $clave; ?>" id="<?php echo $clave; ?>" required>
                        <option value="">Seleccione una opción</option>
                        <?php foreach ($preguntas[$clave]['options'] as $valor => $opcion): ?>
                            <option value="<?php echo $valor; ?>"><?php echo $opcion; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary">Terminar Formulario</button>
            <a href="inicio.php" class="btn btn-secondary">Ir a Inicio</a>
        </form>
        <div class="mensaje"><?php echo $mensaje; ?></div>
    </div>
    <script src="js/formulario.js"></script>
</body>

</html>
