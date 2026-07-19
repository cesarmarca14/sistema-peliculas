<section id="view-top" class="view-section">
    <h2 class="section-title">Lo más visto en la plataforma</h2>
    <div class="movies-grid">
        <?php
        $sql_top = "SELECT p.*, AVG(c.puntos) as promedio 
                    FROM peliculas p 
                    JOIN calificaciones c ON p.id = c.pelicula_id 
                    GROUP BY p.id 
                    ORDER BY promedio DESC 
                    LIMIT 5";

        $res_top = $conn->query($sql_top);
        if ($res_top && $res_top->num_rows > 0) {
            while ($row = $res_top->fetch_assoc()) {
                echo "
                <div class='movie-card' onclick=\"openMovie({$row['id']}, '{$row['titulo']}', '{$row['genero']}', '" . addslashes($row['sinopsis']) . "', '{$row['escenas']}')\">
                    <img src='{$row['imagen']}' class='movie-img' alt='{$row['titulo']}'>
                    <div class='movie-info'>
                        <span class='movie-meta'>{$row['genero']}</span>
                        <div class='movie-title'>{$row['titulo']}</div>
                        <span class='badge-rating' style='background-color: var(--pastel-primary); color: #7c4c4c;'>⭐ " . number_format($row['promedio'], 1) . "</span>
                    </div>
                </div>";
            }
        } else {
            echo "<p style='grid-column: 1/-1;'>No hay suficientes calificaciones para determinar el Top 5.</p>";
        }
        ?>
    </div>
</section>