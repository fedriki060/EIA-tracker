<?php
// Conexión a la base de datos
$servername = "sql100.infinityfree.com";
$username = "if0_37337860";
$password = "UniEIA060";
$dbname = "if0_37337860_palace";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener la última ubicación del bus
$result = $conn->query("SELECT latitude, longitude FROM bus_locations ORDER BY timestamp DESC LIMIT 1");
$bus_location = $result->fetch_assoc();

// Enviar la ubicación en formato JSON
echo json_encode([
    'latitude' => $bus_location['latitude'] ?? null,
    'longitude' => $bus_location['longitude'] ?? null
]);

$conn->close();
?>
