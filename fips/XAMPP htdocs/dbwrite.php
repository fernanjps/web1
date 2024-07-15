<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $temp = $_POST['sendval'];
    $hum = $_POST['sendval2'];
    
    // Verifica que los datos no estén vacíos
    if (!empty($temp) && !empty($hum)) {
        $sql = "INSERT INTO medidas (Temp_actual, Humed_actual, Dia, Hora) VALUES ('$temp', '$hum', CURDATE(), CURTIME())";
        
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "No data received";
    }
} else {
    echo "Invalid request method";
}

$conn->close();
?>
