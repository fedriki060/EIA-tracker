<?php
// db.php

// Configuración de la base de datos
$servername = "sql100.infinityfree.com";
$username = "if0_37337860";
$password = "UniEIA060";
$db_name = "if0_37337860_db";



// Crear conexión
$conn = new mysqli($host, $username, $password, $db_name);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>