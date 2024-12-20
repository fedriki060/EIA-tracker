<?php  
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de Rutas</title>
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js"></script>
    <style>

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #E0E0E0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h1 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #E0E0E0;
            text-align: center;
        }

        .menu-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
            width: 90%;
            max-width: 500px;
        }

        .menu-option {
            position: relative;
            background-color: #1F1F1F;
            color: #E0E0E0;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 1.1em;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        .menu-option:hover {
            background-color: #0097a7;
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            animation: pulse 1s infinite;
        }

        .bus-icon {
            position: absolute;
            font-size: 30px;
            color: #f6f6f6;
            margin-top: -4px;
            opacity: 0; /* Inicialmente oculto */
            transition: opacity 0.3s ease; /* Transición suave */
        }

        /* Las animaciones de los buses */
        .menu-option:hover .bus1 { animation: drive1 1.5s forwards; opacity: 1; }
        .menu-option:hover .bus2 { animation: drive2 1.5s forwards; opacity: 1; }
        .menu-option:hover .bus3 { animation: drive3 1.5s forwards; opacity: 1; }
        .menu-option:hover .bus4 { animation: drive4 1.5s forwards; opacity: 1; }
        .menu-option:hover .bus5 { animation: drive5 1.5s forwards; opacity: 1; }
        .menu-option:hover .bus6 { animation: drive6 1.5s forwards; opacity: 1; }
        .menu-option:hover .bus7 { animation: drive7 1.5s forwards; opacity: 1; }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 10px #16b5ff, 0 0 20px #16b5ff; }
            50% { box-shadow: 0 0 20px #00e5ff, 0 0 30px #00e5ff; }
        }

        /* Animaciones de movimiento de los buses */
        @keyframes drive1 { from { left: -40px; } to { left: 100%; } }
        @keyframes drive2 { from { right: -40px; } to { right: 120%; } }
        @keyframes drive3 { from { left: -40px; top: 10px; } to { left: 100%; top: 10px; } }
        @keyframes drive4 { from { right: -40px; top: 10px; } to { right: 100%; top: 10px; } }
        @keyframes drive5 { from { left: -40px; bottom: 10px; } to { left: 100%; bottom: 10px; } }
        @keyframes drive6 { from { right: -40px; bottom: 10px; } to { right: 100%; bottom: 10px; } }
        @keyframes drive7 { from { left: -40px; top: 50%; transform: translateY(-50%); } to { left: 100%; top: 50%; transform: translateY(-50%); } }

        .back-button {
            position: fixed;
            bottom: 20px;
            left: 20px;
            padding: 10px 20px;
            font-size: 1em;
            color: #E0E0E0;
            background-color: #333333;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(1, 225, 255, 0.873);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .back-button:hover {
            background-color: #444444;
            transform: scale(1.05);
        }

    </style>
</head>
<body>
    <h1>¿Qué ruta quieres elegir? <?php echo htmlspecialchars($username); ?></h1>
    
    <div class="menu-container">
        <div class="menu-option" onclick="window.location.href='https://eia-tracker.great-site.net/Mayorca/tracking.php'">
            Ruta Mayorca
            <span class="iconify bus-icon bus1" data-icon="game-icons:bus"></span>
        </div>
        <div class="menu-option" onclick="window.location.href='https://eia-tracker.great-site.net/Aguacatala/tracking.php'">
            Ruta Sofasa, Aguacatala, Bosques de Zuñiga
            <span class="iconify bus-icon bus2" data-icon="game-icons:bus"></span>
        </div>
        <div class="menu-option" onclick="window.location.href='https://eia-tracker.great-site.net/Palace/tracking.php'">
            Ruta Clínica Las Américas – Palacé
            <span class="iconify bus-icon bus3" data-icon="game-icons:bus"></span>
        </div>
        <div class="menu-option" onclick="window.location.href='https://eia-tracker.great-site.net/Loreto/tracking.php'">
            Ruta San Antonio – Loreto
            <span class="iconify bus-icon bus4" data-icon="game-icons:bus"></span>
        </div>
        <div class="menu-option" onclick="window.location.href='https://eia-tracker.great-site.net/Rionegro/tracking.php'">
            Ruta Rionegro
            <span class="iconify bus-icon bus5" data-icon="game-icons:bus"></span>
        </div>
        <div class="menu-option" onclick="window.location.href='https://eia-tracker.great-site.net/Robledo/tracking.php'">
            Éxito de Robledo
            <span class="iconify bus-icon bus6" data-icon="game-icons:bus"></span>
        </div>
        <div class="menu-option" onclick="window.location.href='https://eia-tracker.great-site.net/Mayorcazn/tracking.php'">
            Mayorca - Bosques de Zúñiga (Unificada)
            <span class="iconify bus-icon bus7" data-icon="game-icons:bus"></span>
        </div>
    </div>
    <button class="back-button" onclick="window.location.href='home.php'">Atrás</button>
</body>
</html>
