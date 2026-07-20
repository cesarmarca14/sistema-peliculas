<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sistema-peliculas"; // Nombre actualizado
$port = 3307; // Puerto donde corre tu MySQL de XAMPP

$conn = new mysqli($host, $user, $pass, $db ,$port);


if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
$conn->set_charset("utf8");
?>