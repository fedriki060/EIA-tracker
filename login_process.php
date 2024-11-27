<?php
session_start();

// Conectar a la base de datos
$servername = "sql100.infinityfree.com";
$username = "if0_37337860";
$password = "UniEIA060";
$dbname = "if0_37337860_db";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del formulario de login
$email = $_POST['email'];
$pass = $_POST['password'];

// Preparar la sentencia SQL para obtener los datos del usuario
$sql = "SELECT id, username, password FROM usuarios WHERE email = ?"; // Asegúrate de incluir el campo 'username'
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

// Verificar si el usuario existe
if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $username, $hashed_password);
    $stmt->fetch();

    // Verificar la contraseña
    if (password_verify($pass, $hashed_password)) {
        // Guardar el ID del usuario y el nombre en la sesión
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username; // Guardar el nombre de usuario en la sesión
        header("Location: home.php");
        exit();
    } else {
        $_SESSION['error'] = "Contraseña incorrecta.";
    }
} else {
    $_SESSION['error'] = "Usuario no encontrado.";
}

$stmt->close();
$conn->close();

// Redirigir a la página de login
header("Location: login.html");
exit();
?>
