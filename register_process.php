<?php
// Conectar a la base de datos
$servername = "sql100.infinityfree.com";  // Servidor (en XAMPP es localhost)
$username = "if0_37337860";  // Usuario (en XAMPP, el usuario predeterminado es root)
$password = "UniEIA060";  // Contraseña (en XAMPP, normalmente está vacía)
$dbname = "if0_37337860_db";  // Nombre de la base de datos que creaste

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del formulario de registro
$user = $_POST['username'];
$email = $_POST['email'];
$pass = $_POST['password'];

// Encriptar la contraseña antes de guardarla
$hashed_password = password_hash($pass, PASSWORD_DEFAULT);

// Preparar la sentencia SQL para insertar los datos en la tabla 'usuarios'
$sql = "INSERT INTO usuarios (username, email, password) VALUES (?, ?, ?)";

// Preparar la consulta
$stmt = $conn->prepare($sql);

// Verificar si la preparación fue exitosa
if ($stmt) {
    // Vincular los parámetros
    $stmt->bind_param("sss", $user, $email, $hashed_password);
    
    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Registro exitoso!";
        header("Location: login.html");  // Redirigir a la página de login después de registrar al usuario
        exit();
    } else {
        echo "Error al registrar: " . $stmt->error;
    }

    // Cerrar la consulta
    $stmt->close();
} else {
    echo "Error en la preparación de la consulta: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
