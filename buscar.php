<?php
include 'conexion.php';

if (isset($_GET['q'])) {
    $q = $conn->real_escape_string($_GET['q']);
    // Buscar películas que empiecen o contengan lo escrito
    $sql = "SELECT titulo FROM peliculas WHERE titulo LIKE '%$q%' LIMIT 5";
    $result = $conn->query($sql);

    $sugerencias = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $sugerencias[] = $row['titulo'];
        }
    }
    echo json_encode($sugerencias);
}
?>