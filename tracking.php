<?php
session_start();
// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Obtener el nombre de usuario de la sesión
$username = $_SESSION['username'] ?? 'Usuario'; // Reemplaza 'Usuario' si no hay nombre en la sesión

// Conexión a la base de datos
$servername = "sql100.infinityfree.com"; // Cambia esto si tu servidor no es local
$db_username = "if0_37337860"; // Reemplaza con tu nombre de usuario de la base de datos
$db_password = "UniEIA060"; // Reemplaza con tu contraseña de la base de datos
$dbname = "if0_37337860_db"; // Reemplaza con el nombre de tu base de datos

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa con Leaflet</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        #map {
            height: 90vh;
        }
        body {
            background-color: black;
            color: white;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .button {
            margin: 0 10px;
            padding: 10px 20px;
            background-color: lightblue;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: black;
        }
        .button:hover {
            background-color: deepskyblue;
        }
        #top-section {
            height: 10vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #333;
        }
    </style>
</head>
<body>

<div id="top-section">
    <button id="busButton" class="button">Ubicar bus</button>
    <button id="centerButton" class="button">Centrar mapa</button>
</div>

<div id="map"></div>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    // Inicialización del mapa centrado en una ubicación predeterminada
    var map = L.map('map').setView([6.2154, -75.5775], 15); // Centro de Carrera 43A
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var userMarker;
    var busMarker;

    // Actualizar la ubicación del usuario con alta precisión
    function updateLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    var userCoords = [position.coords.latitude, position.coords.longitude];

                    if (!userMarker) {
                        map.setView(userCoords, 20);
                        userMarker = L.marker(userCoords).addTo(map)
                            .bindPopup('<?php echo $username; ?>') // Muestra el nombre de usuario
                            .openPopup();
                    } else {
                        userMarker.setLatLng(userCoords);
                    }
                },
                function() {
                    alert("No se pudo obtener la ubicación. Asegúrate de que la ubicación esté habilitada.");
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        } else {
            alert("Geolocalización no soportada en este navegador.");
        }
    }

    // Actualiza la ubicación del usuario cada 5 segundos
    updateLocation();
    setInterval(updateLocation, 5000);

    // Centrar el mapa en la ubicación del usuario
    document.getElementById('centerButton').addEventListener('click', function() {
        if (userMarker) {
            var coords = userMarker.getLatLng();
            map.setView(coords, map.getZoom());
        } else {
            alert('Por favor, espera a que se obtenga la ubicación inicial.');
        }
    });

    // Obtener la ubicación del bus desde el servidor y hacer una transición suave
    function getBusLocation() {
        fetch('get_bus_location.php') // Solicita la ubicación actualizada del bus
            .then(response => response.json())
            .then(data => {
                if (data.latitude && data.longitude) {
                    var busCoords = [data.latitude, data.longitude];

                    // Si no existe el marcador, crearlo
                    if (!busMarker) {
                        busMarker = L.marker(busCoords, {
                            icon: L.icon({
                                iconUrl: 'icon.png', // Reemplaza con la URL del ícono del bus
                                iconSize: [25, 41],
                                iconAnchor: [12, 41],
                                popupAnchor: [1, -34],
                            })
                        }).addTo(map).bindPopup('Ubicación del bus').openPopup();
                    } else {
                        // Crear una animación suave hacia la nueva posición
                        smoothTransition(busMarker, busCoords);
                    }
                } else {
                    console.log('Estamos buscando la ubicación del bus');
                }
            })
            .catch(error => console.error('Error al obtener la ubicación del bus:', error));
    }

    // Función para realizar una transición suave
    function smoothTransition(marker, newCoords) {
        var currentCoords = marker.getLatLng();
        var latLngs = [currentCoords, newCoords];
        
        // Crear una línea temporal entre la posición actual y la nueva posición
        var line = L.polyline(latLngs, { color: 'transparent' }).addTo(map);
        var duration = 2000; // Duración de la transición en milisegundos
        var startTime = performance.now();

        function animateMarker(time) {
            var progress = (time - startTime) / duration;
            if (progress < 1) {
                // Interpolar la posición entre el punto inicial y el final
                var interpolatedPosition = L.latLng(
                    currentCoords.lat + (newCoords[0] - currentCoords.lat) * progress,
                    currentCoords.lng + (newCoords[1] - currentCoords.lng) * progress
                );
                marker.setLatLng(interpolatedPosition);
                requestAnimationFrame(animateMarker);
            } else {
                // Finalizar en la posición exacta
                marker.setLatLng(newCoords);
                map.removeLayer(line); // Eliminar la línea temporal
            }
        }

        requestAnimationFrame(animateMarker);
    }

    // Actualiza la ubicación del bus cada 5 segundos
    setInterval(getBusLocation, 5000);
    
    // Centrar el mapa en el bus cuando se haga clic en el botón
    document.getElementById('busButton').addEventListener('click', function() {
        if (busMarker) {
            map.setView(busMarker.getLatLng(), map.getZoom());
        } else {
            alert('La ubicación del bus no está disponible.');
        }
    });
</script>

</body>
</html>
