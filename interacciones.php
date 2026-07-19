<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['usuario'])) {
    echo "no_session";
    exit;
}

$username = $_SESSION['usuario'];
// Obtener la ID real del usuario conectado
$user_res = $conn->query("SELECT id FROM usuarios WHERE username = '$username'");
$user_row = $user_res->fetch_assoc();
$usuario_id = $user_row['id'];

$action = isset($_GET['action']) ? $_GET['action'] : '';

// 1. Alternar Favorito (Corazón)
if ($action == 'toggle_favorito') {
    $pelicula_id = (int) $_POST['pelicula_id'];

    $check = $conn->query("SELECT id FROM favoritos WHERE usuario_id = $usuario_id AND pelicula_id = $pelicula_id");
    if ($check->num_rows > 0) {
        $conn->query("DELETE FROM favoritos WHERE usuario_id = $usuario_id AND pelicula_id = $pelicula_id");
        echo "removed";
    } else {
        $conn->query("INSERT INTO favoritos (usuario_id, pelicula_id) VALUES ($usuario_id, $pelicula_id)");
        echo "added";
    }
}

// 2. Registrar Calificación por Estrellas
if ($action == 'calificar') {
    $pelicula_id = (int) $_POST['pelicula_id'];
    $puntos = (int) $_POST['puntos'];

    if ($puntos < 1 || $puntos > 5) {
        echo "error";
        exit;
    }

    $conn->query("INSERT INTO calificaciones (usuario_id, pelicula_id, puntos) VALUES ($usuario_id, $pelicula_id, $puntos) 
                  ON DUPLICATE KEY UPDATE puntos = $puntos");
    echo "success";
}

// 3. Registrar Historial (Ya vista)
if ($action == 'marcar_vista') {
    $pelicula_id = (int) $_POST['pelicula_id'];
    $conn->query("INSERT IGNORE INTO p_vistas (usuario_id, pelicula_id) VALUES ($usuario_id, $pelicula_id)");
    echo "success";
}
?>