<?php
session_start();

// Depuración
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Verificar si el usuario está logueado
$loggedIn = isset($_SESSION['username']);
$username = $loggedIn ? $_SESSION['username'] : 'Invitado';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de Sesión</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .message {
            text-align: center;
            font-size: 2em;
            color: #333;
        }
    </style>
</head>
<body>

    <div class="message">
        <p>Bienvenido, <?php echo htmlspecialchars($username); ?>!</p>
    </div>

</body>
</html>
