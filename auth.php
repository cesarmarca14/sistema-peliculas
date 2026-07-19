<?php
include 'conexion.php';
session_start();

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'register') {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $dni = $_POST['dni'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validar formato de username (solo letras y números)
    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        echo "invalid_format";
        exit;
    }

    // Validar si el usuario o DNI ya existen
    $check = $conn->query("SELECT id FROM usuarios WHERE username = '$username' OR dni = '$dni'");
    if ($check->num_rows > 0) {
        echo "exists";
        exit;
    }

    // Guardar en la base de datos (Contraseña simple para entorno de prueba escolar)
    $sql = "INSERT INTO usuarios (nombre, apellidos, dni, username, password) VALUES ('$nombre', '$apellidos', '$dni', '$username', '$password')";
    if ($conn->query($sql)) {
        $_SESSION['usuario'] = $username;
        echo "success";
    } else {
        echo "error";
    }
}

if ($action == 'login') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM usuarios WHERE username = '$username' AND password = '$password'");
    if ($result->num_rows > 0) {
        $_SESSION['usuario'] = $username;
        echo "success";
    } else {
        echo "fail";
    }
}

if ($action == 'forgot') {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $dni = $_POST['dni'];

    $result = $conn->query("SELECT username, password FROM usuarios WHERE nombre='$nombre' AND apellidos='$apellidos' AND dni='$dni'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "Tus datos son:\nUsuario: " . $row['username'] . "\nContraseña: " . $row['password'];
    } else {
        echo "No se encontró ninguna cuenta con esos datos.";
    }
}
?>