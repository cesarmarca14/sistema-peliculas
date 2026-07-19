<?php
include 'conexion.php';
session_start();
$usuario_logueado = isset($_SESSION['usuario']) ? 1 : 0;
$search = isset($_GET['search']) ? $_GET['search'] : '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PastelFlix</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

    <aside class="sidebar">
        <div class="logo">🌸 PastelFlix</div>
        <button class="menu-btn active" onclick="switchView('inicio', this)">Inicio</button>
        <button class="menu-btn" onclick="switchView('recomendaciones', this)">Recomendaciones</button>
        <button class="menu-btn" onclick="switchView('top', this)">🏆 Tops</button>

        <!-- SECCIONES EXCLUSIVAS PARA USUARIOS CONECTADOS -->
        <?php if ($usuario_logueado): ?>
            <button class="menu-btn" onclick="switchView('favoritas', this)">Mis Favoritas</button>
            <button class="menu-btn" onclick="switchView('vistas', this)">Ya las vi</button>
        <?php endif; ?>

        <hr style="border: 1px solid #f1ece6; margin: 10px 0;">
        <?php if (!$usuario_logueado): ?>
            <button class="menu-btn" style="background: var(--pastel-accent)" onclick="openAuthModal('login')">🔑 Ingresar /
                Registro</button>
        <?php else: ?>
            <p style="padding: 10px; font-weight: bold; color: var(--pastel-secondary)">👤 Hola,
                <?php echo $_SESSION['usuario']; ?></p>
            <a href="logout.php" style="text-decoration: none; font-size: 13px; margin-left: 12px; color: red;">Cerrar
                Sesión</a>
        <?php endif; ?>
    </aside>

    <main class="main-content">
        <div class="top-bar">
            <div class="search-container">
                <form class="search-form" method="GET" action="index.php">
                    <input type="text" id="buscador" name="search" class="search-input" autocomplete="off"
                        placeholder="Escribe para buscar de 30 títulos..."
                        value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn-buscar">Buscar</button>
                </form>
                <div id="suggestions" class="suggestions-box"></div>
            </div>
        </div>

        <?php
        include 'secciones/inicio.php';
        include 'secciones/recomendaciones.php';
        include 'secciones/top.php';
        if ($usuario_logueado) {
            include 'secciones/favoritas.php';
            include 'secciones/vistas.php';
        }
        ?>
    </main>

    <!-- POP-UP MODAL DETALLES CON CALIFICADOR POR ESTRELLAS -->
    <div id="movieModal" class="modal">
        <div class="modal-content">
            <button class="close-modal" onclick="closeModal('movieModal')">×</button>
            <h2 id="modalTitle" style="color: var(--pastel-secondary);"></h2>
            <p id="modalGenre" style="font-weight: bold; color: var(--text-light);"></p>
            <p id="modalSynopsis"></p>

            <!-- Estrellas de puntuación interactiva -->
            <div id="ratingSection" style="display: <?php echo $usuario_logueado ? 'block' : 'none'; ?>;">
                <h3>Tu Calificación (Varía el Top global):</h3>
                <div class="stars-rating-container">
                    <span class="star-item" onclick="sendRating(1)">★</span>
                    <span class="star-item" onclick="sendRating(2)">★</span>
                    <span class="star-item" onclick="sendRating(3)">★</span>
                    <span class="star-item" onclick="sendRating(4)">★</span>
                    <span class="star-item" onclick="sendRating(5)">★</span>
                </div>
            </div>

            <h3>Escenas de la película</h3>
            <div id="modalScenes" class="escenas-container"></div>

            <button class="btn-play" id="btnPlayAction">▶ ¡Ver ahora!</button>
        </div>
    </div>

    <!-- MODAL REGISTRO / LOGIN (Se mantiene igual) -->
    <div id="authModal" class="modal">
        <div class="modal-content" style="max-width: 400px;">
            <button class="close-modal" onclick="closeModal('authModal')">×</button>
            <div id="loginFormSection">
                <h2>Iniciar Sesión</h2><br>
                <form id="loginForm" onsubmit="submitLogin(event)">
                    <div class="form-group"><label>Usuario</label><input type="text" name="username" required></div><br>
                    <div class="form-group"><label>Contraseña</label><input type="password" name="password" required>
                    </div><br>
                    <button type="submit" class="btn-play" style="width:100%">Ingresar</button>
                </form><br>
                <a href="#" onclick="toggleAuthViews('register')">¿No tienes cuenta? Regístrate aquí</a>
            </div>
            <div id="registerFormSection" style="display:none;">
                <h2>Regístrate</h2><br>
                <form id="registerForm" onsubmit="submitRegister(event)">
                    <div class="form-group"><label>Nombre</label><input type="text" name="nombre" required></div>
                    <div class="form-group"><label>Apellidos</label><input type="text" name="apellidos" required></div>
                    <div class="form-group"><label>DNI</label><input type="text" name="dni" required></div>
                    <div class="form-group"><label>Usuario</label><input type="text" name="username" required></div>
                    <div class="form-group"><label>Contraseña</label><input type="password" name="password" required>
                    </div><br>
                    <button type="submit" class="btn-play" style="width:100%">Crear Cuenta</button>
                </form><br>
                <a href="#" onclick="toggleAuthViews('login')">Ya tengo cuenta</a>
            </div>
        </div>
    </div>

    <script>
        const isLogged = <?php echo $usuario_logueado; ?>;
        let activeMovieId = null; // Guardará el ID de la película abierta
    </script>
    <script src="js/app.js"></script>
</body>

</html>