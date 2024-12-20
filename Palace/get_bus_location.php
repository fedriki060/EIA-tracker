<?php
// Conexión a la base de datos
//Oculto

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
