<?php
// db.php

// Configuración de la base de datos
//oculto



// Crear conexión
$conn = new mysqli($host, $username, $password, $db_name);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>