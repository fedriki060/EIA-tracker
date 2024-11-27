<?php
session_start(); // Iniciar la sesión

// Verificar si se ha enviado la solicitud para establecer la verificación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['verified'] = true; // Guardar que la contraseña fue verificada
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}
?>
