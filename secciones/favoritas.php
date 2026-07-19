<section id="view-favoritas" class="view-section">
    <h2 class="section-title">Mis Películas Favoritas ❤️</h2>
    <div class="movies-grid">
        <?php
        if ($usuario_logueado) {
            $u_name = $_SESSION['usuario'];
            $sql_fav = "SELECT p.*, IFNULL(AVG(c.puntos), 0) as promedio 
                        FROM favoritos f 
                        JOIN peliculas p ON f.pelicula_id = p.id 
                        LEFT JOIN calificaciones c ON p.id = c.pelicula_id
                        WHERE f.usuario_id = (SELECT id FROM usuarios WHERE username='$u_name')
                        GROUP BY p.id";
            $res_fav = $conn->query($sql_fav);
            if ($res_fav && $res_fav->num_rows > 0) {
                while ($row = $res_fav->fetch_assoc()) {
                    echo "
                    <div class='movie-card' onclick=\"openMovie({$row['id']}, '{$row['titulo']}', '{$row['genero']}', '" . addslashes($row['sinopsis']) . "', '{$row['escenas']}')\">
                        <img src='{$row['imagen']}' class='movie-img'>
                        <div class='movie-info'>
                            <span class='movie-meta'>{$row['genero']}</span>
                            <div class='movie-title'>{$row['titulo']}</div>
                            <span class='badge-rating'>⭐ " . number_format($row['promedio'], 1) . "</span>
                        </div>
                    </div>";
                }
            } else {
                echo "<p style='grid-column:1/-1;'>Aún no has agregado películas a tus favoritas.</p>";
            }
        }
        ?>
    </div>
</section>