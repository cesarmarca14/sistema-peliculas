<?php
include 'conexion.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Averiguar el ID del usuario que está navegando
$usuario_id = 0;
if (isset($_SESSION['usuario'])) {
    $u_name = $_SESSION['usuario'];
    $user_query = $conn->query("SELECT id FROM usuarios WHERE username = '$u_name'");
    if ($user_query && $user_query->num_rows > 0) {
        $user_data = $user_query->fetch_assoc();
        $usuario_id = (int) $user_data['id'];
    }
}

// 2. Obtener la acción que se va a ejecutar
$action = $_GET['action'] ?? '';

// 3. ACCIÓN: Registrar Comentarios de Usuarios
if ($action == 'comentar') {
    // Si no está logueado, no dejamos comentar
    if ($usuario_id === 0) {
        echo "no_logged";
        exit;
    }

    $pelicula_id = (int) $_POST['pelicula_id'];
    $comentario = $conn->real_escape_string($_POST['comentario']);

    if (!empty($comentario)) {
        $conn->query("INSERT INTO comentarios (usuario_id, pelicula_id, comentario) VALUES ($usuario_id, $pelicula_id, '$comentario')");
        echo "success";
    } else {
        echo "empty";
    }
    exit;
}

// Aquí abajo seguro tienes tus otras acciones viejas (como marcar_vista o favoritos)...
// Si es así, puedes dejar tus otros "if ($action == '...')" aquí abajo.
?>