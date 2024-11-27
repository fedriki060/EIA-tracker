<?php
session_start();

// Comprobar si la ruta fue pasada
if (isset($_GET['route'])) {
    $_SESSION['route_accessed'] = $_GET['route'];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>