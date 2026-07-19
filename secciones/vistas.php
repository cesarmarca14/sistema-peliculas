<section id="view-vistas" class="view-section">
    <h2 class="section-title">Historial: Películas que ya vi 👁️</h2>
    <div class="movies-grid">
        <?php
        if ($usuario_logueado) {
            $u_name = $_SESSION['usuario'];
            $sql_vis = "SELECT p.*, f.fecha_vista 
                        FROM peliculas_vistas f 
                        JOIN peliculas p ON f.pelicula_id = p.id 
                        WHERE f.usuario_id = (SELECT id FROM usuarios WHERE username='$u_name')
                        ORDER BY f.fecha_vista DESC";
            $res_vis = $conn->query($sql_vis);
            if ($res_vis && $res_vis->num_rows > 0) {
                while ($row = $res_vis->fetch_assoc()) {
                    echo "
                    <div class='movie-card' onclick=\"openMovie({$row['id']}, '{$row['titulo']}', '{$row['genero']}', '" . addslashes($row['sinopsis']) . "', '{$row['escenas']}')\">
                        <img src='{$row['imagen']}' class='movie-img'>
                        <div class='movie-info'>
                            <span class='movie-meta'>{$row['genero']}</span>
                            <div class='movie-title'>{$row['titulo']}</div>
                            <p style='font-size:11px; color:var(--text-light)'>Vista el: {$row['fecha_vista']}</p>
                        </div>
                    </div>";
                }
            } else {
                echo "<p style='grid-column:1/-1;'>Aquí aparecerán las películas que reproduzcas.</p>";
            }
        }
        ?>
    </div>
</section>