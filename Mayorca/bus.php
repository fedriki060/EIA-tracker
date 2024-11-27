<?php
session_start(); // Iniciar la sesión

// Verificar si la clave fue ingresada
if (!isset($_SESSION['verified']) || $_SESSION['verified'] !== true) {
    die("Has ingresado sin verificar que eres un conductor. Por favor, ve a la página de selección de rutas para conductor e introduce la clave.");
}

// db_connection.php
//Oculto

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Comprobar si ha pasado una hora desde la última eliminación
if (!isset($_SESSION['last_cleanup'])) {
    $_SESSION['last_cleanup'] = time(); // Inicializar el tiempo de limpieza
}

$now = time();
$one_hour = 3600; // Una hora en segundos

if ($now - $_SESSION['last_cleanup'] >= $one_hour) {
    // Ejecutar la eliminación de registros
    $conn->query("DELETE FROM bus_locations");
    $_SESSION['last_cleanup'] = $now; // Actualizar el tiempo de limpieza
}

// Si se envían las coordenadas, guardarlas en la base de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;

    if ($latitude !== null && $longitude !== null) {
        $stmt = $conn->prepare("INSERT INTO bus_locations (latitude, longitude) VALUES (?, ?)");
        $stmt->bind_param("dd", $latitude, $longitude);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al insertar en la base de datos.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Coordenadas no válidas.']);
    }
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubicación del Bus</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        #map {
            height: 92vh;
            margin: 20px 0;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        h1 {
            margin: 20px 0;
            color: #333;
        }
    </style>
</head>
<body>
    <h1>Se está mostrando tu ubicación</h1>
    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([6.2154, -75.5775], 15); // Ajusta las coordenadas iniciales según sea necesario
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var busMarker;

        // Función para enviar las coordenadas al servidor
        function sendCoordinates(latitude, longitude) {
            fetch('bus.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `latitude=${latitude}&longitude=${longitude}`
            })
            .then(response => response.json())
            .then(data => console.log(data))
            .catch(error => console.error('Error:', error));
        }

        // Función para actualizar la ubicación del marcador en el mapa
        function updateBusLocation(latitude, longitude) {
            if (!busMarker) {
                // Crear el marcador por primera vez
                busMarker = L.marker([latitude, longitude]).addTo(map)
                    .bindPopup('Tu ubicación')
                    .openPopup();
                map.setView([latitude, longitude], 30);
            } else {
                // Mover suavemente el marcador a la nueva ubicación
                moveMarkerSmoothly(busMarker, [latitude, longitude]);
            }
        }

        // Función para mover el marcador suavemente
        function moveMarkerSmoothly(marker, newCoords) {
            const currentCoords = marker.getLatLng();
            const duration = 1000; // Duración de la animación en milisegundos
            const steps = 50; // Número de pasos para la animación
            const latStep = (newCoords[0] - currentCoords.lat) / steps;
            const lngStep = (newCoords[1] - currentCoords.lng) / steps;

            let currentStep = 0;

            function animate() {
                if (currentStep < steps) {
                    marker.setLatLng([
                        currentCoords.lat + latStep * currentStep,
                        currentCoords.lng + lngStep * currentStep,
                    ]);
                    currentStep++;
                    requestAnimationFrame(animate); // Llama a la siguiente actualización
                } else {
                    marker.setLatLng(newCoords);
                }
            }

            animate();
        }

        // Obtener la ubicación del dispositivo y actualizarla en el servidor y en el mapa
        function updateLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;

                    // Enviar coordenadas al servidor
                    sendCoordinates(latitude, longitude);

                    // Actualizar la ubicación del bus en el mapa
                    updateBusLocation(latitude, longitude);
                }, function() {
                    console.error("No se pudo obtener la ubicación.");
                });
            }
        }

        // Actualizar ubicación cada segundo
        setInterval(updateLocation, 1000);
    </script>
</body>
</html>
